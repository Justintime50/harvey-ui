<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show the user's profile page.
     *
     * @param Request $request
     * @return View
     */
    public function showProfile(Request $request, int $id): View
    {
        $user = User::find($id);

        return view('profile', compact('user'));
    }

    /**
     * Change a user's password if authorized.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function changePassword(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::find($id);
        $user->password = Hash::make($request->input('password'));
        $user->save();

        session()->flash('message', 'Password was changed successfully.');
        return back();
    }
}
