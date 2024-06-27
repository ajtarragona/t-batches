@extends('tgn-batches::layout')

@section('title')
	Backend 
@endsection



@section('body')


	
	<div 
		class="row g-0 h-100" 
		x-data="batches({
			url: '{{ route('tgn-batches.batches')}}',
		})"
	>
		<div class="col-sm-2   " >
			<div class="h-100 border-end" style="overflow-y: auto">
				<button class="btn btn-light" type="button" @click="refresh()">Refresh</button>
				<input type="text" x-model.debounce="filter.term" />
				<select x-model="filter.queue">
					<option value="" >--</option>
					<template x-for="queue in queues">
						<option :value="queue" x-text="queue"></option>
					</template>
				</select>
			</div>
		</div>
		<div class="col-sm-10 h-100 bg-secondary  bg-opacity-10">
			<div class="h-100 " style="overflow-y: auto">
				<div class="table-responsive" >
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
							<template x-for="batch in batches">
								<tr>
									<td style="width:40px;" x-text="batch.id"></td>
									<td class="text-truncate" style="width:120px;" x-text="batch.name"></td>
									<td class="text-truncate" style="width:120px;"><small class="text-muted" x-text="batch.started_at"></small></td>
									<td class="text-truncate" style="width:120px;"><small class="text-muted" x-text="batch.finished_at"></small></td>

									<td class="text-truncate"><span class="badge bg-dark text-bg-dark"><i class="bi bi-gear-wide"></i> <span x-text="batch.queue"></span></span></td>
									<td class="w-50">
										@include('tgn-batches::_batch-progress-alpine')

										{{-- @tBatchProgress($batch) --}}
									</td>
									<td x-text="batch.user_id"></td>
									<td class="text-nowrap"><small class="text-muted" x-text="batch.created_at"></small></td>
									<td><i class="bi bi-exclamation-circle-fill text-danger" x-show="batch.failed"></i></td>
									<td>
										<button  class="btn btn-light btn-sm" data-bs-toggle="collapse" :data-bs-target="'#detail-batch-'+batch.id" role="button" aria-expanded="false" :aria-controls="'detail-batch-'+batch.id">
											Detail
										</button >
									</td>
								</tr>
								<tr class="collapse" :id="'detail-batch-'+batch.id">
									<td colspan="10 " class="bg-light p-0 ">
										{{-- @tBatchJobDetail($batch) --}}
										
									</td>
								</tr>
			
							</template>
			
						</tbody>
					</table>
				</div>
				
			   
			</div>
		</div>
	</div>	
		
	

@endsection

@section('pre-js')

	{{-- <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.x.x/dist/cdn.min.js"></script>
	<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script> --}}
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection