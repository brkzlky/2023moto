var jumbotron = new Vue({
    el: '#jumbotron',
    data: {
        csrf: $('meta[name="csrf-token"]').attr('content'),
        lang: $('html')[0].lang,
        category: null,
        catname: $('#catname').val(),
        selectedBrand: null,
        selectedBrandName: null,
        selectedModel: null,
        selectedModelName: null,
        actionUrl: window.location.origin+'/listings/'+$('#catname').val(),
        yearMin: $('#yearmin_input').val(),
        yearMax: $('#yearmax_input').val(),
        kmMin: $('#kmmin_input').val(),
        kmMax: $('#kmmax_input').val(),
        yearChanged: false,
        kmChanged: false
    },
    methods: {
        selectCategory: function(id) {
            this.category = id;
        },
        yearMinChange: function() {
            this.yearMin = $('#yearmin_input').val();
            if(!this.yearChanged) {
                if(+this.yearMin == 1900 || this.yearMin == ""){
                    this.yearChanged = false;
                } else {
                    this.yearChanged = true;
                }
            }

            if(this.yearChanged) {
                $('#yearlabel').html(this.yearMin+'-'+this.yearMax);
            }
        },
        yearMaxChange: function() {
            this.yearMax = $('#yearmax_input').val();
            var currentYear = new Date().getFullYear();
            if(!this.yearChanged) {
                if(+this.yearMax == +currentYear || this.yearMax == ""){
                    this.yearChanged = false;
                } else {
                    this.yearChanged = true;
                }
            }

            if(this.yearChanged) {
                $('#yearlabel').html(this.yearMin+'-'+this.yearMax);
            }
        },
        kmMinChange: function() {
            this.kmMin = $('#kmmin_input').val();
            if(!this.kmChanged) {
                if(+this.kmMin == 1 || this.kmMin == "" || this.kmMin == "1 KM"){
                    this.kmChanged = false;
                } else {
                    this.kmChanged = true;
                }
            }

            if(this.kmChanged) {
                $('#kmlabel').html(this.kmMin+'-'+this.kmMax);
            }
        },
        kmMaxChange: function() {
            this.kmMax = $('#kmmax_input').val();
            if(!this.kmChanged) {
                if(+this.kmMax == 400000 || this.kmMax == "" || this.kmMax == "400.000 KM"){
                    this.kmChanged = false;
                } else {
                    this.kmChanged = true;
                }
            }

            if(this.kmChanged) {
                $('#kmlabel').html(this.kmMin+'-'+this.kmMax);
            }
        },
    },
    mounted() {
        setTimeout(() => {
            var self = this;

            // widget type select
            $('.js-widget-type-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--widget__dropdown',
                placeholder: this.lang == 'ar' ? 'فئة' : 'Category',
            }).on('select2:selecting', function(e) {
                self.category = e.params.args.data.id;
                self.catname = e.params.args.data.text.toLowerCase();
            });

            $('.js-widget-brand-select').select2({
                dropdownCssClass: 'c-select2--widget__dropdown',
                placeholder: this.lang == 'ar' ? 'ماركة' : 'Brand',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/jumbotron-brands',
                    data: function (params) {
                        var query = {
                            _token: self.csrf,
                            key: params.term,
                            category: self.category
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
                self.selectedBrand = e.params.args.data.id;
                self.selectedBrandName = e.params.args.data.text;
            });

            $('.js-widget-model-select').select2({
                dropdownCssClass: 'c-select2--widget__dropdown',
                placeholder: this.lang == 'ar' ? 'نموذج' : 'Model',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/jumbotron-models',
                    data: function (params) {
                        var query = {
                            _token: self.csrf,
                            key: params.term,
                            brand: self.selectedBrand
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
                self.selectedModel = e.params.args.data.id;
                self.selectedModelName = e.params.args.data.text;
            });
        }, 400);
    }
});