<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PwaController extends Controller
{
    /**
     * Show the PWA main page
     */
    public function index()
    {
        return view('pwa.index');
    }
}
