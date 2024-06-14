@extends('tgn-jobs::layout')

@section('title')
	Backend 
@endsection


@section('body')

<div class="table-responsive">
<table class="table ">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			{{-- <th>Class</th> --}}
			<th>queue</th>
			<th></th>
			<th>User</th>
			<th>Created</th>
			<th>Started</th>
			<th>Finished</th>
			<th>Failed</th>
		</tr>
	</thead>
	<tbody>
		@foreach($jobs as $job)
			<tr>
				<td >{{$job->id}}</td>
				<td class="text-nowrap">{{$job->name}}</td>
				{{-- <td>{{$job->classname}}</td> --}}
				<td class="text-nowrap"><span class="badge bg-dark text-bg-dark"><i class="bi bi-gear-wide"></i> {{$job->queue}}</span></td>
				<td class="w-50">
					@tJobProgress($job)
				</td>
				<td>{{$job->user_id}}</td>
				<td class="text-nowrap"><small class="text-muted">{{$job->created_at}}</small></td>
				<td class="text-nowrap"><small class="text-muted">{{$job->started_at}}</small></td>
				<td class="text-nowrap"><small class="text-muted">{{$job->finished_at}}</small></td>
				<td>@if($job->failed)<i class="bi bi-exclamation-circle-fill text-danger"></i>@endif</td>
				
			</tr>

		@endforeach

	</tbody>
</table>
</div>

@endsection

@section('pre-js')

	{{-- <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.x.x/dist/cdn.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script> --}}
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection