<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected $harveyDomainProtocol;
    protected $harveyDomain;
    protected $harveySecret;
    protected $timeout;
    protected $harveyPageSize;

    public function __construct()
    {
        $this->harveyDomainProtocol = getenv('HARVEY_DOMAIN_PROTOCOL') !== false ? getenv('HARVEY_DOMAIN_PROTOCOL') : 'http';
        $this->harveyDomain = getenv('HARVEY_DOMAIN');
        $this->harveySecret = getenv('HARVEY_SECRET') !== false ? getenv('HARVEY_SECRET') : '';
        $this->timeout = getenv('HARVEY_TIMEOUT') !== false ? getenv('HARVEY_TIMEOUT') : 10;
        $this->harveyPageSize = getenv('HARVEY_PAGE_SIZE') !== false ? getenv('HARVEY_PAGE_SIZE') : 20;
    }
}
