<?php

return [
    'domain_protocol' => env('HARVEY_DOMAIN_PROTOCOL', 'http'),
    'domain' => env('HARVEY_DOMAIN'),
    'secret' => env('HARVEY_SECRET', ''),
    'timeout' => env('HARVEY_TIMEOUT', 10),
    'page_size' => env('HARVEY_PAGE_SIZE', 20),
];
