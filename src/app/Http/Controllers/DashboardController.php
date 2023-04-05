<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class DashboardController extends Controller
{
    /**
     * Show the dashboard view, aggregate projects, deployments, and lock data.
     *
     * @param Request $request
     * @return View
     */
    public function dashboard(Request $request): View
    {
        try {
            $projectsResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/projects?page_size=$this->harveyPageSize");
            $projects = $projectsResponse->successful() ? $projectsResponse->json()['projects'] : [];
            $projectsCount = $projectsResponse->successful() ? $projectsResponse->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $projects = [];
            $projectsCount = 0;
        }

        try {
            $deploymentsResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/deployments?page_size=$this->harveyPageSize");
            $deployments = $deploymentsResponse->successful() ? $deploymentsResponse->json()['deployments'] : [];
            $deploymentsCount = $deploymentsResponse->successful() ? $deploymentsResponse->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $deployments = [];
            $deploymentsCount = 0;
        }

        try {
            $locksResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/locks?page_size=$this->harveyPageSize");
            $locks = $locksResponse->successful() ? $locksResponse->json()['locks'] : [];
        } catch (Throwable $error) {
            $locks = [];
        }

        try {
            $threadsResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/threads");
            $threads = $threadsResponse->successful() ? $threadsResponse->json()['threads'] : [];
        } catch (Throwable $error) {
            $threads = [];
        }

        return view('index', compact(
            'projects',
            'deployments',
            'locks',
            'projectsCount',
            'deploymentsCount',
            'threads'
        ));
    }
}
