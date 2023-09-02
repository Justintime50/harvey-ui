<?php

namespace Tests\Feature\Controller;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests that we return the project page and data corretly.
     *
     * @return void
     */
    public function testShowUser()
    {
        $controller = new UserController();
        $userId = 1;

        $request = Request::create("/users/$userId", 'GET');
        $response = $controller->showProfile($request, $userId);

        $viewData = $response->getData();

        $this->assertEquals('Admin', $viewData['user']['name']);
    }

    /**
     * Tests that we can change a password, save it to the database, and that the hashes match afterwards.
     *
     * This test requires the user changing the password to be authed and an admin.
     *
     * @return void
     */
    public function testChangePassword()
    {
        $controller = new UserController();
        $authedUser = User::find(1);
        $this->actingAs($authedUser);

        $password = 'password1';

        $request = Request::create("/user/$authedUser->id/password", 'POST', [
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response = $controller->changePassword($request, $authedUser->id);

        $user = User::find($authedUser->id);

        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertEquals('Password was changed successfully.', $response->getSession()->get('message'));
        $this->assertEquals(302, $response->getStatusCode());
    }
}
