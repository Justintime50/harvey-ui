@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{ $project }}</h1>
        @php
            if (!empty($locked)) {
                $lockedAlertMessage = 'true';
            } elseif (empty($locked)) {
                $lockedAlertMessage = 'false';
            } else {
                $lockedAlertMessage = 'Unknown';
            }
            $lockedAlertMessage = !empty($locked) ? 'Project is locked!' : 'Project is not locked.';
            $lockedAlertClass = $lockedAlertMessage == 'Project is locked!' ? 'alert alert-danger' : 'alert alert-success';
            $lockButtonEndpoint = $lockedAlertMessage == 'Project is locked!' ? "/projects/$project/unlock" : "/projects/$project/lock";
        @endphp

        <div class="project-buttons">
            <a href="/"><button class="btn btn-primary">Back to Dashboard</button></a>
            <form action="{{ $lockButtonEndpoint }}" method="post">
                @csrf
                <input name="project" value="{{ $project }}" hidden>
                <button class="btn btn-secondary">{{ $locked == false ? 'Lock' : 'Unlock' }} Deployments</button>
            </form>
            <form action="/projects/{{ $project }}/redeploy" method="post"
                onsubmit="return confirm('Confirm redeploy?');">
                @csrf
                <input name="project" value="{{ $project }}" hidden>
                <button class="btn btn-danger">Redeploy</button>
            </form>
        </div>

        <div class="card bg-secondary text-white">
            <div class="card-header">
                Deployments
            </div>
            <div class="card-body">
                <div class="{{ $lockedAlertClass }}">{{ $lockedAlertMessage }}</div>
                <h5>Total: {{ count($deployments) }}/{{ $deploymentsCount }}</h5>
                @if ($deployments != [])
                    <div class="table-responsive">
                        <table class="table-dark table-striped table">
                            <thead>
                                <th>Commit</th>
                                <th>Attempts</th>
                                <th>Timestamp</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach ($deployments as $deployment)
                                    @if (array_key_exists('attempts', $deployment))
                                        @php
                                            usort($deployment['attempts'], function ($item1, $item2) {
                                                // By returning results in a descending order, the most recent attempt will be the 0 index
                                                return $item2['timestamp'] <=> $item1['timestamp']; // Descending
                                            });
                                            $statusColor = $deployment['attempts'][0]['status'] == 'Success' ? 'text-success' : ($deployment['attempts'][0]['status'] == 'In-Progress' ? 'text-info' : 'text-danger');
                                        @endphp
                                        <tr>
                                            <td>
                                                <a
                                                    href="/deployments/{{ $deployment['project'] }}-{{ $deployment['commit'] }}">{{ $deployment['commit'] }}</a>
                                            </td>
                                            <td>{{ count($deployment['attempts']) }}</td>
                                            <td>{{ $deployment['timestamp'] }}</td>
                                            <td class="{{ $statusColor }}">
                                                {{ $deployment['attempts'][0]['status'] }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>There are no project deployments at this time.</p>
                @endif
            </div>
        </div>

        <div class="card bg-secondary mt-3 text-white">
            <div class="card-header">
                Webhook
            </div>
            <div class="card-body">
                <div class="accordion" id="accordion">
                    <div class="accordion-item bg-dark">
                        <h2 class="accordion-header" id="webhookHeading">
                            <button class="accordion-button bg-dark text-white" type="button" data-bs-toggle="collapse"
                                data-bs-target="#webhookContent" aria-expanded="false" aria-controls="webhookContent">
                                Webhook Content
                            </button>
                        </h2>
                    </div>

                    <div id="webhookContent" class="accordion-collapse collapse" aria-labelledby="webhookHeading"
                        data-bs-parent="#accordion">
                        <div class="accordion-body text-white">
                            @if (isset($webhook))
                                <pre>{{ json_encode($webhook, JSON_PRETTY_PRINT) }}</pre>
                            @else
                                NA
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
