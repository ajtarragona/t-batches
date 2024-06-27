import './utils';

document.addEventListener('alpine:init', () => {
    
    Alpine.data('batches', (config) => ({
        batches: [],
        queues:[],
        url: config.url,
        filter:{
            queue:null,
            term:null

        },
        async fetchBatches() {
            var o = this;
            console.log("Fetching batches...");
            let params={...o.filter, _token: getCsrfToken()};
            // _d(params);
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
                    
                    o.queues=o.batches.map(batch=>batch.queue);
                    o.queues = [...new Set(o.queues)];
            })
        },
        init() {
            var o = this;
            _d('init batches');

            o.fetchBatches();

            this.$watch('filter', function (values) {
                console.log('filter changed',values);
                o.fetchBatches();
            // o.grid.setGridOption('quickFilterText',o.term);
                // if(o.term) o.stateChanged=true;
            });

        },
        refresh(){
            this.fetchBatches();
        }

    }));

});


