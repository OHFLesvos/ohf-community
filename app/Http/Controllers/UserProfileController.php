<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserProfile;

class UserProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
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
        return redirect()->route('home')
                    ->with('success', 'User profile has been deleted!');
    }

}
