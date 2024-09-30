@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card bg-secondary text-white">
                    <div class="card-header">Verify Your Email Address</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                A fresh verification link has been sent to your email address.
                            </div>
                        @endif

                        Before proceeding, please check your email for a verification link.
                        If you did not receive the email, <a href="{{ route('verification.resend') }}">click here to request
                            another</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
