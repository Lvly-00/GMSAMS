<?php

return [
    'session_timeout_minutes' => (int) env('GMSAMS_SESSION_TIMEOUT_MINUTES', 15),
    'max_login_attempts' => (int) env('GMSAMS_MAX_LOGIN_ATTEMPTS', 5),
    'otp_expiry_minutes' => (int) env('GMSAMS_OTP_EXPIRY_MINUTES', 10),
    'otp_max_resends' => (int) env('GMSAMS_OTP_MAX_RESENDS', 3),

    'lockout_durations' => [
        1 => 5,
        2 => 15,
        3 => 30,
    ],

    'password' => [
        'min' => 6,
        'max' => 18,
        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,18}$/',
    ],

    'roles' => [
        'admin' => 'admin',
        'head_teacher' => 'head_teacher',
        'teacher' => 'teacher',
        'student' => 'student',
    ],
];
