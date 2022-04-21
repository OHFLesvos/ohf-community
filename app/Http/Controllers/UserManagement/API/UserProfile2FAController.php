<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Services\TOTPService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserProfile2FAController extends Controller
{
    public function index(Request $request, TOTPService $totp)
    {
        $user = Auth::user();
        if ($user->tfa_secret === null) {
            $secret = $totp->generateSecret();
            $request->session()->put('temp_2fa_secret', $secret);

            $uri = $totp->createProvisionUri($secret, $user->email);
            $qrCode = $totp->generateQrCode($uri);

            return response()
                ->json([
                    'enabled' => false,
                    'image' => base64_encode($qrCode->getString()),
                    'otp' => $uri,
                    'datauri' => $qrCode->getDataUri(),
                ]);
        }

        return response()
            ->json([
                'enabled' => true,
            ]);
    }

    public function store(Request $request, TOTPService $totp)
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
                if ($totp->verify($secret, $request->code)) {
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

        if ($totp->verify($user->tfa_secret, $request->code)) {
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
}
