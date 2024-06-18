@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <h1>{{ $project }}</h1>
        @php
            if (!empty($isLocked)) {
                $lockedAlertMessage = 'true';
            } elseif (empty($isLocked)) {
                $lockedAlertMessage = 'false';
            } else {
                $lockedAlertMessage = 'Unknown';
            }
            $lockedAlertMessage = !empty($isLocked) ? 'Project is locked!' : 'Project is not locked.';
            $lockedAlertClass =
                $lockedAlertMessage == 'Project is locked!' ? 'alert alert-danger' : 'alert alert-success';
            $lockButtonEndpoint =
                $lockedAlertMessage == 'Project is locked!' ? "/projects/$project/unlock" : "/projects/$project/lock";
        @endphp

        <div class="project-buttons">
            <a href="/"><button class="btn btn-primary">Back to Dashboard</button></a>
            <form action="{{ $lockButtonEndpoint }}" method="post">
                @csrf
                <input name="project" value="{{ $project }}" hidden>
                <button class="btn btn-secondary">{{ $isLocked == false ? 'Lock' : 'Unlock' }} Deployments</button>
            </form>
            <form action="/projects/{{ $project }}/redeploy" method="post"
                onsubmit="return confirm('Confirm redeploy?');">
                @csrf
                <input name="project" value="{{ $project }}" hidden>
                <button class="btn btn-danger" @if ($isDeploying) disabled @endif>Redeploy</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card bg-secondary text-white">
                    <div class="card-header">
                        Deployments
                    </div>
                    <div class="card-body">
                        @if ($isDeploying)
                            <div class="alert alert-warning" id="deploy-box">
                                <span class="me-1">Project is Deploying!</span>
                                <div class="spinner-grow" role="status">
                                    <span class="visually-hidden">Deploying...</span>
                                </div>
                            </div>
                        @endif
                        <div class="{{ $lockedAlertClass }}">{{ $lockedAlertMessage }}</div>
                        <h5>Total: {{ count($deployments) }}/{{ $deploymentsCount }}</h5>
                        @if ($deployments != [])
                            <div class="table-responsive">
                                <table class="table-dark table-striped table">
                                    <thead>
                                        <th>Status</th>
                                        <th>Commit</th>
                                        <th>Attempts</th>
                                        <th>Timestamp</th>
                                        <th>Runtime</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($deployments as $deployment)
                                            @if (array_key_exists('attempts', $deployment))
                                                @php
                                                    // Base status here off the 0 index which is the most recent
                                                    $status =
                                                        $deployment['attempts'][0]['status'] == 'Success'
                                                            ? '✅'
                                                            : ($deployment['attempts'][0]['status'] == 'In-Progress'
                                                                ? '🚀'
                                                                : '❌');
                                                @endphp
                                                <tr>
                                                    <td>{{ $status }}</td>
                                                    <td>
                                                        <a href="/deployments/{{ $deployment['project'] }}-{{ $deployment['commit'] }}"
                                                            class="badge bg-primary">{{ substr($deployment['commit'], 0, 10) }}</a>
                                                    </td>
                                                    <td>{{ count($deployment['attempts']) }}</td>
                                                    <td>{{ $deployment['timestamp'] }}</td>
                                                    <td>{{ $deployment['attempts'][0]['runtime'] ?? '' }}</td>
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
            </div>
            <div class="col-md-6">
                <canvas id="deploy-runtime-chart"></canvas>

                <div class="card bg-secondary mt-3 text-white">
                    <div class="card-header">
                        Webhook
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="accordion">
                            <div class="accordion-item bg-dark">
                                <h2 class="accordion-header" id="webhookHeading">
                                    <button class="accordion-button bg-dark text-white" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#webhookContent" aria-expanded="false"
                                        aria-controls="webhookContent">
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
            </div>
        </div>

        @php
            $deployRuntimes = [];
            $deployTimestamps = [];
            usort($deployments, function ($item1, $item2) {
                return $item1['timestamp'] <=> $item2['timestamp'];
            });
            foreach ($deployments as $deployment) {
                if (isset($deployment['attempts'])) {
                    foreach ($deployment['attempts'] as $attempt) {
                        if (isset($attempt['runtime'])) {
                            $parse_date = date_parse($attempt['runtime']);
                            $deployRuntimes[] =
                                $parse_date['hour'] * 3600 + $parse_date['minute'] * 60 + $parse_date['second'];
                            $deployTimestamps[] = $deployment['timestamp'];
                        }
                    }
                }
            }
        @endphp

        <script type="module">
            // TODO: This is used as a poor AJAX, replace with real ajax calls
            function reloadPage() {
                if ({!! json_encode($isDeploying) !!}) {
                    setTimeout(function() {
                        location.reload();
                    }, 5000);
                }
            }
            window.onload = reloadPage;

            const ctx = document.getElementById('deploy-runtime-chart');
            const chartData = {!! json_encode($deployRuntimes) !!};
            const timestamps = {!! json_encode($deployTimestamps) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [{
                        label: 'Project Deployment Runtimes',
                        data: chartData,
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Deployments Over Time',
                            },
                            ticks: {
                                maxRotation: 75,
                                minRotation: 75
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Runtime in Seconds',
                            },
                            beginAtZero: true
                        }
                    },
                }
            });
        </script>
    @endsection
