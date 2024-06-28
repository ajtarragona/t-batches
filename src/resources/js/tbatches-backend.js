import './utils';

document.addEventListener('alpine:init', () => {
    
    Alpine.data('batches', (config) => ({
        batches: [],
        queues:[],
        url_prefix: '/ajtarragona/batches',
        url: null,
        loading:true,
        firstloading:true,
        filter:{
            queue:null,
            term:null,
            status:null,
            from_date:null,
            until_date:null,

        },
        async fetchBatches(first) {
            var o = this;
            // console.log("Fetching batches...");
            let params={...o.filter, _token: getCsrfToken()};
            // _d(params);
            o.loading=true;
            fetch(o.url,{
                method:'post',
                body:JSON.stringify(params),
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                }
            }).then(response => response.json())
              .then((data) => {
                    o.batches=data.batches;
                    // _d(o.batches);
                    o.loading=false;
                    o.firstloading=false;
                    
                    //añade las posibles nuevas colas
                    let queues=o.batches.map(batch=>batch.queue);
                    o.queues=[...o.queues,...queues];
                    o.queues = [ ...new Set(o.queues)];
                    
            })
        },
        init() {
            var o = this;
            _d('init batches');
            o.url=baseUrl()+o.url_prefix;
            // _d(o.url);
            o.fetchBatches(true);

            this.$watch('filter', function (values) {
                console.log('filter changed',values);
                o.fetchBatches();
            // o.grid.setGridOption('quickFilterText',o.term);
                // if(o.term) o.stateChanged=true;
            });

            

        },
        refresh(){
            this.fetchBatches();
        },

        removeAll(){
            var o = this;
            let params={_token: getCsrfToken()};
            let url=o.url;
            

            
            fetch(url,{
                method:'delete',
                body:JSON.stringify(params),
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                }
            }).then(response => response.json())
              .then((data) => {
                o.refresh();
            })
        },
        remove(batch_id){
            // _d('delete',batch_id);
            var o = this;
            let params={_token: getCsrfToken()};
            let url=o.url+'/batch/'+batch_id;
            

            
            fetch(url,{
                method:'delete',
                body:JSON.stringify(params),
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                }
            }).then(response => response.json())
              .then((data) => {
                var index = o.batches.findIndex(batch => batch.id === batch_id);
                // _d(index);
                o.batches.splice(index, 1);
            })
        },
        launchTest(name){
            var o = this;
            let params={ name:name, _token: getCsrfToken()};
            // _d('test',params);
            o.loading=true;
            fetch(o.url+'/batch/test',{
                method:'post',
                body:JSON.stringify(params),
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                }
            }).then(response => response.json())
              .then((data) => {
                // _d(data);
                    o.$dispatch('batchadded' , {batch:data.batch});
                    o.refresh();
            })
        }

    }));

});


