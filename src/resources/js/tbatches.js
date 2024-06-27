
import './utils';

document.addEventListener('alpine:init', () => {
    
    Alpine.data('batchDetail', (config) => ({
        batch_id: config.batch_id,
        interval: 2000,
        url: config.url,
        progress: config.progress,
        stop_on_fail: config.stop_on_fail??false,
        failed: config.failed,
        started: config.started,
        finished: config.finished,
        jobs:[],
        async fetchJobs() {
            var o = this;
            console.log("Fetching jobs...");
                        
            fetch(o.url)
                .then(response => response.json())
                .then((data) => {
                    // _d(data,o);
                    o.jobs=data.jobs;
                    o.failed=data.failed;
                    o.started=data.started;
                    o.finished=data.finished;
                    
                    // _d(o);
                    // Parse response
                })
        },
        init() {
            var o = this;
            _d('init batchDetail', o.batch_id);

            o.fetchJobs();

            /* Timer loop ----------------------------------------*/
            let batch, origin = new Date().getTime(), i = 0;
            const timer = () => {
                if (new Date().getTime() - i > origin){
                    i = i + o.interval;
                    if(o.started && !o.finished){ //(!o.failed ||!o.stop_on_fail) && o.progress<100){
                        o.fetchJobs();
                    }

                    batch = requestAnimationFrame(timer)
                } else if (batch !== null){
                    requestAnimationFrame(timer)    
                }
            }

            /* Start looping or start again ------------------------*/
            requestAnimationFrame(timer)
            // Stop the loop
            // batch = null
        }

    }));


    Alpine.data('batchProgress', (config) => ({
        batch_id: config.batch_id,
        interval: 2000,
        url: config.url,
        cancel_url: config.cancel_url,
        progress: config.progress,
        stop_on_fail: config.stop_on_fail??false,
        failed: config.failed,
        started: config.started,
        finished: config.finished,
        height: config.height??'25px',
        
        init() {
            var o = this;
            _d('init Progressbar', o.batch_id);

        
            /* Timer loop ----------------------------------------*/
            let batch, origin = new Date().getTime(), i = 0;
            const timer = () => {
                if (new Date().getTime() - i > origin){
                    i = i + o.interval;
                    if(o.started && !o.finished){ //(!o.failed ||!o.stop_on_fail) && o.progress<100){
                        console.log("Fetching updates...", o.started, o.progress);
                        fetch(o.url)
                            .then(response => response.json())
                            .then((data) => {
                                // _d(data,o);
                                o.progress=data.progress;
                                o.failed=data.failed;
                                o.started=data.started;
                                o.finished=data.finished;
                                if(data.file_url??null){
                                    _d('o.file_url',data.file_url);
                                    window.location.href=data.file_url;
                                }
                                // _d(o);
                                // Parse response
                            })
                    }

                    batch = requestAnimationFrame(timer)
                } else if (batch !== null){
                    requestAnimationFrame(timer)    
                }
            }

            /* Start looping or start again ------------------------*/
            requestAnimationFrame(timer)
            // Stop the loop
            // batch = null
        },
        cancel(){
            let o=this;
            var data={
                _token : getCsrfToken()
            };
            fetch(o.cancel_url,{
                method:'POST',
                body: JSON.stringify(data),
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                }
            }).then(response => response.json())
              .then((data) => {
                    // _d(data);
                    if(data.status=="success"){
                        
                    }else{
                        
                    }

              });
        },
    }));
});


