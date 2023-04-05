<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class ProjectController extends Controller
{
    /**
     * Shows the project view.
     *
     * @param Request $request
     * @param string $project
     * @return View
     */
    public function showProject(Request $request, string $project): View
    {
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
                ->get("$this->harveyDomainProtocol://$this->harveyDomain/deployments?project=$project&page_size=$this->harveyPageSize");
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
     * @param Request $request
     * @param string $project
     * @return RedirectResponse
     */
    public function unlockProject(Request $request, string $project): RedirectResponse
    {
        try {
            $response = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->put("$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/unlock");

            $flashType = $response->successful() ? 'message' : 'error';
            $flashMessage = $response->successful() ? 'Project unlocked successfully!' : json_encode($response->json());
        } catch (Throwable $error) {
            $flashType = 'error';
            $flashMessage = "Sorry, there was a problem unlocking the project: $error";
        }

        $flashType = $response->successful() ? 'message' : 'error';
        $flashMessage = $response->successful() ? 'Project unlocked successfully!' : json_encode($response->json());

        session()->flash($flashType, $flashMessage);
        return redirect()->back();
    }

    /**
     * Locks a project's deployments.
     *
     * @param Request $request
     * @param string $project
     * @return RedirectResponse
     */
    public function lockProject(Request $request, string $project): RedirectResponse
    {
        try {
            $response = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->put("$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/lock");

            $flashType = $response->successful() ? 'message' : 'error';
            $flashMessage = $response->successful() ? 'Project locked successfully!' : json_encode($response->json());
        } catch (Throwable $error) {
            $flashType = 'error';
            $flashMessage = "Sorry, there was a problem locking the project: $error";
        }

        session()->flash($flashType, $flashMessage);
        return redirect()->back();
    }

    /**
     * Redeploys a project.
     *
     * @param Request $request
     * @param string $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redeployProject(Request $request, string $project): RedirectResponse
    {
        try {
            $response = Http::withBasicAuth($this->harveySecret, '')
                ->timeout($this->timeout)
                ->post("$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/redeploy");

            $flashType = $response->successful() ? 'message' : 'error';
            $flashMessage = $response->successful() ? 'Project redeploy started!' : json_encode($response->json());
        } catch (Throwable $error) {
            $flashType = 'error';
            $flashMessage = "Sorry, there was a problem redeploying the project: $error";
        }

        session()->flash($flashType, $flashMessage);
        return redirect()->back();
    }
}
