<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            $lockResponse = $this->harveyGetRequest(
                "$this->harveyDomainProtocol://$this->harveyDomain/locks/$project"
            );
            $isLocked = $lockResponse->successful() ? $lockResponse->json()['locked'] : null;
            $isDeploying = $lockResponse->successful() ? $lockResponse->json()['system_lock'] : null;
        } catch (Throwable $error) {
            $isLocked = null;
            $isDeploying = null;
        }

        try {
            $projectResponse = $this->harveyGetRequest(
                "$this->harveyDomainProtocol://$this->harveyDomain/deployments?project=$project&page_size=$this->harveyPageSize" // phpcs:ignore
            );
            $deployments = $projectResponse->successful() ? $projectResponse->json()['deployments'] : [];
            $deploymentsCount = $projectResponse->successful() ? $projectResponse->json()['total_count'] : 0;
        } catch (Throwable $error) {
            $deployments = [];
            $deploymentsCount = 0;
        }

        try {
            $webhookResponse = $this->harveyGetRequest(
                "$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/webhook"
            );
            $webhook = $webhookResponse->successful() ? $webhookResponse->json() : null;
        } catch (Throwable $error) {
            $webhook = null;
        }

        return view('project', compact(
            'project',
            'isLocked',
            'isDeploying',
            'deployments',
            'deploymentsCount',
            'webhook',
        ));
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
            $response = $this->harveyPutRequest(
                "$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/unlock"
            );
            $flashType = $response->successful() ? 'message' : 'error';
            $flashMessage = $response->successful()
                ? 'Project unlocked successfully!'
                : json_encode($response->json());
        } catch (Throwable $error) {
            $flashType = 'error';
            $flashMessage = "Sorry, there was a problem unlocking the project: $error";
        }

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
            $response = $this->harveyPutRequest(
                "$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/lock"
            );
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
            $response = $this->harveyPostRequest(
                "$this->harveyDomainProtocol://$this->harveyDomain/projects/$project/redeploy"
            );
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
