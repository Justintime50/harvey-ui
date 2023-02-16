@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{ $project }}</h1>
        <?php
        // TODO: This doesn't account for null or no lock present
        $locked_status = !empty($locked) ? 'true' : 'false';
        $lock_color = $locked_status == 'false' ? 'text-success' : 'text-danger';
        $lock_button_endpoint = $locked_status == 'true' ? '/unlock-project' : '/lock-project';
        ?>

        <p><b>Locked:</b> <span class="{{ $lock_color }}">{{ $locked_status }}</span></p>

        <div class="project-buttons">
            <a href="/"><button class="btn btn-primary">Back to Dashboard</button></a>
            <button class="btn btn-warning" disabled>Redeploy</button>
            <form action="{{ $lock_button_endpoint }}" method="post">
                @csrf
                <input name="project" value="{{ $project }}" hidden>
                <button class="btn btn-secondary">{{ $locked == false ? 'Lock' : 'Unlock' }} Deployments</button>
            </form>
            <button class="btn btn-danger" disabled>Shutdown</button>
        </div>

        <h2 class="text-left">Deployments</h2>
        <p>Total: {{ $deploymentsCount }}</p>
        @if ($deployments != [])
            <div class="table-responsive">
                <table class="table-dark table-striped table">
                    <thead>
                        <th>Commit</th>
                        <th>Attempt</th>
                        <th>Timestamp</th>
                        <th>Status</th>
                    </thead>
                    <tbody>
                        @foreach ($deployments as $deployment)
                            @if (isset($deployment['attempts']))
                                @php
                                    usort($deployment['attempts'], function ($item1, $item2) {
                                        return $item2['timestamp'] <=> $item1['timestamp'];
                                    });
                                @endphp
                                @foreach ($deployment['attempts'] as $attempt)
                                    @php $statusColor = $attempt['status'] == 'Success' ? 'text-success' : ($attempt['status'] == 'In-Progress' ? 'text-info' : 'text-danger'); @endphp
                                    <tr>
                                        <td>
                                            <a
                                                href="deployment?deployment={{ $deployment['project'] }}-{{ $deployment['commit'] }}">
                                                {{ $deployment['project'] }}@<br />{{ $deployment['commit'] }}
                                            </a>
                                        </td>
                                        <td>{{ $attempt['attempt'] ?? 'Unknown' }}</td>
                                        <td>{{ $attempt['timestamp'] }}</td>
                                        <td class="{{ $statusColor }}">{{ $attempt['status'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>There are no project deployments at this time.</p>
        @endif
    </div>

@endsection
