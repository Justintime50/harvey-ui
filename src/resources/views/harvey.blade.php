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
                <tr>
                    <td><a href="harvey-project?project={{ $project }}">{{ $project }}</a></td>
                    <td>Unknown</td>
                    <td>See Project</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>There are no projects at this time.</p>
    @endif

    <p>The following are all of the most recent deployments done via Harvey.</p>

    @if($pipelines != [])
    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <th>Deployment</th>
                <th>Last Run</th>
                <th>Status</th>
            </thead>
            <tbody>
                @foreach ($pipelines as $pipeline)
                @php $status_color = ( $pipeline['status'] == 'Success' ) ? 'text-success' : (( $pipeline['status']
                == 'In-Progress' ) ? 'text-info' : 'text-danger'); @endphp
                <tr>
                    <td><a href="harvey-pipeline?pipeline={{ $pipeline['project'] }}-{{ $pipeline['commit'] }}">{{
                            $pipeline['project'] }}@<br />{{ $pipeline['commit'] }}</a></td>
                    <td>{{ $pipeline['timestamp'] }}</td>
                    <td class="{{ $status_color }}">{{ $pipeline['status'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>There are no pipelines at this time.</p>
    @endif
</div>

@endsection
