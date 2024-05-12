<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    /*  Authenticate Admin */
    public function authenticate(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('admin.login')
                ->withInput()
                ->withErrors($validator);
        } else {
            if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {

                //check it is admin or not(check user role)
                if (Auth::guard('admin')->user()->role != "admin") {
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'you are not authorize to access this page!');
                }
                // Authentication successful, redirect to dashboard or desired location
                return redirect()->route('admin.dashboard');
            } else {
                // Authentication failed, redirect back to login with error message
                return redirect()->route('admin.login')->with('error', 'Either email or password is incorrect!');
            }
        }
    }

    //logout

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
