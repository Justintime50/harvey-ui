<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    /**
     * Get the list of projects from Harvey.
     *
     * @return void
     */
    public function dashboard()
    {
        $harvey_domain = getenv('HARVEY_DOMAIN');

        $harvey_health_response = Http::timeout(5)->get("http://$harvey_domain/health");
        $harvey_status = $harvey_health_response->status();

        $projects_response = Http::timeout(5)->get("http://$harvey_domain/projects");
        $projects = $projects_response->successful() ? $projects_response->json()['projects'] : [];

        $pipelines_response = Http::timeout(5)->get("http://$harvey_domain/pipelines");
        $pipelines = $pipelines_response->successful() ? $pipelines_response->json()['pipelines'] : [];

        return view('/harvey', compact('harvey_status', 'projects', 'pipelines'));
    }

    /**
     * Gets the log details of a single pipeline.
     *
     * @return void
     */
    public function readPipeline(Request $request)
    {
        $harvey_domain = getenv('HARVEY_DOMAIN');

        $pipeline_id = $request->pipeline;

        $response = Http::timeout(5)->get("http://$harvey_domain/pipelines/$pipeline_id");
        $pipeline = $response->successful() ? $response->json() : null;

        return view('harvey-pipeline', compact('pipeline'));
    }

    /**
     * Gets all the pipelines for a project.
     *
     * @return void
     */
    public function readProject(Request $request)
    {
        $harvey_domain = getenv('HARVEY_DOMAIN');

        $project = $request->project;

        $lock_response = Http::timeout(5)->get("http://$harvey_domain/locks/$project");
        $locked = $lock_response->successful() ? $lock_response->json()['locked'] : null;

        $project_response = Http::timeout(5)->get("http://$harvey_domain/pipelines?project=$project");
        $pipelines = $project_response->successful() ? $project_response->json()['pipelines'] : null;

        return view('harvey-project', compact('project', 'locked', 'pipelines'));
    }
}
