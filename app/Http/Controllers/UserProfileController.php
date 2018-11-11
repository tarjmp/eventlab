<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function read()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'location'      => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->first_name      = $data['first_name'];
        $user->last_name       = $data['last_name'];
        $user->location        = $data['location'];
        $user->date_of_birth   = $data['date_of_birth'];
        $user->email           = $data['email'];
        $user->save();
        return view('profile')->with('updated', true);
    }
}
