<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChickenManagementController extends Controller
{
    public function index()
    {
        return view('chicken-management');
    }
}
