<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use Illuminate\Http\Request;

class GigController extends EventController {
    protected $validation = [
        'title'     => 'required|string|max:100',
        'description' => 'string|max:255',
        'start'     => 'required|date',
        'end'       => 'required|date|after:start',
        'place'     => 'required|string',
    ];

    public function __construct() {
        parent::__construct();

        $this->middleware('admin:gig');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('date.gig.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, $this->validation);

        // Just take all the data from the request as Gig data.
        $data = $request->all();
        // Prepare the dates (including adding semesters).
        $data = $this->prepareDates($data);

        // Create new gig with data.
        Gig::create($data);

        $request->session()->flash('message_success', trans('date.success'));

        return redirect()->route('dates.index');
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
        $gig = Gig::find($id);

        if (null === $gig) {
            return back()->withErrors(trans('date.gig_not_found'));
        }

        return view('date.gig.show', ['gig' => $gig]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $gig = Gig::find($id);

        if (null === $gig) {
            return redirect()->route('dates.index')->withErrors([trans('date.not_found')]);
        }

        $this->validate($request, $this->validation);

        $data = $this->prepareDates($request->all());

        $gig->update($data);
        $gig->save();

        $request->session()->flash('message_success', trans('date.success'));

        return redirect()->route('dates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $gig = Gig::find($id);

        if (null === $gig) {
            return redirect()->route('dates.index')->withErrors([trans('date.not_found')]);
        }

        try {
            // Do not have to delete attendances explicitly, because foreign key triggers cascade.
            $gig->delete();
        } catch (\Exception $exception) {
            return redirect()->route('dates.index')->withErrors(
                [
                    trans('date.delete_error', ['message' => $exception->getMessage()])
                ]
            );
        }

        \Session::flash('message_success', trans('date.delete_success'));

        return redirect()->route('dates.index');
    }
}
