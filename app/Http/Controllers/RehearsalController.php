<?php

namespace App\Http\Controllers;

use App\Rehearsal;
use App\Semester;
use App\Voice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RehearsalController extends Controller {
    protected $validation = [
        'title'     => 'required|string|max:100',
        'description' => 'string|max:500',
        'start'     => 'required|date',
        'end'       => 'required|date|after:start',
        'place'     => 'required|string',
        'voice_id'  => 'required|integer|min:0',
        'weight'    => 'required|numeric|min:0|max:1',
        'mandatory' => 'required|boolean',
        'repeat'    => 'boolean',
        'end_repeat'=> 'required_if:repeat,1|after:start',
        'interval'  => 'required_if:repeat,1',
    ];

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
            $voices[\Auth::user()->voice->id] = \Auth::user()->voice->name;
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
    public function store(Request $request) {
        $this->validate($request, $this->validation);

        //TODO: Right calculation.
        $data = array_merge($request->all(),
            [
                'semester_id' => Semester::current()->id,
            ]
        );

        $start = new Carbon($data['start']);
        $end   = new Carbon($data['end']);

        $data['start'] = $start->toDateTimeString();
        $data['end'] = $end->toDateTimeString();

        Rehearsal::create($data);

        if ($data['repeat']) {
            $start = new Carbon($data['start']);
            $end   = new Carbon($data['end']);

            $endRep= (new Carbon($data['end_repeat']))->addDay();

            while ($start->lt($endRep)) {
                if ($data['interval'] == 'weekly') {
                    $start = $start->addWeek();
                    $end = $end->addWeek();
                } else if ($data['interval'] == 'daily') {
                    $start = $start->addDay();
                    $end = $end->addDay();
                } else {
                    $start = $start->addMonth();
                    $end = $end->addMonth();
                }

                if ($end->lt($endRep)) {
                    $data['start'] = $start->toDateTimeString();
                    $data['end'] = $end->toDateTimeString();

                    Rehearsal::create($data);
                } else {
                    break;
                }
            }
        }

        $request->session()->flash('message_success', trans('date.success'));

        return redirect()->route('date.index');
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
            $voices[\Auth::user()->voice->id] = \Auth::user()->voice->name;
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
    public function update(Request $request, $id) {
        $rehearsal = Rehearsal::find($id);

        if (null === $rehearsal) {
            return redirect()->route('date.index')->withErrors([trans('date.not_found')]);
        }
        
        $this->validate($request, $this->validation);

        //TODO: Right calculation.
        $data = array_merge($request->all(),
            [
                'semester_id' => Semester::current()->id,
            ]
        );

        $start = new Carbon($data['start']);
        $end   = new Carbon($data['end']);

        $data['start'] = $start->toDateTimeString();
        $data['end'] = $end->toDateTimeString();

        $rehearsal->update($data);
        $rehearsal->save();

        $request->session()->flash('message_success', trans('date.success'));

        return redirect()->route('date.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     *
     * @throws \Exception
     */
    public function destroy($id) {
        $rehearsal = Rehearsal::find($id);

        if (null === $rehearsal) {
            return redirect()->route('date.index')->withErrors([trans('date.not_found')]);
        }

        $rehearsal->delete();

        \Session::flash('message_success', trans('date.delete_success'));

        return redirect()->route('date.index');
    }
}
