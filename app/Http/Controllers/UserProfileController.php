<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserProfile;
use App\Http\Requests\StoreNewUserPassword;
use App\Http\Requests\Store2FA;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('userprofile.view', [
            'user' => Auth::user()
        ]);
    }

    public function update(StoreUserProfile $request) {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($user->isDirty()) {
            Log::info('Used updated profile.', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'client_ip' => request()->ip(),
            ]);
            $user->save();
            return redirect()->route('userprofile')
                             ->with('success', __('userprofile.profile_updated'));
        }
        return redirect()->route('userprofile')
                         ->with('info', __('userprofile.no_changes_made'));
    }

    public function updatePassword(StoreNewUserPassword $request) {
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
            return redirect()->route('userprofile')
                             ->with('success', __('userprofile.password_updated'));
        }
        return redirect()->route('userprofile')
                         ->with('info', __('userprofile.no_changes_made'));
    }

    public function delete() {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        Log::notice('Used deleted account.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => request()->ip(),
        ]);
        return view('userprofile.deleted');
    }

    public function view2FA(Request $request) {
        $user = Auth::user();
        if ($user->tfa_secret == null) {
            $secret = trim(Base32::encodeUpper(random_bytes(64)), '=');
            $request->session()->put('temp_2fa_secret', $secret);
            $otp = TOTP::create($secret);
            $otp->setLabel($user->email);
            $otp->setIssuer(Config::get('app.name'));
            $qrCode = new QrCode($otp->getProvisioningUri());
            $qrCode->setSize(400);
            return view('userprofile.enable2FA', [
                'image' => base64_encode($qrCode->writeString()),
            ]);
        } else {
            return view('userprofile.disable2FA');
        }
    }

    public function store2FA(Store2FA $request) {
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
                    ->with('info', __('userprofile.tfa_enabled'));
            }
            return redirect()->back()
                ->with('error', __('userprofile.invalid_code_please_repeat'));
        }
        return redirect()->back()
            ->with('error', __('userprofile.invalid_secret'));
    }

    public function disable2FA(Store2FA $request) {
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
                    ->with('info', __('userprofile.tfa_disabled'));
            }
            return redirect()->back()
                ->with('error', __('userprofile.invalid_code_please_repeat'));
        }
        return redirect()->back()
            ->with('error', __('userprofile.invalid_secret'));
    }
}
