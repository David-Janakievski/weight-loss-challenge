<?php

return [
    // Update start_date to the actual day the challenge begins.
    'start_date' => env('CHALLENGE_START_DATE', now()->toDateString()),
    'end_date' => env('CHALLENGE_END_DATE', '2026-08-01'),
];
