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
            $harvey_health_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/health");
            $harvey_status = $harvey_health_response->status();
        } catch (Throwable $error) {
            $harvey_status = 500;
        }

        try {
            $projects_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/projects?page_size=$this->harvey_page_size");
            $projects = $projects_response->successful() ? $projects_response->json()['projects'] : [];
            $projectsCount = $projects_response->successful() ? $projects_response->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $projects = [];
        }

        try {
            $deployments_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/deployments?page_size=$this->harvey_page_size");
            $deployments = $deployments_response->successful() ? $deployments_response->json()['deployments'] : [];
            $deploymentsCount = $deployments_response->successful() ? $deployments_response->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $deployments = [];
        }

        try {
            $locks_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/locks?page_size=$this->harvey_page_size");
            $locks = $locks_response->successful() ? $locks_response->json()['locks'] : [];
        } catch (Throwable $error) {
            $locks = [];
        }

        return view('index', compact('harvey_status', 'projects', 'deployments', 'locks', 'projectsCount', 'deploymentsCount'));
    }

    /**
     * Gets the log details of a single deployment.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readDeployment(Request $request)
    {
        $deployment_id = $request->deployment;

        try {
            $response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/deployments/$deployment_id");
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
            $lock_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/locks/$project");
            $locked = $lock_response->successful() ? $lock_response->json()['locked'] : null;
        } catch (Throwable $error) {
            $locked = null;
        }

        try {
            $project_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->get("$this->harvey_domain_protocol://$this->harvey_domain/deployments?project=$project"); // TODO: Add page_size url param here
            $deployments = $project_response->successful() ? $project_response->json()['deployments'] : null;
            $deploymentsCount = $project_response->successful() ? $project_response->json()['total_count'] : 0;
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
            $locks_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->put("$this->harvey_domain_protocol://$this->harvey_domain/projects/$project/unlock");

            $flash_type = $locks_response->successful() ? 'message' : 'error';
            $flash_message = $locks_response->successful() ? 'Project unlocked successfully!' : json_encode($locks_response->json());
        } catch (Throwable $error) {
            $flash_type = 'error';
            $flash_message = "Sorry, there was a problem unlocking the project: $error";
        }

        $flash_type = $locks_response->successful() ? 'message' : 'error';
        $flash_message = $locks_response->successful() ? 'Project unlocked successfully!' : json_encode($locks_response->json());

        session()->flash($flash_type, $flash_message);
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
            $locks_response = Http::withBasicAuth($this->harvey_secret, '')
                ->timeout($this->timeout)
                ->put("$this->harvey_domain_protocol://$this->harvey_domain/projects/$project/lock");

            $flash_type = $locks_response->successful() ? 'message' : 'error';
            $flash_message = $locks_response->successful() ? 'Project locked successfully!' : json_encode($locks_response->json());
        } catch (Throwable $error) {
            $flash_type = 'error';
            $flash_message = "Sorry, there was a problem locking the project: $error";
        }

        session()->flash($flash_type, $flash_message);
        return redirect()->back();
    }
}
