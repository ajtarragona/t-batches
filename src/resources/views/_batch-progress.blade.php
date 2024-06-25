
<div x-data="batchProgress({
    batch_id: {{$batch->id}},
    url: '{{ route('tgn-batches.monitor',['batch'=>$batch->id])}}',
    progress: {{$batch->progress??0}},
    failed: {{$batch->failed?'true':'false'}},
    started: {{$batch->started_at?'true':'false'}},
    finished: {{$batch->finished_at?'true':'false'}},
})">

    <div  class="progress" role="progressbar" aria-label="batch progress" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar " :class="failed?'bg-danger':(progress<100 ? 'progress-bar-striped progress-bar-animated bg-info':'bg-primary')" :title="progress+'%'" :style="'width:'+progress+'%'" x-text="progress?(progress+'%'):''"></div>
    </div>


</div>