<?php

namespace App\Http\Controllers;

class AdminDashboardController
{

    public function index()
    {
        return view('welcome');
    }

    public function login()
    {
        return view('auth.login');
    }

}
