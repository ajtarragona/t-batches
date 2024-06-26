@extends('tgn-batches::layout')

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
			<th>Started</th>
			<th>Finished</th>
			{{-- <th>Class</th> --}}
			<th>queue</th>
			<th></th>
			<th>User</th>
			<th>Created</th>
			<th>Failed</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		@foreach($batches as $batch)
			<tr>
				<td style="width:40px;">{{$batch->id}}</td>
				<td class="text-truncate" style="width:120px;">{{$batch->name}}</td>
				<td class="text-truncate" style="width:120px;"><small class="text-muted">{{$batch->started_at}}</small></td>
				<td class="text-truncate" style="width:120px;"><small class="text-muted">{{$batch->finished_at}}</small></td>
				{{-- <td>{{$batch->classname}}</td> --}}
				<td class="text-truncate"><span class="badge bg-dark text-bg-dark"><i class="bi bi-gear-wide"></i> {{$batch->queue}}</span></td>
				<td class="w-50">
					@tBatchProgress($batch)
				</td>
				<td>{{$batch->user_id}}</td>
				<td class="text-nowrap"><small class="text-muted">{{$batch->created_at}}</small></td>
				<td>@if($batch->failed)<i class="bi bi-exclamation-circle-fill text-danger"></i>@endif</td>
				<td>
					<button  class="btn btn-light btn-sm" data-bs-toggle="collapse" data-bs-target="#detail-batch-{{$batch->id}}" role="button" aria-expanded="false" aria-controls="detail-batch-{{$batch->id}}">
						Detail
					</button >
				</td>
			</tr>
			<tr class="collapse" id="detail-batch-{{$batch->id}}">
				<td colspan="10 " class="bg-light p-0 ">
					@tBatchJobDetail($batch)
					
				</td>
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