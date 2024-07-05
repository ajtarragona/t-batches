@extends('tgn-batches::layout')

@section('title')
	Backend 
@endsection



@section('body')


	
	<div 
		class="row g-0 h-100" 
		x-data="batches({
			
		})"
	>
		<div class="col-sm-2   " >
			<div class="h-100 border-end d-flex flex-column" style="overflow-y: auto">
				<div class="p-2 d-flex justify-content-between">
					
					<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
							Launch
						</button>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="#" @click.prevent="launchTest('example-batch')">Example</a></li>
							<li><a class="dropdown-item" href="#" @click.prevent="launchTest('example-batch-with-errors')">Example (continue on fail)</a></li>
							<li><a class="dropdown-item" href="#" @click.prevent="launchTest('example-batch-stop')">Example (stops on fail)</a></li>
							<li><a class="dropdown-item" href="#" @click.prevent="launchTest('example-single-thread-batch')">Example single thread</a></li>
							<li><a class="dropdown-item" href="#" @click.prevent="launchTest('example-file-batch')">File</a></li>
						</ul>
					</div>
					<button class="btn btn-light" type="button" @click="refresh()"><i class="bi bi-arrow-clockwise"></i></button>

				</div>
				<div class="p-2 d-flex flex-column">
					<input class="form-control mb-2" type="search" placeholder="Search..." x-model.debounce="filter.term" />

					<div class="row mb-2 gx-0">
						<div class="col-sm-6">
							<input type="date" class="form-control " placeholder="Des de..." x-model.debounce="filter.from_date" />

						</div>
						<div class="col-sm-6">
							<input type="date" class="form-control " placeholder="Fins a..." x-model.debounce="filter.until_date" />

						</div>
					</div>
					
					<select class="form-select mb-2" x-model="filter.queue" placeholder="Queue" >
						<option value="" >Choose queue...</option>
						<template x-for="queue in queues">
							<option :value="queue" x-text="queue"></option>
						</template>
					</select>

					<select class="form-select" x-model="filter.status" placeholder="Queue" >
						<option value="" >status...</option>
						
						<option value="pending">Pending</option>
						<option value="running">Running</option>
						<option value="finished">Finished</option>
						<option value="failed">Failed</option>
						
					</select>


					<div class="mt-3">
						{{-- Last batch
						@if($test_batch??null)
							@tBatchProgress($test_batch->id,['id'=>'last-batch-progress','hideOnFinish'=>true])
						@endif --}}
						
						{{-- <div  x-data="batchProgress({
								batch_id: {{$test_batch->id}},
								
								
							})"
							@batchadded.window="setBatch($event.detail.batch.id)"
						>
							<template x-if="batch">
								<div class="position-relative batch-progress flex-grow-1" >
									<div  class="progress flex-grow-1" :style="'height:'+ height " role="progressbar" aria-label="batch progress" :aria-valuenow="batch.progress" aria-valuemin="0" aria-valuemax="100">
										<div class="progress-bar " :class="{'bg-danger': batch.failed && batch.finished_at, 'bg-warning': batch.failed && !batch.finished_at, 'bg-success':(!batch.failed && batch.finished_at), 'bg-info':(!batch.failed && !batch.finished_at) , 'progress-bar-striped progress-bar-animated': (batch.started_at && !batch.finished_at) }" :title="batch.progress+'%'" :style="'width:'+batch.progress+'%'" x-text="batch.progress?(batch.progress+'%'):''"></div>
									</div>
									<div class="cancel-btn" x-show="batch.started_at && !batch.finished_at">
										<a type="button" @click="cancel()" title="Cancel"><small><i class="bi bi-stop-fill"></i></small></a>
									</div>
								</div>
							</template>
						</div> --}}
					
					</div>
						
					
						
					

					

				</div>
				<div class="p-2 mt-auto mb-0">
					<button  class="btn btn-light text-danger 	" role="button" @click="removeAll()">
						<i class="bi bi-trash"></i> Delete finished batches
					</button >
				</div>
			</div>
		</div>
		<div class="col-sm-10 h-100 bg-secondary  bg-opacity-10">
			<div class="h-100 position-relative" style="overflow-y: auto" class="p-3 text-muted">
				

				<div x-show="loading || firstloading" x-transition.opacity class="p-2 text-muted position-absolute start-50 top-0 translate-middle-x bg-white rounded m-3 shadow-sm " style="z-index:10">
					<div class="d-flex align-items-center">
						<div class="spinner-border spinner-border-sm text-primary" role="status">
							<span class="visually-hidden">Loading...</span>
						</div>
						<div class="ms-2">Loading...</div>
					</div>
					
				</div>
				
			
				<div class="p-3 text-muted"  x-cloak x-show="!firstloading && batches.length==0">
					<span x-text="'No batch jobs'"></span>
				</div>
			

				<div  x-show="!firstloading &&  batches.length>0" x-cloak >
					<div class="table-responsive" >
						<table class="table ">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Created</th>
									<th>Started</th>
									<th>Finished</th>
									{{-- <th>Class</th> --}}
									<th>Queue</th>
									<th>#Jobs</th>
									{{-- <th>User</th> --}}
									<th>&nbsp;</th>
									<th>&nbsp;</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							
								<template x-for="batch in batches" :key="batch.id">
									<tbody x-data="batchProgress({
										batch: batch,
										height: '{{ $height??'31px'}}'
									})">
										<tr >
											<td style="width:40px;" x-text="batch.id"></td>
											<td class="text-truncate" style="width:120px;" x-text="batch.name"></td>
											<td class="text-truncate" style="width:120px;"><small class="text-muted" x-text="batch.created_at"></small></td>
											<td class="text-truncate" style="width:120px;"><small class="text-muted" x-text="batch.started_at"></small></td>
											<td class="text-truncate" style="width:120px;"><small class="text-muted" x-text="batch.finished_at"></small></td>

											<td class="text-truncate"><span class="badge bg-dark text-bg-dark"><i class="bi bi-gear-wide"></i> <span x-text="batch.queue"></span></span></td>
											<td ><span x-text="batch.total"></span></td>
											{{-- <td x-text="batch.user_id"></td> --}}
											<td>
												<i class="bi bi-exclamation-circle-fill text-danger" x-cloak x-show="batch.finished_at && batch.failed  "></i>
												<i class="bi bi-exclamation-circle-fill text-warning" x-cloak x-show="!batch.finished_at && batch.failed  "></i>
												<i class="bi bi-check-circle-fill text-success" x-cloak  x-show="batch.finished_at && !batch.failed"></i>
											</td>
											<td class="w-50">
												<div class="d-flex"> 
													
													<div class="position-relative batch-progress flex-grow-1">
														<div  class="progress flex-grow-1" :style="'height:'+ height " role="progressbar" aria-label="batch progress" :aria-valuenow="batch.progress" aria-valuemin="0" aria-valuemax="100">
															<div class="progress-bar " :class="{'bg-danger': batch.failed && batch.finished_at, 'bg-warning': batch.failed && !batch.finished_at, 'bg-success':(!batch.failed && batch.finished_at), 'bg-info':(!batch.failed && !batch.finished_at) , 'progress-bar-striped progress-bar-animated': (batch.started_at && !batch.finished_at) }" :title="batch.progress+'%'" :style="'width:'+batch.progress+'%'" x-text="batch.progress?(batch.progress+'%'):''"></div>
														</div>
														<div class="cancel-btn" x-show="batch.started_at && !batch.finished_at">
															<a type="button" @click="cancel()" title="Cancel"><small><i class="bi bi-stop-fill"></i></small></a>
														</div>
													</div>

													<div class="details-btn flex-1 ps-2 "  x-show="batch.started_at">
														<a type="button"  @click="toggleDetails()" title="Show detail" :class="showDetail?'text-body-tertiary':'text-secondary'"><i class="bi" :class="showDetail?'bi-chevron-up':'bi-chevron-down' "></i></a>
													</div>
												</div>
										
												
											</td>
											<td>
												<button  class="btn btn-light text-danger btn-sm" :disabled="batch.started_at && !batch.finished_at"  role="button" @click="remove(batch.id)">
													<i class="bi bi-trash"></i>
												</button >
											</td>
										</tr>
										<tr x-show="showDetail" >
											<td colspan="10" class=" p-0">
												<div class="table-responsive"  style="max-height:400px" x-ref="batchDetail">
													<table  class="table table-sm table-striped  table-text-sm   mb-0">
														<thead>
															<tr>
																<th style="width:40px">&nbsp;</th>
																<th>Step</th>
																<th>Id</th>
																<th>Name</th>
																<th>Start</th>
																<th>Finish</th>
																<th class="w-50">Message</th>
																<th width="100">Trace</th>
															</tr>
														</thead>
														<tbody>
															<template x-for="(job, index) in jobs">
																
																<tr :class="{'table-danger':job.failed, 'table-success':job.finished_at && !job.failed}">
																	<td >
																		<div class="spinner-grow spinner-grow-sm text-secondary" role="status" x-cloak x-show="job.started_at && !job.finished_at  ">
																			<span class="visually-hidden">Loading...</span>
																		</div>
																		<i class="bi bi-exclamation-circle-fill text-danger" x-cloak x-show="job.finished_at && job.failed  "></i>
																		<i class="bi bi-check-circle-fill text-success" x-cloak  x-show="job.finished_at && !job.failed"></i>
																		

																	</td>
																	<td style="width:50px" class=""><span  x-text="index+1"></span>/<span  x-text="batch.total"></span></td>
																	<td style="width:40px" class="" x-text="job.id"></td>
																	<td style="width:120px" class=" text-truncate" x-text="job.name"></td>
																	<td style="width:120px" class=" text-truncate"><small class="text-muted" x-text="job.started_at"></small></td>
																	<td style="width:120px" class=" text-truncate"><small class="text-muted" x-text="job.finished_at"></small></td>
																	<td class="" x-text="job.message"></td>
																	<td class=" p-0 align-middle">
																		<div class="dropstart "  x-show="job.trace">

																			<button class="btn btn-danger btn-sm dropdown-toggle py-0"  type="button" data-bs-toggle="dropdown" aria-expanded="false">
																				Show Exception
																			</button>
																			<ul class="dropdown-menu p-0 shadow-sm overflow-hidden" >
																				<code><pre class="mb-0" style="max-width:500px;max-height:300px" x-show="job.trace" x-text="JSON.stringify(job.trace,null, 2)"></pre></code>
																			</ul>

																				
																		</div>
																	</td>
												
																</tr>
															</template>
														</tbody>
													</table>
												</div>
											</td>
										</tr>
									</tbody>
								</template>
				
							</tbody>
						</table>
					</div>
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