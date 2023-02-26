<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class DashboardController extends Controller
{
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

    /**
     * Get the list of projects from Harvey.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        try {
            $harveyHealthResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/health");
            $harveyStatus = $harveyHealthResponse->status();
        } catch (Throwable $error) {
            $harveyStatus = 500;
        }

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

        return view('index', compact('harveyStatus', 'projects', 'deployments', 'locks', 'projectsCount', 'deploymentsCount'));
    }

    /**
     * Gets the log details of a single deployment.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readDeployment()
    {
        $deploymentId = request()->get('deployment');

        try {
            $response = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/deployments/$deploymentId");
            $deployment = $response->successful() ? $response->json() : null;
        } catch (Throwable $error) {
            $deployment = null;
        }

        return view('deployment', compact('deployment'));
    }

    /**
     * Gets all the deployments for a project.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readProject()
    {
        $project = request()->get('project');

        try {
            $lockResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/locks/$project");
            $locked = $lockResponse->successful() ? $lockResponse->json()['locked'] : null;
        } catch (Throwable $error) {
            $locked = null;
        }

        try {
            $projectResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/deployments?project=$project"); // TODO: Add page_size url param here
            $deployments = $projectResponse->successful() ? $projectResponse->json()['deployments'] : null;
            $deploymentsCount = $projectResponse->successful() ? $projectResponse->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $deployments = null;
        }

        try {
            $webhookResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/webhook");
            $webhook = $webhookResponse->successful() ? $webhookResponse->json() : null;
        } catch (Throwable $error) {
            $webhook = null;
        }

        return view('project', compact('project', 'locked', 'deployments', 'deploymentsCount', 'webhook'));
    }

    /**
     * Unlocks a project's deployments.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlockProject()
    {
        $project = request()->get('project');

        try {
            $locksResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->put("$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/unlock");

            $flashType = $locksResponse->successful() ? 'message' : 'error';
            $flashMessage = $locksResponse->successful() ? 'Project unlocked successfully!' : json_encode($locksResponse->json());
        } catch (Throwable $error) {
            $flashType = 'error';
            $flashMessage = "Sorry, there was a problem unlocking the project: $error";
        }

        $flashType = $locksResponse->successful() ? 'message' : 'error';
        $flashMessage = $locksResponse->successful() ? 'Project unlocked successfully!' : json_encode($locksResponse->json());

        session()->flash($flashType, $flashMessage);
        return redirect()->back();
    }

    /**
     * Locks a project's deployments.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lockProject()
    {
        $project = request()->get('project');

        try {
            $locksResponse = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->put("$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/lock");

            $flashType = $locksResponse->successful() ? 'message' : 'error';
            $flashMessage = $locksResponse->successful() ? 'Project locked successfully!' : json_encode($locksResponse->json());
        } catch (Throwable $error) {
            $flashType = 'error';
            $flashMessage = "Sorry, there was a problem locking the project: $error";
        }

        session()->flash($flashType, $flashMessage);
        return redirect()->back();
    }
}
