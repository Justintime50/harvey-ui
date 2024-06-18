<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Throwable;

class DeploymentController extends Controller
{
    /**
     * Show the deployment view.
     *
     * @param Request $request
     * @param string $id
     * @return View
     */
    public function showDeployment(Request $request, string $id): View
    {
        try {
            $response = $this->harveyGetRequest("$this->harveyDomainProtocol://$this->harveyDomain/deployments/$id");
            $deployment = $response->successful() ? $response->json() : null;
        } catch (Throwable $error) {
            $deployment = null;
        }

        try {
            // The $id is the project name appended with a dash and the ID of the deployment, get the project
            // name by removing the ID
            $project = (strrpos($id, '-') !== false) ? substr($id, 0, strrpos($id, '-')) : $id;
            $lockResponse = $this->harveyGetRequest(
                "$this->harveyDomainProtocol://$this->harveyDomain/locks/$project"
            );
            $isDeploying = $lockResponse->successful() ? $lockResponse->json()['system_lock'] : null;
        } catch (Throwable $error) {
            $isDeploying = null;
        }

        return view('deployment', compact('deployment', 'isDeploying'));
    }
}
