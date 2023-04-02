@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 mt-3 mb-3">
                <div class="card bg-secondary text-white">
                    <div class="card-header">
                        Projects
                    </div>
                    <div class="card-body">
                        <h5>Total: {{ $projectsCount }}</h5>

                        @if ($projects != [])
                            <div class="table-responsive">
                                <table class="table-dark table-striped table">
                                    <thead>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Locked</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $project)
                                            @php $lock_exists = false; @endphp
                                            <tr>
                                                <td><a href="projects/{{ $project }}">{{ $project }}</a>
                                                </td>
                                                <td>Unknown</td>
                                                {{-- TODO: Fix the response of locks so it has the project name as the key so we don't need to
                    iterate like this --}}
                                                @foreach ($locks as $lock)
                                                    @if ($lock['project'] == $project)
                                                        @php
                                                            $lock_color = $lock['locked'] == false ? 'text-success' : 'text-danger';
                                                            $lock_exists = true;
                                                        @endphp
                                                        <td class="{{ $lock_color }}">{{ var_export($lock['locked']) }}
                                                        </td>
                                                    @endif
                                                @endforeach
                                                @if ($lock_exists !== true)
                                                    <td>Unknown</td>
                                                @endif
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
            </div>

            <div class="col-lg-6 mt-3 mb-3">
                @if ($deployments != [])
                    <div class="card bg-secondary text-white">
                        <div class="card-header">
                            Deployments
                        </div>
                        <div class="card-body">
                            <h5>Total: {{ $deploymentsCount }}</h5>

                            <div class="table-responsive">
                                <table class="table-dark table-striped table">
                                    <thead>
                                        <th>Project</th>
                                        <th>Deployment</th>
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
                                                    <td><a href="projects/{{ $deployment['project'] }}">{{ $deployment['project'] }}
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="deployments/{{ $deployment['project'] }}-{{ $deployment['commit'] }}">{{ $deployment['commit'] }}</a>
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
                        </div>
                    </div>
                @else
                    <p>There are no deployments at this time.</p>
                @endif
            </div>
        </div>
    @endsection
