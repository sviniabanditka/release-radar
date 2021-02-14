<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AdminController extends Controller
{
    public function getIndex()
    {
        return view('admin.index');
    }

    public function getUsers()
    {
        return view('admin.users');
    }
}
