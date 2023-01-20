var header = new Vue({
    el: '#headerbar',
    data: {
        search: [],
        key: null,
        searchInterval: null,
        onSearch: false,
        lang: $('html')[0].lang,
        listingpath: window.location.origin+'/listings'
    },
    methods: {
        searchListing: function(e) {
            this.onSearch = true;
            if(this.searchInterval != null) {
                clearInterval(this.searchInterval);
            }
            this.key = e.target.value;
            var i = 0;
            this.searchInterval = setInterval(() => {
                if(i == 1000) {
                    this.search = [];
                    var postdata = new FormData();
                    postdata.append('key', this.key);
                    axios.post(window.location.origin+'/search', postdata).then((d) => {
                        if(d.status == 200) {
                            this.search = d.data;
                        } else {
                            var name = this.lang == 'ar' ? 'نتائج البحث غير موجودة' : 'Search results not found.';
                            this.search.push({listing_no: null, name: name, slug: null, category: null, catslug: null});
                        }
                    }).catch(() => {
                        var name = this.lang == 'ar' ? 'نتائج البحث غير موجودة' : 'Search results not found.';
                        this.search.push({listing_no: null, name: name, slug: null, category: null, catslug: null});
                    });

                    setTimeout(() => {
                        this.onSearch = false;
                        clearInterval(this.searchInterval);
                    }, 200);
                }
                i += 1000;
            }, 750);
        }
    },
    mounted() {
        $('.c-select2--loc').on('select2:selecting', function(e) {
            window.location = e.params.args.data.id;
        });
    }
});