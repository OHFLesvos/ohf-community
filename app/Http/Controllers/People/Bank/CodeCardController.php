<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\LabelAlignment;
use Dompdf\Dompdf;
use App\Http\Requests\People\Bank\CreateCodeCard;

class CodeCardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show view for preparing new code card sheet.
     * 
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('bank.prepareCodeCard');
    }

    /**
     * Create new code card sheet and return PDF for download.
     * 
     * @param  \App\Http\Requests\People\Bank\CreateCodeCard  $request
     * @return \Illuminate\Http\Response
     */
    public function download(CreateCodeCard $request) {
        $pages = $request->pages;

        $codes = [];
        for ($i = 0; $i < 10 * $pages; $i++) {
            $code = bin2hex(random_bytes(16));
            $codes[] = base64_encode(self::createQrCode($code, substr($code, 0, 7), 500));
            if ($i == 0) {
                $firstCode = $code;
            }
        }
        $logo = base64_encode(file_get_contents(public_path() . '/img/logo_card.png'));
        $view = view('bank.codeCard', [
            'codes' => $codes,
            'logo' => $logo,
        ])->render();

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($view);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        $dompdf->set_option('dpi', 300);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        return $dompdf->stream(__('people.codes_card') . ' ' . substr($firstCode, 0, 7));
    }

    /**
     * Create a QR code image
     * 
     * @return string string containing the generated image
     */
    private static function createQrCode($value, $label, $size) {
        $qrCode = new QrCode($value);
        $qrCode->setSize($size);
        $qrCode->setLabel($label, 20, null, LabelAlignment::CENTER);   
        return $qrCode->writeString();
    }
}
