<?php

namespace App\Http\Controllers;

use App\Rehearsal;
use App\Voice;
use Illuminate\Http\Request;

use App\Http\Requests;

class RehearsalController extends Controller {

    public function __construct() {
        $this->middleware('auth');

        $this->middleware('admin:rehearsal');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $voices = [];
        // If user rights are restricted only give her voice back.
        if (\Auth::user()->adminOnlyOwnVoice('rehearsal')) {
            $voices[\Auth::user()->voice()->id] = \Auth::user()->voice()->name;
        } else {
            $voices = Voice::all()->pluck('name', 'id')->toArray();
        }

        return view('date.rehearsal.create', ['voices' => $voices]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $rehearsal = Rehearsal::find($id);

        $voices = [];
        // If user rights are restricted only give her voice back.
        if (\Auth::user()->adminOnlyOwnVoice('rehearsal')) {
            $voices[\Auth::user()->voice()->id] = \Auth::user()->voice()->name;
        } else {
            $voices = Voice::all()->pluck('name', 'id')->toArray();
        }

        if (null === $rehearsal) {
            return back()->withErrors(trans('date.rehearsal_not_found'));
        }

        return view('date.rehearsal.show', ['rehearsal' => $rehearsal, 'voices' => $voices]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
