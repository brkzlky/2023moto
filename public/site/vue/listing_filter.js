var listing_filter = new Vue({
    el: '#listing_filter',
    data: {
        lang: $('html')[0].lang,
        csrf: $('meta[name="csrf-token"]').attr('content'),
        curcat: $('#curcat').val(),
        curcatshort: $('#curcatshort').val(),
        curloc: $('#curloc').val(),
        curlocshort: $('#curlocshort').val(),
        filter: [],
        availableFilter: [],
        locations: [],
        categories: [],
        subcategories: [],
        colors: [],
        brands: [],
        models: [],
        trims: [],
        userTypes: [],
        listingsRaw: [],
        listings: [],
        perPageItem: 9,
        totalPage: 1,
        currentPage: 1,
        paginationArray: [1],
        showFilter: false,
        loadingResults: false,
        onPageFilter: false,
        isFiltered: false,
        clearedFilter: true,
        detailUrl: window.location.origin+'/listings',
    },
    methods: {
        getFilterDatas: function() {
            this.locations = [];
            this.categories = [];
            this.colors = [];
            this.listings = [];
            this.listingsRaw = [];
            this.availableFilter = [];
            this.loadingResults = true;
            var postdata = new FormData();
            postdata.append('_token',this.csrf);
            postdata.append('category', this.curcat);
            postdata.append('location', this.curloc);
            if(this.onPageFilter) {
                if(!this.clearedFilter) {
                    this.isFiltered = true;
                    postdata.append('filter', JSON.stringify(this.filter));
                }
            }
            axios.post(window.location.origin+'/listing-filter', postdata).then((d) => {
                if(d.status == 200) {
                    this.filter = [];
                    this.locations = d.data.locations;
                    this.categories = d.data.categories;
                    this.colors = d.data.colors;
                    this.userTypes = d.data.user_types;
                    this.listingsRaw = d.data.listings;
                    this.totalPage = 1;
                    this.currentPage = 1;
                    //this.listings = d.data.listings;
                    this.availableFilter = d.data.available_filters;
                    this.filter = d.data.filter;

                    $('#lcount').html(this.listingsRaw.length);

                    this.paginate();

                    setTimeout(() => {
                        this.loadingResults = false;
                    }, 1000);

                    var find = this.categories.find((c) => c.category_guid == this.filter.category);
                    this.subcategories = find.children;

                    setTimeout(() => {
                        this.syncPlugins();
                    }, 600);

                    setTimeout(() => {
                        this.showFilter = true;
                    }, 300);
                }
            });
        },
        paginate: function(){
            this.paginationArray = [1];
            this.listingsRaw.forEach((lr, i) => {
                if((i+1) % this.perPageItem == 0) {
                    this.totalPage = this.totalPage + 1;
                    this.paginationArray.push(this.totalPage);
                }
            })

            setTimeout(() => {
                this.listings = this.listingsRaw.slice(((this.currentPage - 1)*this.perPageItem), this.perPageItem);
            }, 300);
        },
        goToPage: function(num) {
            var raw = this.listingsRaw;
            this.currentPage = num;
            var key = (((this.currentPage - 1)*this.perPageItem) - 1);
            if(key < 0) {
                key = 0;
            }
            setTimeout(() => {
                this.listings = raw.slice(key, key + this.perPageItem);
            }, 100);
        },
        doFilter: function() {
            this.onPageFilter = true;
            setTimeout(() => {
                this.getFilterDatas();
            }, 300);
        },
        clearFilter: function() {
            this.isFiltered = false;
            this.clearedFilter = true;
            this.onPageFilter = false;
            setTimeout(() => {
                this.getFilterDatas();
            }, 200);
        },
        setValue: function(key, type, e){
            var val = e.target.value;
            if(key == 'price') {
                if(type == 'min') {
                    this.filter.price.min = val;
                }
                if(type == 'max') {
                    this.filter.price.max = val;
                }
            }

            if(key == 'year') {
                if(type == 'min') {
                    this.filter.year.min = val;
                }
                if(type == 'max') {
                    this.filter.year.max = val;
                }
            }

            if(key == 'km') {
                if(type == 'min') {
                    this.filter.km.min = val;
                }
                if(type == 'max') {
                    this.filter.km.max = val;
                }
            }
        },
        syncPlugins: function () {
            var self = this;
            //filter select location
            $('.js-filter-select--loc').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'موقع' : 'Location',
            }).on('select2:selecting', function(e) {
                self.filter.location = e.params.args.data.id;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select category
            $('.js-filter-select--category').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'فئة' : 'Category',
            }).on('select2:selecting', function(e) {
                self.subcategories = [];
                self.filter.category = e.params.args.data.id;
                var find = self.categories.find((c) => c.category_guid == e.params.args.data.id);
                self.subcategories = find.children;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select subcategory
            $('.js-filter-select--subcategory').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'تصنيف فرعي' : 'Subcategory',
            }).on('select2:selecting', function(e) {
                self.filter.subcategory = e.params.args.data.id;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select color
            $('.js-filter-select--color').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'اللون' : 'Color',
            }).on('select2:selecting', function(e) {
                self.filter.color.id = e.params.args.data.id;
                self.filter.color.text = e.params.args.data.text;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select situation
            $('.js-filter-select--situation').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'قارة' : 'Situation',
            }).on('select2:selecting', function(e) {
                self.filter.situation = e.params.args.data.id;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select situation
            $('.js-filter-select--listingtype').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'نوع القائمة' : 'Listing Type',
            }).on('select2:selecting', function(e) {
                self.filter.type = e.params.args.data.id;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select seller type
            $('.js-filter-select--seller-type').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'نوع البائع' : 'Seller Type',
            }).on('select2:selecting', function(e) {
                self.filter.seller_type = e.params.args.data.id;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select fuel type
            $('.js-filter-select--fueltype').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'قارة' : 'Fuel Type',
            }).on('select2:selecting', function(e) {
                self.filter.fuel_type = e.params.args.data.id;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            //filter select brand
            $('.js-filter-select--brand').select2({
                dropdownCssClass: 'c-select2--filter__dropdown',
                placeholder: this.lang == 'ar' ? 'ماركة' : 'Brand',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/listing-brands',
                    data: function (params) {
                        var query = {
                            _token: self.csrf,
                            brand: params.term,
                            category: self.filter.category,
                            ac: JSON.stringify(self.availableFilter.brands)
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                          results: data
                        };
                    }
                }
            }).on('select2:selecting', function(e) {
                self.filter.brand.id = e.params.args.data.id;
                self.filter.brand.text = e.params.args.data.text;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });
            
            //filter select model
            $('.js-filter-select--model').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'نموذج' : 'Model',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/listing-models',
                    data: function (params) {
                      var query = {
                        _token: self.csrf,
                        model: params.term,
                        brand: self.filter.brand.id,
                        ac: JSON.stringify(self.availableFilter.models)
                      }
                      return query;
                    },
                    processResults: function (data) {
                        return {
                          results: data
                        };
                    }
                }
            }).on('select2:selecting', function(e) {
                self.filter.model.id = e.params.args.data.id;
                self.filter.model.text = e.params.args.data.text;
                self.onPageFilter = true;
                self.clearedFilter = false;
                self.isFiltered = true;
                setTimeout(() => {
                    self.getFilterDatas();
                }, 300);
            });

            // hide filter button trigger
            $('#closeFilter').click(function () {
                $('.c-filter').removeClass('is-visible');
                setTimeout(() => {
                    $('.c-filter').parent().addClass('d-none');
                }, 700);
            });

            // show filter button trigger
            $('#filterTrigger').click(function () {
                $('.c-filter').parent().removeClass('d-none');
                setTimeout(() => {
                    $('.c-filter').addClass('is-visible');
                }, 200);
            });
        },
        checkImage: function(num, mImage) {
            if(mImage == null) {
                return false;
            }
            var image = mImage.name;
            var url = window.location.origin+'/storage/listings/'+num+'/'+image;
            var http = new XMLHttpRequest();
            http.open('HEAD', url, false);
            http.send();
            if(http.status!=404) {
                return true;
            } else {
                return false;
            }
        },
        checkSubcat: function(guid) {
            if(jQuery.inArray(guid, this.availableFilter.subcats) != -1) {
                return true;
            } else {
                return false;
            }
        },
        checkColor: function(guid) {
            if(jQuery.inArray(guid, this.availableFilter.colors) != -1) {
                return true;
            } else {
                return false;
            }
        },
        checkFuel: function(guid) {
            if(jQuery.inArray(guid, this.availableFilter.fuels) != -1) {
                return true;
            } else {
                return false;
            }
        },
        checkSeller: function(guid) {
            if(jQuery.inArray(guid, this.availableFilter.sellers) != -1) {
                return true;
            } else {
                return false;
            }
        },
        checkSituation: function(guid) {
            if(jQuery.inArray(guid, this.availableFilter.situs) != -1) {
                return true;
            } else {
                return false;
            }
        },
        checkListingType: function(guid) {
            if(jQuery.inArray(guid, this.availableFilter.types) != -1) {
                return true;
            } else {
                return false;
            }
        },
        formatDate: function(date) {
            return '16.11.2021';
        }
    },
    mounted() {
      setTimeout(() => {
            var jt = JSON.parse($('#jtsearch').val());
            //$('#jtsearch').val('');

            if(jt != null) {
                this.onPageFilter = true;
                this.clearedFilter = false;
                this.filter = jt;
            }
            setTimeout(() => {
                listing_filter.getFilterDatas();
            }, 100);
      }, 400);
    }
});