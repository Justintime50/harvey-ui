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
                                                return $item2['timestamp'] <=> $item1['timestamp'];
                                            });
                                            $statusColor = end($deployment['attempts'])['status'] == 'Success' ? 'text-success' : (end($deployment['attempts'])['status'] == 'In-Progress' ? 'text-info' : 'text-danger');
                                        @endphp
                                        <tr>
                                            <td>
                                                <a
                                                    href="deployment?deployment={{ $deployment['project'] }}-{{ $deployment['commit'] }}">{{ $deployment['commit'] }}</a>
                                            </td>
                                            <td>{{ count($deployment['attempts']) }}</td>
                                            <td>{{ $deployment['timestamp'] }}</td>
                                            <td class="{{ $statusColor }}">{{ end($deployment['attempts'])['status'] }}
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
    @endsection
