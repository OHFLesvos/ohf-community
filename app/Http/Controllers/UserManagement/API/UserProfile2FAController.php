<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\Store2FA;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;

class UserProfile2FAController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->tfa_secret === null) {
            $secret = $this->generateSecret();
            $request->session()->put('temp_2fa_secret', $secret);

            $otp = TOTP::create($secret);
            $otp->setLabel($user->email);
            $otp->setIssuer(config('app.name'));

            $qrCode = $this->generateQrCode($otp->getProvisioningUri());

            return response()
                ->json([
                    'enabled' => false,
                    'image' => base64_encode($qrCode->getString()),
                    'otp' => $otp->getProvisioningUri(),
                    'datauri' => $qrCode->getDataUri(),
                ]);
        }

        return response()
            ->json([
                'enabled' => true,
            ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => [
                'required',
                'numeric',
            ],
        ]);

        $user = Auth::user();
        if ($user->tfa_secret === null) {
            $secret = $request->session()->pull('temp_2fa_secret');
            if ($secret != null) {
                $otp = TOTP::create($secret);
                if ($otp->verify($request->code, null, 1)) {
                    $user->tfa_secret = $secret;
                    $user->save();
                    Log::notice('User enabled 2FA.', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'email' => $user->email,
                        'client_ip' => request()->ip(),
                    ]);

                    return response()
                        ->json([
                            'message' => __('Two-Factor Authentication enabled'),
                        ]);
                }

                $request->session()->put('temp_2fa_secret', $secret);
                return response()
                    ->json([
                        'message' => __('Invalid code, please repeat.'),
                    ], Response::HTTP_BAD_REQUEST);
            }

            return response()
                ->json([
                    'message' => __('Invalid secret, please repeat.'),
                ], Response::HTTP_BAD_REQUEST);
        }

        $otp = TOTP::create($user->tfa_secret);
        if ($otp->verify($request->code, null, 1)) {
            $user->tfa_secret = null;
            $user->save();
            Log::notice('Used disabled 2FA.', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'client_ip' => request()->ip(),
            ]);

            return response()
                ->json([
                    'message' => __('Two-Factor Authentication disabled'),
                ]);
        }

        return response()
            ->json([
                'message' => __('Invalid code, please repeat.'),
            ], Response::HTTP_BAD_REQUEST);
    }

    private function generateSecret(): string
    {
        return trim(Base32::encodeUpper(random_bytes(64)), '=');
    }

    private function generateQrCode(string $data): ResultInterface
    {
        return Builder::create()
            ->writer(new PngWriter())
            ->data($data)
            ->size(400)
            ->build();
    }
}
