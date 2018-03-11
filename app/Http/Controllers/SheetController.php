<?php

namespace App\Http\Controllers;

use App\Sheet;
use App\User;
use Illuminate\Support\Facades\Input;

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
        $users  = User::all(['id', 'first_name', 'last_name']);
        return view('sheet.index', ['sheets' => $sheets, 'users' => $users]);
    }

    public function create(){

        return view('sheet.create');
    }

    public function show($id){
        $sheet = Sheet::findOrFail($id);
        return view('sheet.show', ['sheet' => $sheet]);
    }

    public function ajaxUpdate($id){
        if (!Input::has('borrowed')){
            return response()->json(['error' => 'wrong_input', 'message' => trans('form.error_arbitrary')], 500);
        }

        $names = explode(" ", Input::get('borrowed'));

        if (count($names)!=2){
            return response()->json(['error' => 'user_not_found', 'message' => trans('form.error_user_not_found', ['name' => Input::get('borrowed')])], 500);
        }

        $firstname = $names[0];
        $lastname  = $names[1];

        $user  = User::whereFirstName($firstname)->whereLastName($lastname)->first();

        if (!$user){
            return response()->json(['error' => 'user_not_found', 'message' => trans('form.error_user_not_found', ['name' => Input::get('borrowed')])], 500);
        }

        $sheet = Sheet::find($id);
        if (!$sheet){
            return response()->json(['error' => 'sheet_not_found', 'message' => trans('form.error_sheet_not_found', ['id' => $id])], 500);
        }

        $next  = $sheet->getNextFreeNumber();

        $sheet->users()->save($user, ['number' => $next, 'status' => Sheet::STATUS_BORROWED]);

        return response()->json(['message' => trans('sheet.created_with_number', ['name' => $firstname, 'number' => $next]), 'number' => $next, 'sheetId' => $sheet->id]);
    }
}
