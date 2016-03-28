<?php

namespace App\Http\Controllers;

use App\Semester;
use App\Voice;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth');

        $this->middleware('adminOrOwn', ['except' => ['index']]);
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
        $voice = null;

        if (Input::has('voice')) {
            $voice = Input::get('voice');

            $voiceModel = Voice::find($voice);
            while (null !== $voiceModel && !$voiceModel->child_group) {
                $voiceModel = $voiceModel->children()->first();
                $voice = $voiceModel->id;
            }
        }

        return view('user.create')->with('voice', $voice);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'first_name'=> 'required|alpha|max:255',
            'last_name' => 'required|alpha|max:255',
            'email'     => 'required|email',
            'password'  => 'size:8',
            'voice_id'  => 'required|integer|min:0',
            'birthday'  => 'date|after:1900-01-01|before:' . date('Y-m-d'),
            'address_zip'   => 'integer',
            'sheets_deposit_returned' => 'boolean'
        ]);

        $data = array_merge($request->all(),
            [
                'last_echo' => Semester::current()->id,
            ]
        );

        // If we do not have a password we just set the last name.
        if (!$request->has('password') || strlen($request->input('password')) < 6) {
            $data['password'] = bcrypt($request->input('last_name'));
        }

        $user = User::create($data);

        $request->session()->flash('message_success', trans('user.success'));

        return redirect()->route('user.show', ['id' => $user->id]);
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
        $user = User::find($id);

        if (null !== $user) {
            return view('user.profile', ['user' => $user]);
        } else {
            return redirect()->route('user.index')->withErrors([trans('user.not_found')]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = User::find($id);

        if (null === $user) {
            return redirect()->route('user.index')->withErrors([trans('user.not_found')]);
        }

        $this->validate($request, [
            'first_name'=> 'required|alpha|max:255',
            'last_name' => 'required|alpha|max:255',
            'email'     => 'required|email',
            'password'  => 'size:8',
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
        $user = User::find($id);

        if (null === $user) {
            return redirect()->route('user.index')->withErrors([trans('user.not_found')]);
        }

        $user->delete();

        \Session::flash('message_success', trans('user.delete_success'));

        return redirect()->route('user.index');
    }
}
