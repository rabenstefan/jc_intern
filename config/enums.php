<?php

return [
    'attendances' => [
        'no' => 0,
        'maybe' => 1,
        'yes' => 2
    ],
    'attendances_reversed' => [
        0 => 'no',
        1 => 'maybe',
        2 => 'yes'
    ],
    'attendances_binary' => [
        'no' => 0,
        'yes' => 2
    ],
    'attendances_binary_reversed' => [
        0 => 'no',
        2 => 'yes'
    ],
    'allowed_missed_rehearsals' => [
        'total' => 5,
        'excused' => 5,
        'unexcused' => 2
    ],
    'birthday_consideration_days' => [
        'past' => 3,
        'future' => 10
    ],

    // Birthdays will be set to the next recurrence in the future, unless they have taken within the last few days.
    // This number determines how much is "the last few days".
    // Example: If a birthday occured yesterday, it would make much more sense to display it yesterday and not in 364 days.
    'birthdays_in_past' => 7,
];
