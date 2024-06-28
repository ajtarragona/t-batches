{{-- {{json_encode($batch)}} --}}

<div x-data="batchProgress({
    batch: {{json_alpine($batch)}},
})">

    <div class="position-relative batch-progress flex-grow-1">
        <div  class="progress flex-grow-1" :style="'height:'+ height " role="progressbar" aria-label="batch progress" :aria-valuenow="batch.progress" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar " :class="{'bg-danger': batch.failed && batch.finished_at, 'bg-warning': batch.failed && !batch.finished_at, 'bg-success':(!batch.failed && batch.finished_at), 'bg-info':(!batch.failed && !batch.finished_at) , 'progress-bar-striped progress-bar-animated': (batch.started_at && !batch.finished_at) }" :title="batch.progress+'%'" :style="'width:'+batch.progress+'%'" x-text="batch.progress?(batch.progress+'%'):''"></div>
        </div>
        <div class="cancel-btn" x-show="batch.started_at && !batch.finished_at">
            <a type="button" @click="cancel()" title="Cancel"><small><i class="bi bi-stop-fill"></i></small></a>
        </div>
    </div>



</div>