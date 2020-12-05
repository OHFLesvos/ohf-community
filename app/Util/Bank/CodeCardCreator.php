<?php

namespace App\Util\Bank;

use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Illuminate\Support\Facades\Storage;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class CodeCardCreator
{
    const QR_CODE_SIZE = 500;
    const QR_CODE_LABEL_FONT_SIZE = 20;
    const CODE_LENGTH = 16;
    const CODE_SHORT_LENGTH = 7;

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

    public function create(int $amount)
    {
        assert($amount > 0, 'Amount needs to be larger than 0');

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

        $codes = $this->generateCodes($amount);
        $mpdf->writeHTML($this->getContent($codes));

        $name = __('people.code_cards') . ' ' . substr($codes[0], 0, self::CODE_SHORT_LENGTH);
        $mpdf->Output($name . '.pdf', Destination::DOWNLOAD);
    }

    private function getCss(): string
    {
        return file_get_contents(resource_path('css/codeCard.css'));
    }

    private function generateCodes(int $amount): array
    {
        $codes = [];
        for ($i = 0; $i < $amount; $i++) {
            $codes[] = bin2hex(random_bytes(self::CODE_LENGTH));
        }
        return $codes;
    }

    private function getContent(array $codes): string
    {
        return view('bank.codeCard', [
            'codes' => $this->getCodeImages($codes),
            'logo' => $this->getLogoImage(),
            'label' => $this->label,
        ])->render();
    }

    private function getCodeImages(array $codes)
    {
        return array_map(fn ($code) => $this->createQrCode($code, substr($code, 0, self::CODE_SHORT_LENGTH)), $codes);
    }

    /**
     * Create a QR code image
     *
     * @return string Data URI string containing the generated image
     */
    private function createQrCode(string $value, string $label): string
    {
        $qrCode = new QrCode($value);
        $qrCode->setSize(self::QR_CODE_SIZE);
        $qrCode->setLabel($label, self::QR_CODE_LABEL_FONT_SIZE, null, LabelAlignment::CENTER);
        return $qrCode->writeDataUri();
    }

    private function getLogoImage()
    {
        if ($this->logo !== null) {
            return Storage::path($this->logo);
        }
        return null;
    }
}
