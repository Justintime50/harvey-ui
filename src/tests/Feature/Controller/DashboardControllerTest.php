<?php

namespace Tests\Feature\Controller;

use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Tests\TestCase;
use VCRAccessories\CassetteSetup;

class DashboardControllerTest extends TestCase
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
     * Tests that we return the dashboard page and data corretly.
     *
     * @return void
     */
    public function testShowDashboard()
    {
        CassetteSetup::setupCassette('dashboard/show.yml', self::$expireCassetteDays);
        $controller = new DashboardController();

        $request = Request::create("/dashboard", 'GET');
        $response = $controller->showDashboard($request);

        $viewData = $response->getData();

        $this->assertGreaterThanOrEqual(1, $viewData['projects']);
        $this->assertGreaterThanOrEqual(1, $viewData['deployments']);
        $this->assertGreaterThanOrEqual(1, $viewData['locks']);
        $this->assertGreaterThanOrEqual(1, $viewData['projectsCount']);
        $this->assertGreaterThanOrEqual(1, $viewData['deploymentsCount']);
        $this->assertGreaterThanOrEqual(1, $viewData['threads']);
    }
}
