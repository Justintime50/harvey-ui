<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class DashboardController extends Controller
{
    protected $harvey_domain_protocol;
    protected $harvey_domain;
    protected $harvey_secret;
    protected $timeout;

    public function __construct()
    {
        $this->harvey_domain_protocol = getenv('HARVEY_DOMAIN_PROTOCOL') !== false ? getenv('HARVEY_DOMAIN_PROTOCOL') : 'http';
        $this->harvey_domain = getenv('HARVEY_DOMAIN');
        $this->harvey_secret = getenv('HARVEY_SECRET') !== false ? getenv('HARVEY_SECRET') : '';
        $this->timeout = getenv('HARVEY_TIMEOUT') !== false ? getenv('HARVEY_TIMEOUT') : 10;
        $this->harvey_page_size = getenv('HARVEY_PAGE_SIZE') !== false ? getenv('HARVEY_PAGE_SIZE') : 20;
    }

    /**
     * Get the list of projects from Harvey.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        try {
            $harveyHealthResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/health");
            $harveyStatus = $harveyHealthResponse->status();
        } catch (Throwable $error) {
            $harveyStatus = 500;
        }

        try {
            $projectsResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/projects?page_size=$this->harvey_page_size");
            $projects = $projectsResponse->successful() ? $projectsResponse->json()['projects'] : [];
            $projectsCount = $projectsResponse->successful() ? $projectsResponse->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $projects = [];
        }

        try {
            $deploymentsResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/deployments?page_size=$this->harvey_page_size");
            $deployments = $deploymentsResponse->successful() ? $deploymentsResponse->json()['deployments'] : [];
            $deploymentsCount = $deploymentsResponse->successful() ? $deploymentsResponse->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $deployments = [];
        }

        try {
            $locksResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/locks?page_size=$this->harvey_page_size");
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
    public function readDeployment(Request $request)
    {
        $deploymentId = $request->deployment;

        try {
            $response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/deployments/$deploymentId");
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
    public function readProject(Request $request)
    {
        $project = $request->project;

        try {
            $lockResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/locks/$project");
            $locked = $lockResponse->successful() ? $lockResponse->json()['locked'] : null;
        } catch (Throwable $error) {
            $locked = null;
        }

        try {
            $projectResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/deployments?project=$project"); // TODO: Add page_size url param here
            $deployments = $projectResponse->successful() ? $projectResponse->json()['deployments'] : null;
            $deploymentsCount = $projectResponse->successful() ? $projectResponse->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $deployments = null;
        }

        return view('project', compact('project', 'locked', 'deployments', 'deploymentsCount'));
    }

    /**
     * Unlocks a project's deployments.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlockProject(Request $request)
    {
        $project = $request->project;

        try {
            $locksResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->put("$this->harvey_domain_protocol://$this->harvey_domain/projects/$project/unlock");

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
    public function lockProject(Request $request)
    {
        $project = $request->project;

        try {
            $locksResponse = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->put("$this->harvey_domain_protocol://$this->harvey_domain/projects/$project/lock");

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
