<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

use App\Http\Requests;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return redirect()->route('role.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'label' => 'required|alpha_num',
            'can_plan_rehearsal' => 'required|boolean',
            'can_plan_gig' => 'required|boolean',
            'can_send_mail' => 'required|boolean',
            'can_configure_system' => 'required|boolean',
            'only_own_voice' => 'required|boolean',
        ]);

        $role = Role::create($request->all());

        $request->session()->flash('message_success', trans('role.success', ['label' => $role->label]));

        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect()->route('role.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        return redirect()->route('role.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $role = Role::find($id);

        if (null === $role) {
            return redirect()->route('role.index')->withErrors([trans('role.not_found')]);
        }

        $this->validate($request, [
            'label' => 'required|alpha_num',
            'can_plan_rehearsal' => 'required|boolean',
            'can_plan_gig' => 'required|boolean',
            'can_send_mail' => 'required|boolean',
            'can_configure_system' => 'required|boolean',
            'only_own_voice' => 'required|boolean',
        ]);

        $role->update($request->all());
        $role->save();

        $request->session()->flash('message_success', trans('role.success', ['label' => $role->label]));

        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $role = Role::find($id);

        if (null === $role) {
            return redirect()->route('role.index')->withErrors([trans('role.not_found')]);
        }

        $role->delete();

        \Session::flash('message_success', trans('role.delete_success'));

        return redirect()->route('role.index');
    }
}
