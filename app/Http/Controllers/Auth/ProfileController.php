<?php

namespace App\Http\Controllers\Auth;

use Hash;
use Image;
use Auth;
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
                // 'image' => 'required',
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

            // to change image to user 
            if($request->hasFile('image')){
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                Image:: make($image)->resize(300, 300)->save(public_path('uploads/avatars/' . $filename ));
                
                $user = Auth::user();
                $user->image = $filename ;
                $user->save();
            }


            $currentUser->save();
            return redirect()->back()->with('success', 'Profile updated successfully!');

// handle the user upload of image

    }


}
