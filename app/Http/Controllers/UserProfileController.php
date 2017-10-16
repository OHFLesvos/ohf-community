<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserProfile;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('userprofile', [
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
                             ->with('success', 'User profile has been updated!');
        }
        return redirect()->route('userprofile')
                         ->with('info', 'No changed have been made.');
	}

    public function delete() {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return view('userprofile.deleted');
    }

}
