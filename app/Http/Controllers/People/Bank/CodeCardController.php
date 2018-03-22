<?php

namespace App\Http\Controllers\People\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\LabelAlignment;
use Dompdf\Dompdf;

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

    public function prepareCodeCard() {
        return view('bank.prepareCodeCard');
    }

    public function createCodeCard(Request $request) {
        $pages = isset($request->pages) && is_numeric($request->pages) && $request->pages > 0 ? $request->pages : 1;

        $codes = [];
        for ($i = 0; $i < 10 * $pages; $i++) {
            $code = bin2hex(random_bytes(16));
            $codes[] = base64_encode(self::createQrCode($code, substr($code, 0, 7), 500));
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
        return $dompdf->stream();
    }

    public static function createQrCode($value, $label, $size) {
        $qrCode = new QrCode($value);
        $qrCode->setSize($size);
        $qrCode->setLabel($label, 20, null, LabelAlignment::CENTER);   
        return $qrCode->writeString();
    }
}
