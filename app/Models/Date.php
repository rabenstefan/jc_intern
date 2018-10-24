<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;

trait Date {
    // This is for carbon dates.
    protected $dates = ['start', 'end'];

    // This is for view stuff (visibility and such).
    protected $applicable_filters = null;

    protected $offset_for_madhatter = false;

    /**
     * Get all filters, that are applicable to this date.
     *
     * @return array
     */
    public function getApplicableFilters() {
        if (null === $this->applicable_filters) {
            $this->setApplicableFilters();
        }
        return $this->applicable_filters;
    }

    /**
     * Set the currently known applicable filters of this date (only the name of the Date).
     */
    protected function setApplicableFilters() {
        $this->applicable_filters[] = $this->getShortName();
        if (property_exists($this, 'calendar_options')) {
            $this->calendar_options['applicableFilters'] = $this->applicable_filters;
        }

    }

    /**
     * MadHatter's FullCalendar needs some special treatment to display correctly.
     * Most importantly, we need to set the end-date one second into the future.
     * This will happen whenever getEnd() is called.
     *
     * Please note that calling this function will spoil the Date object for future modification of the end-attribute.
     * It is not forbidden, just take extra care. Luckily, there is no real use case for that anyway. At least at the moment.
     *
     */
    public function prepareMadhatterCalendarView() {
        // First, make sure the applicable filters are accessible
        $this->getApplicableFilters();

        // Secondly, MadHatter's FullCalendar works a little differently from our method when it comes to storing all-day-events
        if ($this->isAllDay()) {
            $this->offset_for_madhatter = true; // will be checked in getEnd()
        }
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay() {
        return $this->all_day;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart() {
        return $this->start;
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd() {
        if ($this->offset_for_madhatter) {
            // MadHatter's FullCalendar handels all-day events differently than we do. A little hacky, but should be fine.
            return $this->end->copy()->addSecond();
        }
        return $this->end;
    }

    /**
     * Check if this date has a place
     *
     * @return Boolean
     */
    public function hasPlace() {
        // Second part is to make sure we don't have empty strings. PHP sucks.
        return isset($this->place) && !empty($this->place);
    }

    /**
     * Getters for name of the class (needed for views).
     *
     * @return string
     */
    abstract static function getShortName();
    abstract static function getShortNamePlural();

    public function needsAnswer() {
        return false;
    }

    public function hasCommented() {
        return false;
    }

    public static function isMissable() {
        return false;
    }

    public function isWeighted() {
        return false;
    }

    /**
     * No need for old events.
     *
     * @param array $columns
     * @param bool $with_old
     * @param bool $with_attendances
     * @param bool $with_new
     * @param bool $current_only
     * @return \Eloquent[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function all($columns = ['*'], $with_old = false, $with_attendances = false, $with_new = true, $current_only = false) {
        if ($with_old) {
            return parent::all($columns);
        } else {
            return parent::where('end', '>=', Carbon::today())->get($columns);
        }
    }
}
