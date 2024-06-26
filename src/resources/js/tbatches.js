
_d = function(...args) {
    // args.forEach(arg => {
    //     console.log(Alpine.raw(arg));
    // });

    var args=args.map((arg)=>{
        return Alpine.raw(arg);
    });

    console.log(...args);
}


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
        init() {
            var o = this;
            _d('init batchDetail', o.batch_id);

            

            /* Timer loop ----------------------------------------*/
            let batch, origin = new Date().getTime(), i = 0;
            const timer = () => {
                if (new Date().getTime() - i > origin){
                    i = i + o.interval;
                    if(o.started && !o.finished){ //(!o.failed ||!o.stop_on_fail) && o.progress<100){
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
        progress: config.progress,
        stop_on_fail: config.stop_on_fail??false,
        failed: config.failed,
        started: config.started,
        finished: config.finished,
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
        }
    }));
});


