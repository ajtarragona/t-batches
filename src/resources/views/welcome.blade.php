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
			<th>Class</th>
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
				<td>{{$job->id}}</td>
				<td>{{$job->name}}</td>
				<td>{{$job->classname}}</td>
				<td>{{$job->queue}}</td>
				<td class="w-50">

					<div class="progress" role="progressbar" aria-label="Job progress" aria-valuenow="{{$job->progress}}" aria-valuemin="0" aria-valuemax="100">
						<div class="progress-bar bg-{{$job->failed?'danger':'primary'}}" title="{{$job->progress}}%" style="width: {{$job->progress}}%"></div>
					</div>
					

				</td>
				<td>{{$job->user_id}}</td>
				<td>{{$job->created_at}}</td>
				<td>{{$job->started_at}}</td>
				<td>{{$job->finished_at}}</td>
				<td>@if($job->failed)<i class="bi bi-exclamation-circle-fill text-danger"></i>@endif</td>
				
			</tr>

		@endforeach

	</tbody>
</table>
</div>
@endsection