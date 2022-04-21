<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\StoreNewUserPassword;
use App\Http\Requests\UserManagement\StoreUserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
}
