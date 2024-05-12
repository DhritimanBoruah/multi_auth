<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    //this will show dashboard for customer
    public function dashboard()
    {
        return view('dashboard');
    }
}
