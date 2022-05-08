@extends('layouts.app')

@section('content')

<div class="container">
    <h1>{{ $project }}</h1>
    @php
    $locked_status = !empty($locked) ? 'true' : 'false'; // TODO: This doesn't account for null or no lock present
    $lock_color = ( $locked_status == 'false' ? 'text-success' : 'text-danger');
    $lock_button_endpoint = $locked_status == 'true' ? '/unlock-project' : '/lock-project';
    @endphp

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
    @if($deployments != [])
    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <th>Commit</th>
                <th>Last Run</th>
                <th>Status</th>
            </thead>
            <tbody>
                @foreach ($deployments as $deployment)
                @php $status_color = ( $deployment['status'] == 'Success' ) ? 'text-success' : (( $deployment['status']
                == 'In-Progress' ) ? 'text-info' : 'text-danger'); @endphp
                <tr>
                    <td><a href="deployment?deployment={{ $deployment['project'] }}-{{ $deployment['commit'] }}">{{
                            $deployment['commit'] }}</a></td>
                    <td>{{ $deployment['timestamp'] }}</td>
                    <td class="{{ $status_color }}">{{ $deployment['status'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>There are no project deployments at this time.</p>
    @endif
</div>

@endsection
