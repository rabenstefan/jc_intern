<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use MaddHatter\LaravelFullcalendar\Event;

class Birthday implements Event {
    use Date;

    protected $calendar_options = [
        'className' => 'event-birthday'
    ];

    protected $title;
    protected $start;
    protected $end;

    public $description = '';

    protected $user;

    public function __construct(User $user = null) {
        $this->title = trans('form.birthday') . " " . $user->first_name . ' ' . $user->last_name;
        $this->user = $user;

        // Date arithmetic: Set to current year, add one year if date is more than one week ago.
        $dateCurrentYear = new Carbon($user->birthday);
        $dateCurrentYear->year = Carbon::now()->year;
        if ($dateCurrentYear->lt(Carbon::now()->subDays(config('enums.birthdays_in_past')))) {
            $dateCurrentYear->addYear();
        } else {
            if ($dateCurrentYear->gte(Carbon::now()->addYear()->subDays(config('enums.birthdays_in_past')))) {
                // Date is too far in the future. This only happens if this function is called in the days after New Years.
                $dateCurrentYear->subYear();
            }
        }

        $this->start = $dateCurrentYear;
        $this->end   = $dateCurrentYear->copy()->endOfDay();

        $this->setApplicableFilters();
    }

    public static function getShortName() {
        return 'birthday';
    }

    public static function getShortNamePlural() {
        return 'birthdays';
    }

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    public function getUser() {
        return $this->user;
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay() {
        return true;
    }

    /**
     * Check if this date has a place
     *
     * @return Boolean
     */
    public function hasPlace() {
        return false;
    }

    /**
     * Optional FullCalendar.io settings for this event
     *
     * @return array
     */
    public function getEventOptions() {
        return $this->calendar_options;
    }

    /**
     * Returns all Birthdays (unsorted, can use ->sortBy(function($item) {return $item->getStart();}))
     *
     * @return Collection
     */
    public static function all() {
        $collection = new Collection();
        $users = User::all();

        foreach ($users as $user) {
            if (!empty($user->birthday)) {
                $collection->add(new Birthday($user));
            }
        }

        return $collection;
    }
}
