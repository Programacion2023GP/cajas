<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashController extends Controller
{
    public function index(){
        //hace consulta para saber cual fue el ultimo turno insertado

        return view('dashboard')->with('turno');
    }
}
