<?php

namespace App\Http\Controllers;

use App\Semester;
use Carbon\Carbon;

class SemesterController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('semester.create');
    }
}