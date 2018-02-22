<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserProfile;
use App\Http\Requests\StoreNewUserPassword;
use Illuminate\Support\Facades\Hash;

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
        return view('userprofile.deleted');
    }

}
