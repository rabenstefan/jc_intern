<?php

namespace App\Http\Controllers;

use App\Semester;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        // Actually we do not need a "show single user".
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return view('user.profile', ['user' => User::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'first_name'=> 'required|alpha|max:255',
            'last_name' => 'required|alpha|max:255',
            'email'     => 'required|email',
            'voice_id'  => 'required|integer|min:0',
            'birthday'  => 'date|after:1900-01-01|before:' . date('Y-m-d'),
            'address_zip'   => 'integer',
            'sheets_deposit_returned' => 'boolean'
        ]);

        $user->update($request->all());
        $user->save();

        $request->session()->flash('message_success', trans('user.success'));

        return redirect()->route('user.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
