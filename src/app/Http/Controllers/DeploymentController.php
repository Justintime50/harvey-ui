<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        return view('deployment', compact('deployment'));
    }
}
