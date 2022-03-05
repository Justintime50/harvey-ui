<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    protected $harvey_domain;
    protected $harvey_secret;
    protected $timeout;

    public function __construct()
    {
        $this->harvey_domain = getenv('HARVEY_DOMAIN');
        $this->harvey_secret = getenv('HARVEY_SECRET');
        $this->timeout = 10;
    }

    /**
     * Get the list of projects from Harvey.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        $harvey_health_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->get("http://$this->harvey_domain/health");
        $harvey_status = $harvey_health_response->status();

        $projects_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->get("http://$this->harvey_domain/projects");
        $projects = $projects_response->successful() ? $projects_response->json()['projects'] : [];

        $pipelines_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->get("http://$this->harvey_domain/pipelines");
        $pipelines = $pipelines_response->successful() ? $pipelines_response->json()['pipelines'] : [];

        $locks_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->get("http://$this->harvey_domain/locks");
        $locks = $locks_response->successful() ? $locks_response->json()['locks'] : [];

        return view('/harvey', compact('harvey_status', 'projects', 'pipelines', 'locks'));
    }

    /**
     * Gets the log details of a single pipeline.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readPipeline(Request $request)
    {
        $pipeline_id = $request->pipeline;

        $response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->get("http://$this->harvey_domain/pipelines/$pipeline_id");
        $pipeline = $response->successful() ? $response->json() : null;

        return view('harvey-pipeline', compact('pipeline'));
    }

    /**
     * Gets all the pipelines for a project.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function readProject(Request $request)
    {
        $project = $request->project;

        $lock_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->get("http://$this->harvey_domain/locks/$project");
        $locked = $lock_response->successful() ? $lock_response->json()['locked'] : null;

        $project_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->get("http://$this->harvey_domain/pipelines?project=$project");
        $pipelines = $project_response->successful() ? $project_response->json()['pipelines'] : null;

        return view('harvey-project', compact('project', 'locked', 'pipelines'));
    }

    /**
     * Unlocks a project's deployments.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlockProject(Request $request)
    {
        $project = $request->project;

        $locks_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->put("http://$this->harvey_domain/projects/$project/unlock");

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

        $locks_response = Http::withBasicAuth($this->harvey_secret, '')
            ->timeout($this->timeout)
            ->put("http://$this->harvey_domain/projects/$project/lock");

        $flash_type = $locks_response->successful() ? 'message' : 'error';
        $flash_message = $locks_response->successful() ? 'Project locked successfully!' : json_encode($locks_response->json());

        session()->flash($flash_type, $flash_message);
        return redirect()->back();
    }
}
