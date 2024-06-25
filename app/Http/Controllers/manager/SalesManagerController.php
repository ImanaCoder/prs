<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesManagerController extends Controller
{
    public function index(){
        return view('manager.dashboard');
    }
}
