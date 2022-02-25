@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Deployment</h1>
    <a href="/"><button class="btn btn-primary">Back to Dashboard</button></a>
    @php $status_color = ( $pipeline['status'] == 'Success' ) ? 'text-success' : (( $pipeline['status'] ==
    'In-Progress' ) ? 'text-info' : 'text-danger'); @endphp
    <p><b>Project:</b> {{ $pipeline['project'] }}</p>
    <p><b>Commit:</b> {{ $pipeline['commit'] }}</p>
    <p><b>Timestamp:</b> {{ $pipeline['timestamp'] }}</p>
    <p><b>Status:</b><span class="{{ $status_color }}"> {{ $pipeline['status'] }}</span></p>
    <p><b>Logs:</b><br />{!! nl2br(e($pipeline['log'])) ?? '' !!}</p>
</div>

@endsection
