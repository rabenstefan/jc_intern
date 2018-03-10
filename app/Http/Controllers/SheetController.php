<?php

namespace App\Http\Controllers;

use App\Sheet;
use Illuminate\Http\Request;

use App\Http\Requests;

class SheetController extends Controller
{
    protected $validation = [
    ];

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('admin:gig');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $sheets = Sheet::all();
        return view('sheet.index', ['sheets' => $sheets]);
    }
}
