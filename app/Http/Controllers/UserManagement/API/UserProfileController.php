<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\Store2FA;
use App\Http\Requests\UserManagement\StoreNewUserPassword;
use App\Http\Requests\UserManagement\StoreUserProfile;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('roles');

        return response()->json([
            'user' => $user,
            'languages' => language()->allowed(),
            'locale' => App::getLocale(),
        ]);
    }

    public function update(StoreUserProfile $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        if (empty($user->provider_name)) {
            $user->email = $request->email;
        }
        $user->locale = $request->locale;
        if ($user->isDirty()) {
            Log::info('Used updated profile.', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'client_ip' => request()->ip(),
            ]);
            $user->save();

            return response()
                ->json([
                    'message' => __('User profile has been updated.'),
                ]);
        }

        return response()
            ->json([
                'message' => __('No changes have been made.'),
            ]);
    }

    public function updatePassword(StoreNewUserPassword $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        if ($user->isDirty()) {
            Log::notice('Used changed password.', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'client_ip' => request()->ip(),
            ]);
            $user->save();

            return response()
                ->json([
                    'message' => __('Password has been updated.'),
                ]);
        }

        return response()
            ->json([
                'message' => __('No changes have been made.'),
            ]);
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        Log::notice('Used deleted account.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => request()->ip(),
        ]);

        return response()
            ->json([
                'message' => __('Your account has been deleted.'),
            ]);
    }

    public function view2FA(Request $request)
    {
        $user = Auth::user();
        if ($user->tfa_secret == null) {
            $secret = trim(Base32::encodeUpper(random_bytes(64)), '=');
            $request->session()->put('temp_2fa_secret', $secret);

            $otp = TOTP::create($secret);
            $otp->setLabel($user->email);
            $otp->setIssuer(config('app.name'));

            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->data($otp->getProvisioningUri())
                ->size(400)
                ->build();

            return response()
                ->json([
                    'image' => base64_encode($qrCode->getString()),
                ]);
        }

        return response()
            ->json([]);
    }

    public function store2FA(Store2FA $request)
    {
        $user = Auth::user();
        $secret = $request->session()->pull('temp_2fa_secret', null);
        if ($secret != null) {
            $otp = TOTP::create($secret);
            if ($otp->verify($request->code)) {
                $user->tfa_secret = $secret;
                $user->save();
                Log::notice('User enabled 2FA.', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'email' => $user->email,
                    'client_ip' => request()->ip(),
                ]);
                return redirect()->route('userprofile')
                    ->with('info', __('Two-Factor Authentication enabled'));
            }
            return redirect()->back()
                ->with('error', __('Invalid code, please repeat.'));
        }
        return redirect()->back()
            ->with('error', __('Invalid secret, please repeat.'));
    }

    public function disable2FA(Store2FA $request)
    {
        $user = Auth::user();
        if ($user->tfa_secret != null) {
            $otp = TOTP::create($user->tfa_secret);
            if ($otp->verify($request->code)) {
                $user->tfa_secret = null;
                $user->save();
                Log::notice('Used disabled 2FA.', [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'email' => $user->email,
                    'client_ip' => request()->ip(),
                ]);
                return redirect()->route('userprofile')
                    ->with('info', __('Two-Factor Authentication disabled'));
            }
            return redirect()->back()
                ->with('error', __('Invalid code, please repeat.'));
        }
        return redirect()->back()
            ->with('error', __('Invalid secret, please repeat.'));
    }
}
