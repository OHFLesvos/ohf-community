<?php

namespace App\Util\Bank;

use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Storage;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Setting;

class CodeCardCreator
{
    private string $firstCode = '';

    private ?string $logo = null;

    private ?string $label = null;

    public function setLogo(?string $logo)
    {
        $this->logo = $logo;
    }

    public function setLabel(?string $label)
    {
        $this->label = $label;
    }

    /**
     * Create new code card sheet and return PDF for download.
     *
     * @param  \App\Http\Requests\People\Bank\CreateCodeCard  $request
     * @return \Illuminate\Http\Response
     */
    public function create(int $pages) {

        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'portrait',
            'dpi' => 300,
            'img_dpi' => 300,
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
        ]);
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->WriteHTML($this->getCss(), HTMLParserMode::HEADER_CSS);

        $mpdf->writeHTML($this->getContent($pages));

        $name = __('people.code_cards') . ' ' . substr($this->firstCode, 0, 7);
        $mpdf->Output($name . '.pdf', Destination::DOWNLOAD);
    }

    private function getCss(): string
    {
        return file_get_contents(resource_path('css/codeCard.css'));
    }

    private function getContent(int $pages): string
    {
        return view('bank.codeCard', [
            'codes' => $this->getCodes($pages),
            'logo' => $this->getLogoImage(),
            'label' => $this->label,
        ])->render();
    }

    private function getCodes(int $pages)
    {
        $codes = [];
        for ($i = 0; $i < 10 * $pages; $i++) {
            $code = bin2hex(random_bytes(16));
            $codes[] = base64_encode($this->createQrCode($code, substr($code, 0, 7), 500));
            if ($i == 0) {
                $this->firstCode = $code;
            }
        }
        return $codes;
    }

    private function getLogoImage()
    {
        if ($this->logo !== null) {
            return 'data:image/png;base64,' . base64_encode(Storage::get($this->logo));
        }
        return null;
    }

    /**
     * Create a QR code image
     *
     * @return string string containing the generated image
     */
    private function createQrCode(string $value, string $label, int $size): string
    {
        $qrCode = new QrCode($value);
        $qrCode->setSize($size);
        $qrCode->setLabel($label, 20, null, LabelAlignment::CENTER);
        return $qrCode->writeString();
    }
}
