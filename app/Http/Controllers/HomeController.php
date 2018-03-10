<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Rehearsal;
use App\Gig;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $next_rehearsal = Rehearsal::getNextRehearsal();
        if (null === $next_rehearsal) {
            $rehearsal = ['diff' => 'nie', 'datetime' => 'keine Probe gefunden', 'location' => 'nirgendwo'];
        } else {
            $rehearsal = ['diff' => $next_rehearsal->start->diffForHumans(), 'datetime' => $next_rehearsal->start->formatLocalized('%c'), 'location' => $next_rehearsal->place];
        }

        $next_gig = Gig::getNextGig();
        if (null === $next_gig) {
            $gig = ['diff' => 'nie', 'datetime' => 'keine Probe gefunden', 'location' => 'nirgendwo'];
        } else {
            $gig = ['diff' => $next_gig->start->diffForHumans(), 'datetime' => $next_gig->start->toDayDateTimeString(), 'location' => $next_gig->place];
        }

        return view('home', ['next_rehearsal' => $rehearsal, 'next_gig' => $gig]);
    }
}
