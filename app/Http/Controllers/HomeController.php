<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showLandingPage()
    {
        return view('landing');
    }

    public function showDashboardPage()
    {
        $user = Sentinel::getUser();
        return view('dashboard', compact('user'));
    }

    public function showAboutPage()
    {
        return view('about');
    }
}
