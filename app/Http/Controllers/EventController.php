<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class EventController extends DateController {

    public function __construct() {
        parent::__construct();
    }

    public function getSemester($date) {
        return (new SemesterController())->getSemester($date);
    }

    protected function prepareDates($data) {
        $start = new Carbon($data['start']);
        $end   = new Carbon($data['end']);

        $semester = $this->getSemester($start);
        $data = array_merge($data,
            [
                'semester_id' => $semester->id,
            ]
        );

        $data['start'] = $start->toDateTimeString();
        $data['end'] = $end->toDateTimeString();

        return $data;
    }
}