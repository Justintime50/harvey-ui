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
        $this->harveyDomainProtocol = config('harvey.domain_protocol');
        $this->harveyDomain = config('harvey.domain');
        $this->harveySecret = config('harvey.secret');
        $this->timeout = config('harvey.timeout');
        $this->harveyPageSize = config('harvey.page_size');
    }
}
