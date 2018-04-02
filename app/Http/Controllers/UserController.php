<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Voice;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Input;

class UserController extends Controller {
    protected $validation = [
        'first_name'=> 'required|alpha|max:255',
        'last_name' => 'required|alpha|max:255',
        'email'     => 'required|email',
        'voice_id'  => 'required|integer|min:0',
        'birthday'  => 'date|after:1900-01-01',
        'address_zip'   => 'integer',
        'sheets_deposit_returned' => 'boolean'
    ];

    protected $password_validation = [
        'password'  => 'required|size:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
    ];

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(
            'adminOrOwn', [
                'except' => ['index', 'create', 'store'
                ]
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('user.index', [
            'musical_leader' => User::getMusicalLeader(),
            'voices' => Voice::getParentVoices(),
            'old_users' => User::orderBy('voice_id')->where('last_echo', '<>', Semester::current()->id)->get(),
        ]);
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
        $this->validate(
            $request,
            array_merge($this->validation, $this->password_validation)
        );

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

        return redirect()->route('users.show', ['id' => $user->id]);
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
            return redirect()->route('users.index')->withErrors([trans('user.not_found')]);
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
            return redirect()->route('users.index')->withErrors([trans('user.not_found')]);
        }

        $this->validate($request, $this->validation);

        // Get rid of empty fields (they should not update).
        $data = array_filter($request->all());

        $user->update($data);
        $user->save();

        $request->session()->flash('message_success', trans('user.success'));

        return redirect()->route('users.show', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id) {
        $user = User::find($id);

        if (null === $user) {
            return redirect()->route('users.index')->withErrors([trans('user.not_found')]);
        }

        $user->delete();

        \Session::flash('message_success', trans('user.delete_success'));

        return redirect()->route('users.index');
    }
}
