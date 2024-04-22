@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="card bg-secondary mt-3 text-white">
                    <div class="card-header">
                        Projects
                    </div>
                    <div class="card-body">
                        <h5>Total: {{ $projectsCount }}</h5>

                        @if (!empty($projects))
                            <div class="table-responsive">
                                <table class="table-dark table-striped table">
                                    <thead>
                                        <th>Locked</th>
                                        <th>Project</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $project)
                                            @php $lockExists = false; @endphp
                                            <tr>
                                                @foreach ($locks as $lock)
                                                    @if ($lock['project'] == $project)
                                                        @php
                                                            $lockIcon = $lock['locked'] == false ? '✅' : '🔒';
                                                            $lockExists = true;
                                                        @endphp
                                                        <td>{{ $lockIcon }}</td>
                                                        @php break; @endphp
                                                    @endif
                                                @endforeach
                                                @if ($lockExists !== true)
                                                    <td>❓</td>
                                                @endif
                                                <td><a href="projects/{{ $project }}">{{ $project }}</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>There are no projects at this time.</p>
                        @endif
                    </div>
                </div>

                <div class="card bg-secondary mt-3 text-white">
                    <div class="card-header">
                        Threads (Ongoing Work)
                    </div>
                    <div class="card-body">
                        @if (!empty($threads))
                            <ul>
                                @foreach ($threads as $thread)
                                    <li>{{ $thread }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>There are no threads at this time.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                @if (!empty($deployments))
                    <div class="card bg-secondary mt-3 text-white">
                        <div class="card-header">
                            Deployments
                        </div>
                        <div class="card-body">
                            <h5>Total: {{ count($deployments) }}/{{ $deploymentsCount }}</h5>

                            <div class="table-responsive">
                                <table class="table-dark table-striped table">
                                    <thead>
                                        <th>Status</th>
                                        <th>Project</th>
                                        <th>Deployment</th>
                                        <th>Attempts</th>
                                        <th>Timestamp</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($deployments as $deployment)
                                            @if (array_key_exists('attempts', $deployment))
                                                @php
                                                    $status =
                                                        $deployment['attempts'][0]['status'] == 'Success'
                                                            ? '✅'
                                                            : ($deployment['attempts'][0]['status'] == 'In-Progress'
                                                                ? '🚀'
                                                                : '❌');
                                                @endphp
                                                <tr>
                                                    <td>{{ $status }}</td>
                                                    <td><a href="projects/{{ $deployment['project'] }}">{{ $deployment['project'] }}
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="deployments/{{ $deployment['project'] }}-{{ $deployment['commit'] }}">{{ $deployment['commit'] }}</a>
                                                    </td>
                                                    <td>{{ count($deployment['attempts']) }}</td>
                                                    <td>{{ $deployment['timestamp'] }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <p>There are no deployments at this time.</p>
                @endif
            </div>
        </div>
    @endsection
