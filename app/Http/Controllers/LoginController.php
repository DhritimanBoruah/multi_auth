<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    // Call login page for customer
    public function index()
    {
        return view('login');
    }

    /*  Authenticate user */
    public function authenticate(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('account.login')
                ->withInput()
                ->withErrors($validator);
        } else {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                // Authentication successful, redirect to dashboard or desired location
                return redirect()->route('account.dashboard');
            } else {
                // Authentication failed, redirect back to login with error message
                return redirect()->route('account.login')->with('error', 'Either email or password is incorrect!');
            }
        }
    }

    //this method show register page
    public function register()
    {
        return view('register');
    }

    public function processRegister(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required',

        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('account.register')
                ->withInput()
                ->withErrors($validator);
        } else {

            //put to db users

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'customer';
            $user->save();

            return redirect()->route('account.login')->with('success', 'You have registered Successfully.');
        }
    }

    //Logout

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login');
    }
}
