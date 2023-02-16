@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Deployment</h1>
        <a href="/"><button class="btn btn-primary">Back to Dashboard</button></a>
        <p><b>Project:</b> {{ $deployment['project'] ?? '' }}</p>
        <p><b>Commit:</b> {{ $deployment['commit'] ?? '' }}</p>
        <p><b>Timestamp:</b> {{ $deployment['timestamp'] ?? '' }}</p>

        @if (isset($deployment['attempts']))
            @php
                usort($deployment['attempts'], function ($item1, $item2) {
                    return $item2['timestamp'] <=> $item1['timestamp'];
                });
            @endphp
            @foreach ($deployment['attempts'] as $attempt)
                <hr />
                @php $status_color = $attempt['status'] == 'Success' ? 'text-success' : ($attempt['status'] == 'In-Progress' ? 'text-info' : 'text-danger'); @endphp
                <p><b>Attempt:</b> {{ $attempt['attempt'] ?? '' }}</p>
                <p><b>Timestamp:</b> {{ $attempt['timestamp'] ?? '' }}</p>
                <p><b>Status:</b><span class="{{ $status_color }}"> {{ $attempt['status'] ?? '' }}</span></p>
                <p><b>Logs:</b><br />{!! nl2br(e($attempt['log'] ?? '')) !!}</p>
            @endforeach
        @endif
    </div>
@endsection
