<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\SelfReport;

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
     * @return Renderable
     */
    public function index()
    {
        $organization_summary = SelfReport::summaryData();

        return view('home', compact('organization_summary'));
    }
}
