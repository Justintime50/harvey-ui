@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Profile</h1>

        <form action="/users/{{ $user->id }}/password" method="POST">
            @csrf

            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" required>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" name="password_confirmation" required>

            <input type="submit" class="btn btn-primary mt-2" value="Update Password">
        </form>

    </div>
@endsection
