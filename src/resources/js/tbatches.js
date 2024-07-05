
import './utils';

document.addEventListener('alpine:init', () => {
    

    Alpine.data('batchProgress', (config) => ({
        id:config.id??null,
        batch_id: config.batch_id??null,
        batch: config.batch??null,
        interval: 2000,
        url_prefix: '/ajtarragona/batches',
        the_url: null,
        showDetail:false,
        hidden:false,
        hideOnFinish:config.hideOnFinish??false,
        hideDelay:config.hideDelay??1000,
        jobs:[],
        // cancel_url: config.cancel_url,
        // progress: config.progress,
        // stop_on_fail: config.stop_on_fail??false,
        // failed: config.failed,
        // started: config.started,
        // finished: config.finished,
        height: config.height??'20px',
        
        async init() {
            var o = this;
            _d('init batch', o.hideOnFinish);
            
            if(o.batch && !o.batch_id){
                o.batch_id=o.batch.id;
                o.the_url=baseUrl()+o.url_prefix+'/batch/'+o.batch_id;

                // _d(o.batch);
            }else if(o.batch_id && !o.batch){
                o.the_url=baseUrl()+o.url_prefix+'/batch/'+o.batch_id;

                // _d('from ID ' +o.batch_id);
                //recupero el batch
                await o.fetchBatch(true);
                // _d('after',o.batch);
            }

            // _d('init batch',this.batch_id);
            
            if(!o.batch) return;
            o.jobs=o.batch.jobs??[];

            // _d('init Progressbar', o.batch_id);


            
            // _d(o.url, o.batch);
            // return;
                    
        
            /* Timer loop ----------------------------------------*/
            let batch, origin = new Date().getTime(), i = 0;
            const timer = () => {
                if (new Date().getTime() - i > origin){
                    i = i + o.interval;
                    if(!o.batch.finished_at){ //(!o.failed ||!o.stop_on_fail) && o.progress<100){
                        o.fetchBatch(false);
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
        async fetchBatch(first) {
            let o=this;
            console.log("Fetching updates...", o.batch_id);
            await fetch(o.the_url)
                .then(response => response.json())
                .then((data) => {
                    // _d(data);
                    o.batch=data.batch;
                    o.jobs=data.jobs;
                    
                    if(o.hideOnFinish){
                        if(o.batch.finished_at ){
                            if(first || !o.hideDelay){
                                o.hidden=true;
                            }else{
                                setTimeout(function(){
                                    o.hidden=true;
                                    
                                }, o.hideDelay );
                            }
                        }else{
                            o.hidden=false;
                        }
                    }

                    if(o.batch.finished_at){
                        // _d('batch finished ',o.batch);
                        o.$dispatch('batchfinished', {batch:o.batch});
                    }

                    if(data.file_url??null){
                        // _d('o.file_url',data.file_url);
                        window.location.href=data.file_url;
                    }
                    if(o.showDetail){
                        o.stickToBottom();
                    }
                    // _d(o);
                    // Parse response
                });
        },
        async setBatch(batch_id){
            let o =this;
            this.batch_id=batch_id;
            this.the_url=baseUrl()+this.url_prefix+'/batch/'+this.batch_id;

            // _d('from ID ' +o.batch_id);
            //recupero el batch
            await o.fetchBatch(true);
        },
        stickToBottom(){
            let o=this;
            o.$nextTick(()=>{
            // setTimeout(()=>{
                let container=o.$refs['batchDetail'];
                let h=container.scrollHeight;
                // _d(container, h);
                container.scrollTo(0, h);
 
            });
        },

        toggleDetails(){
            let o=this;

            // _d('toggleDetails',this.showDetail);
            this.showDetail=!this.showDetail;
            if(this.showDetail){
                // setTimeout(()=>{
                //     o.stickToBottom();
                // },50);
            }

        },
        cancel(){
            let o=this;
            var data={
                _token : getCsrfToken()
            };
            fetch(o.the_url+'/cancel',{
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


