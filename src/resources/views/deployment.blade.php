@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card bg-secondary text-white">
            <div class="card-header">
                Deployment
            </div>
            <div class="card-body">
                <div class="project-buttons">
                    <a href="/projects/{{ $deployment['project'] }}"><button class="btn btn-primary">Back to
                            Project</button></a>
                    <form action="/projects/{{ $deployment['project'] }}/redeploy" method="post"
                        onsubmit="return confirm('Confirm redeploy?');">
                        @csrf
                        <input name="project" value="{{ $deployment['project'] }}" hidden>
                        <button class="btn btn-danger">Redeploy</button>
                    </form>
                </div>
                <ul>
                    <li>Project: {{ $deployment['project'] }}</li>
                    <li>Commit: {{ $deployment['commit'] ?? '' }}</li>
                    <li>Attempts: {{ count($deployment['attempts']) }}</li>
                    <li>Timestamp: {{ $deployment['timestamp'] ?? '' }}</li>
                </ul>

                @if (array_key_exists('attempts', $deployment))
                    <div class="accordion" id="accordion">
                        @foreach ($deployment['attempts'] as $attempt)
                            @php $statusColor = $attempt['status'] == 'Success' ? 'text-success' : ($attempt['status'] == 'In-Progress' ? 'text-info' : 'text-danger'); @endphp

                            <div class="accordion-item bg-dark">
                                <h2 class="accordion-header" id="attemptHeading-{{ $attempt['attempt'] }}">
                                    <button class="accordion-button bg-dark text-white" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#attemptContent-{{ $attempt['attempt'] ?? '' }}"
                                        aria-expanded="false"
                                        aria-controls="attemptContent-{{ $attempt['attempt'] ?? '' }}">
                                        <span class="col">
                                            Attempt:</b> {{ $attempt['attempt'] ?? '' }}</span>
                                        <span class="col">
                                            Timestamp: {{ $attempt['timestamp'] ?? '' }}
                                        </span>
                                        <span class="col">
                                            Runtime: {{ $attempt['runtime'] ?? '' }}
                                        </span>
                                        <span class="col {{ $statusColor }}">
                                            {{ $attempt['status'] ?? '' }}
                                        </span>
                                    </button>
                                </h2>

                                <div id="attemptContent-{{ $attempt['attempt'] ?? '' }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="attemptHeading-{{ $attempt['attempt'] }}" data-bs-parent="#accordion">
                                    <div class="accordion-body text-white">
                                        <p>{!! nl2br(e($attempt['log'] ?? '')) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endsection
