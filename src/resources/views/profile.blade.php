@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Profile</h1>

        <form action="/users/{{ $user->id }}/password" method="POST">
            @csrf

            <div class="mt-2">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mt-2">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>

            <input type="submit" class="btn btn-primary mt-3" value="Update Password">
        </form>

    </div>
@endsection
