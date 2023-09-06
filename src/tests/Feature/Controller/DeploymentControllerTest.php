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

        $deploymentId = self::$testProjectName . '-' . self::$testProjectId;
        $request = Request::create("/deployments/$deploymentId", 'GET');
        $response = $controller->showDeployment($request, $deploymentId);

        $viewData = $response->getData();

        $this->assertArrayHasKey('project', $viewData['deployment']);
    }
}
