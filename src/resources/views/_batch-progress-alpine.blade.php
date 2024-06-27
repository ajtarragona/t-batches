
<div x-data="batchProgress({
    batch_id: batch.id,
    url: '/ajtarragona/batches/monitor/'+batch.id,
    cancel_url: '/ajtarragona/batches/cancel/'+batch.id,
    progress: batch.progress,
    stop_on_fail: batch.stop_on_fail,
    failed: batch.failed,
    started: batch.started_at?true:false,
    finished: batch.finished_at?true:false,
    height: '{{ $height??'25px'}}'
})">

    <div class="position-relative batch-progress">
        <div  class="progress flex-grow-1" :style="'height:'+ height " role="progressbar" aria-label="batch progress" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar " :class="{'bg-danger': failed, 'bg-success':(!failed && finished), 'bg-info':(!failed && !finished) , 'progress-bar-striped progress-bar-animated': (started && !finished) }" :title="progress+'%'" :style="'width:'+progress+'%'" x-text="progress?(progress+'%'):''"></div>
        </div>
        <div class="cancel-btn" x-show="started && !finished">
            <a type="button" @click="cancel()" title="Cancel"><small><i class="bi bi-stop-fill"></i></small></a>
        </div>
    </div>



</div>