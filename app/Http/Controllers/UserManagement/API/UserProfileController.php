<?php

namespace App\Http\Controllers\UserManagement\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserManagement\UpdatePassword as UpdatePassword;
use App\Http\Requests\UserManagement\UpdateProfile as UpdateProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Storage;

class UserProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user()->load('roles');

        return response()
            ->json([
                'user' => $user,
                'avatar_url' => $user->avatarUrl(),
                'languages' => language()->allowed(),
                'locale' => App::getLocale(),
                'can_delete' => ! ($user->is_super_admin && User::where('is_super_admin', true)->count() == 1),
            ]);
    }

    public function update(UpdateProfile $request): JsonResponse
    {
        $user = Auth::user();
        $user->fill($request->validated());

        if ($user->isClean()) {
            return response()
                ->json([
                    'message' => __('No changes have been made.'),
                ]);
        }

        $user->save();

        Log::info('Used updated profile.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('User profile has been updated.'),
            ]);
    }

    public function updatePassword(UpdatePassword $request): JsonResponse
    {
        $user = Auth::user();
        $user->password = Hash::make($request->password);

        if ($user->isClean()) {
            return response()
                ->json([
                    'message' => __('No changes have been made.'),
                ]);
        }

        $user->save();

        Log::notice('Used changed password.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('Password has been updated.'),
            ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $user = Auth::user();

        if ($user->is_super_admin && User::where('is_super_admin', true)->count() == 1) {
            return response()
                ->json([
                    'message' => __('Account cannot be deleted as it is the only remaining account with super-admin privileges.'),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user->avatar !== null && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }

        $user->delete();

        Log::info('Used deleted account.', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'client_ip' => $request->ip(),
        ]);

        return response()
            ->json([
                'message' => __('Your account has been deleted.'),
            ]);
    }
}
