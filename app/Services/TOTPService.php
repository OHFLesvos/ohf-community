<?php

namespace App\Services;

use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;

class TOTPService
{
    const VERIFY_WINDOW = 1;

    public function verify(string $secret, string $otp): bool
    {
        return TOTP::create($secret)->verify($otp, null, self::VERIFY_WINDOW);
    }

    public function createProvisionUri(string $secret, string $label): string
    {
        $otp = TOTP::create($secret);
        $otp->setLabel($label);
        $otp->setIssuer(config('app.name'));

        return $otp->getProvisioningUri();
    }

    public function generateSecret(): string
    {
        return trim(Base32::encodeUpper(random_bytes(64)), '=');
    }

    public function generateQrCode(string $data): ResultInterface
    {
        return Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->size(400)
            ->build();
    }

    public function generateProvisionQrCode(string $secret, string $label): string
    {
        $uri = $this->createProvisionUri($secret, $label);
        $qrCode = $this->generateQrCode($uri);

        return $qrCode->getDataUri();
    }
}
