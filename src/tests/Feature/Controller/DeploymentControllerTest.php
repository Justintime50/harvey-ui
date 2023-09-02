<?php

namespace Tests\Feature\Controller;

use App\Http\Controllers\DeploymentController;
use Illuminate\Http\Request;
use Tests\TestCase;
use VCRAccessories\CassetteSetup;

class DeploymentControllerTest extends TestCase
{
    /**
     * Setup the testing environment for this file.
     */
    public static function setUpBeforeClass(): void
    {
        CassetteSetup::setupVcrTests();
    }

    /**
     * Cleanup the testing environment once finished.
     */
    public static function tearDownAfterClass(): void
    {
        CassetteSetup::teardownVcrTests();
    }

    /**
     * Tests that we return the deployment page and data corretly.
     *
     * @return void
     */
    public function testShowDeployment()
    {
        CassetteSetup::setupCassette('deployments/show.yml', self::$expireCassetteDays);
        $controller = new DeploymentController();

        // TODO: Mock out deployments instead of using real ones
        $deploymentId = 'justintime50-justinpaulhammond-62c98a8498192f0c886a759d82701ac3d5412fac';
        $request = Request::create("/deployments/$deploymentId", 'GET');
        $response = $controller->showDeployment($request, $deploymentId);

        $viewData = $response->getData();

        $this->assertArrayHasKey('project', $viewData['deployment']);
    }
}
