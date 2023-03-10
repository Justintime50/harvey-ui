@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{ $project }}</h1>
        @php
            if (!empty($locked)) {
                $lockedStatus = 'true';
            } elseif (empty($locked)) {
                $lockedStatus = 'false';
            } else {
                $lockedStatus = 'Unknown';
            }
            $lockedStatus = !empty($locked) ? 'true' : 'false';
            $lockColor = $lockedStatus == 'false' ? 'text-success bg-dark' : 'text-danger bg-dark';
            $lockButtonEndpoint = $lockedStatus == 'true' ? '/unlock-project' : '/lock-project';
        @endphp

        <div class="project-buttons">
            <a href="/"><button class="btn btn-primary">Back to Dashboard</button></a>
            <button class="btn btn-warning" disabled>Redeploy</button>
            <form action="{{ $lockButtonEndpoint }}" method="post">
                @csrf
                <input name="project" value="{{ $project }}" hidden>
                <button class="btn btn-secondary">{{ $locked == false ? 'Lock' : 'Unlock' }} Deployments</button>
            </form>
            <button class="btn btn-danger" disabled>Shutdown</button>
        </div>

        <div class="card bg-secondary text-white">
            <div class="card-header">
                Deployments
            </div>
            <div class="card-body">
                <p>Locked: <span class="{{ $lockColor }}">{{ $lockedStatus }}</span></p>
                <p>Total: {{ $deploymentsCount }}</p>
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
                                                    href="deployment?deployment={{ $deployment['project'] }}-{{ $deployment['commit'] }}">{{ $deployment['commit'] }}</a>
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
                                @json($webhook)
                            @else
                                NA
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
