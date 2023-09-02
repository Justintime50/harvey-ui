<?php

namespace Tests\Feature\Controller;

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Tests\TestCase;
use VCRAccessories\CassetteSetup;

class ProjectControllerTest extends TestCase
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
     * Tests that we return the project page and data corretly.
     *
     * @return void
     */
    public function testShowProject()
    {
        CassetteSetup::setupCassette('projects/show.yml', self::$expireCassetteDays);
        $controller = new ProjectController();

        // TODO: Mock out projects instead of using real ones
        $projectId = 'justintime50-justinpaulhammond';
        $request = Request::create("/projects/$projectId", 'GET');
        $response = $controller->showProject($request, $projectId);

        $viewData = $response->getData();

        $this->assertNotNull($viewData['project']);
        $this->assertIsBool($viewData['locked']);
        $this->assertGreaterThanOrEqual(1, $viewData['deployments']);
        $this->assertGreaterThanOrEqual(1, $viewData['deploymentsCount']);
        $this->assertNotNull($viewData['webhook']);
    }

    /**
     * Tests that we lock and unlock a project correctly.
     *
     * @return void
     */
    public function testlockUnlock()
    {
        CassetteSetup::setupCassette('projects/lockUnlock.yml', self::$expireCassetteDays);
        $controller = new ProjectController();

        // TODO: Mock out projects instead of using real ones
        $projectId = 'justintime50-justinpaulhammond';

        $request = Request::create("/projects/$projectId/lock", 'PUT');
        $response = $controller->lockProject($request, $projectId);

        $this->assertEquals('Project locked successfully!', $response->getSession()->get('message'));
        $this->assertEquals(302, $response->getStatusCode());

        $request = Request::create("/projects/$projectId/unlock", 'PUT');
        $response = $controller->unlockProject($request, $projectId);

        $this->assertEquals('Project unlocked successfully!', $response->getSession()->get('message'));
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * Tests that we redeploy a project correctly.
     *
     * @return void
     */
    public function testRedeploy()
    {
        CassetteSetup::setupCassette('projects/redeploy.yml', self::$expireCassetteDays);
        $controller = new ProjectController();

        // TODO: Mock out projects instead of using real ones
        $projectId = 'justintime50-justinpaulhammond';
        $request = Request::create("/projects/$projectId/redeploy", 'POST');
        $response = $controller->redeployProject($request, $projectId);

        $this->assertEquals('Project redeploy started!', $response->getSession()->get('message'));
        $this->assertEquals(302, $response->getStatusCode());
    }
}
