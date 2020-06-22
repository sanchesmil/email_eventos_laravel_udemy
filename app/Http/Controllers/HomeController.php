<?php

namespace App\Http\Controllers;

use App\Events\HomeEvent;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        event(new HomeEvent("Olá Mundo"));  // quando logar chama o evento 'HomeEvent' passando 'Olá mundo';
        return view('home');
    }
}
