<?php

return [
    'api_url' => env('LICENSE_API_URL', 'http://192.168.12.127:8000/api/superadmin'),
    'cache_key' => 'license_data',
    'cache_duration' => 60 * 60 * 24, // 1 day
];