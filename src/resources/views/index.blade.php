@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Harvey Dashboard</h1>
    @php $harvey_status_icon = $harvey_status == 200 ? '<i class="fas fa-check text-success"></i>' : '<i
        class="fas fa-bomb text-danger"></i>' @endphp
    <p><b>Harvey Status:</b> {!! $harvey_status_icon !!}</p>

    <p>The following are all of the projects currently deployed via Harvey.</p>

    @if ($projects != [])
    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <th>Project</th>
                <th>Status</th>
                <th>Locked</th>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                @php $lock_exists = false; @endphp
                <tr>
                    <td><a href="project?project={{ $project }}">{{ $project }}</a></td>
                    <td>Unknown</td>
                    {{-- TODO: Fix the response of locks so it has the project name as the key so we don't need to
                    iterate like this --}}
                    @foreach ($locks as $lock)
                    @if ($lock['project'] == $project)
                    @php
                    $lock_color = ( $lock['locked'] == false ? 'text-success' : 'text-danger');
                    $lock_exists = true;
                    @endphp
                    <td class="{{ $lock_color }}">{{ var_export($lock['locked']) }}</td>
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

    <p>The following are all of the most recent deployments done via Harvey.</p>

    @if($deployments != [])
    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <th>Deployment</th>
                <th>Last Run</th>
                <th>Status</th>
            </thead>
            <tbody>
                @foreach ($deployments as $deployment)
                @php $status_color = ( $deployment['status'] == 'Success' ) ? 'text-success' : (( $deployment['status']
                == 'In-Progress' ) ? 'text-info' : 'text-danger'); @endphp
                <tr>
                    <td><a href="deployment?deployment={{ $deployment['project'] }}-{{ $deployment['commit'] }}">{{
                            $deployment['project'] }}@<br />{{ $deployment['commit'] }}</a></td>
                    <td>{{ $deployment['timestamp'] }}</td>
                    <td class="{{ $status_color }}">{{ $deployment['status'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>There are no deployments at this time.</p>
    @endif
</div>

@endsection
