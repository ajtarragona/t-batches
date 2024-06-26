
<div x-data="batchDetail({
    batch_id: {{$batch->id}},
    url: '{{ route('tgn-batches.monitor',['batch'=>$batch->id])}}',
    stop_on_fail: {{$batch->stop_on_fail?'true':'false'}},
    failed: {{$batch->failed?'true':'false'}},
    started: {{$batch->started_at?'true':'false'}},
    finished: {{$batch->finished_at?'true':'false'}},
})">

    
    <table  class="table table-sm table-text-sm  text-sm mb-0">
        <tbody>
            <template x-for="job in jobs">
        
                <tr>
                    <td style="width:40px" class="bg-transparent" x-text="job.id"></td>
                    <td style="width:120px" class="bg-transparent text-truncate" x-text="job.name"></td>
                    <td style="width:120px" class="bg-transparent text-truncate"><small class="text-muted" x-text="job.started_at"></small></td>
                    <td style="width:120px" class="bg-transparent text-truncate"><small class="text-muted" x-text="job.finished_at"></small></td>
                    <td class="bg-transparent" x-text="job.message"></td>
                    <td class="bg-transparent"><i class="bi bi-exclamation-circle-fill text-danger" x-show="job.failed"></i></td>

                </tr>
            </template>
        </tbody>
    </table>
   
</div>