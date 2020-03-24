<?php

namespace App\Http\Controllers\Auth;

use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'password.confirm']);
    }

    // edit to show form 
    public function edit(Request $request){
        $user = $request->user();
        return view('auth.profile', compact('user'));
    }

    // update and save to database

    public function update(Request $request)
    {
       $currentUser = $request->user();
// to validation 
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $currentUser->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $currentUser->id,
                'password' => 'required_with:password_confirmation|confirmed',
                'current_password' => ['required_with:password', function ($attribute,$value,$fail) use($request) {
                        $currentUser = $request->user();
                        if(!empty($request->password) && !Hash::check($value, $currentUser->password)){
                            return $fail("Current password not match");
                        }
                }],

            ]);

            $currentUser->name = $request->name;
            $currentUser->email = $request->email;
            $currentUser->username = $request->username;

                if (!empty($request->password)) {
                    $currentUser->password = Hash::make($request->password);
                }
            $currentUser->save();
            return redirect()->back()->with('success', 'Profile updated successfully!');

    }


}
