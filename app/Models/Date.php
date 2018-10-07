<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;

trait Date {
    // This is for carbon dates.
    protected $dates = ['start', 'end'];

    // This is for view stuff (visibility and such).
    protected $applicable_filters = null;

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
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay() {
        return $this->getStart()->startOfDay() == $this->getStart() && $this->getEnd()->startOfDay() == $this->getEnd();
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
