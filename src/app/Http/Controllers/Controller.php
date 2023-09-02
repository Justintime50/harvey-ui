<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

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

    public function harveyGetRequest(string $url)
    {
        return Http::withBasicAuth($this->harveySecret, '')
            ->timeout($this->timeout)
            ->get($url);
    }

    public function harveyPostRequest(string $url)
    {
        return Http::withBasicAuth($this->harveySecret, '')
            ->timeout($this->timeout)
            ->post($url);
    }

    public function harveyPutRequest(string $url)
    {
        return Http::withBasicAuth($this->harveySecret, '')
            ->timeout($this->timeout)
            ->put($url);
    }
}
