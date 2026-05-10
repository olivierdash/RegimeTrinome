<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (session()->get('admin_id')) {
            return redirect()->to('/admin');
        }

        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }

        return redirect()->to('/login');
    }
}
