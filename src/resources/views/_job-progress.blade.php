
<div x-data="jobProgress({
    job_id: {{$job->id}},
    url: '{{ route('tgn-jobs.monitor',['job'=>$job->id])}}',
    progress: {{$job->progress??0}},
    failed: {{$job->failed?'true':'false'}},
    started: {{$job->started_at?'true':'false'}},
    finished: {{$job->finished_at?'true':'false'}},
})">

    <div  class="progress" role="progressbar" aria-label="Job progress" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar " :class="failed?'bg-danger':(progress<100 ? 'progress-bar-striped progress-bar-animated bg-info':'bg-primary')" :title="progress+'%'" :style="'width:'+progress+'%'" x-text="progress?(progress+'%'):''"></div>
    </div>


</div>