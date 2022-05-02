@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Deployment</h1>
    <a href="/"><button class="btn btn-primary">Back to Dashboard</button></a>
    @php $status_color = ( $deployment['status'] ?? '' == 'Success' ) ? 'text-success' : (( $deployment['status'] ??
    '' == 'In-Progress' ) ? 'text-info' : 'text-danger'); @endphp
    <p><b>Project:</b> {{ $deployment['project'] ?? '' }}</p>
    <p><b>Commit:</b> {{ $deployment['commit'] ?? '' }}</p>
    <p><b>Timestamp:</b> {{ $deployment['timestamp'] ?? '' }}</p>
    <p><b>Status:</b><span class="{{ $status_color }}"> {{ $deployment['status'] ?? '' }}</span></p>
    <p><b>Logs:</b><br />{!! nl2br(e($deployment['log'] ?? '')) !!}</p>
</div>

@endsection
