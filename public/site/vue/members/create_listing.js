var mcl = new Vue({
    el: '#member_create_listing',
    data: {
        listingNo: null,
        listing: null,
        user: null,
        vehicles: [],
        subcats: [],
        locations: [],
        currencies: [],
        colors: [],
        attributeGroups: [],
        listingAttributes: [],
        listingType: null,
        selectedLocation: null,
        selectedSituation: null,
        selectedCategory: null,
        selectedCategorySlug: null,
        selectedBrand: null,
        selectedModel: null,
        selectedTrim: null,
        selectedYear: null,
        selectedMileage: null,
        selectedColor: null,
        selectedFuel: null,
        selectedVehicleType: null,
        listingTitle: null,
        listingDesc: null,
        selectedCountry: null,
        selectedCountryLabel: null,
        selectedCity: null,
        selectedState: null,
        selectedCurrency: 'QAR',
        listingPrice: null,
        formApplied: null,
        showFirstDetail: false,
        showSecondDetail: false,
        firstCompleted: false,
        listingCreated: false,
        hasError: false,
        marker: null,
        map: null,
        latitude: 25.20484930,
        longitude: 55.27078280,
        latitudeRaw: 25.20484930,
        longitudeRaw: 55.27078280,
        coordError: false,
        coordErrorMsg: null,
        lang: $('html')[0].lang,
        csrf: $('meta[name="csrf-token"]').attr('content'),
    },
    methods: {
        listingDatas: function() {
            axios.post(window.location.origin+'/member/listing-specs').then((d) => {
                if(d.status == 200) {
                    this.vehicles = d.data.vehicles;
                    this.locations = d.data.locations;
                    this.currencies = d.data.currencies;
                    this.colors = d.data.colors;
                    this.showFirstDetail = true;
                } else {
                    this.vehicles = [];
                    this.locations = [];
                    this.currencies = [];
                    this.colors = [];
                    this.showFirstDetail = false;
                }
            }).catch(() => {
                this.vehicles = [];
                this.locations = [];
                this.currencies = [];
                this.colors = [];
                this.showFirstDetail = false;
            })
        },
        selectCategory: function(guid, slug) {
            this.selectedCategory = guid;
            this.selectedCategorySlug = slug;
            var cat = this.vehicles.find((v) => v.category_guid == guid);
            this.subcats = cat.children;
            this.listingType = null;
            this.selectedBrand = null;
            this.selectedModel = null;
            this.selectedTrim = null;
            $('.listing-about').addClass('op0');
            $('.js-adv-brand-select').val(null).trigger("change");
            $('.js-adv-model-select').val(null).trigger("change");
            $('.js-adv-trim-select').val(null).trigger("change");
            $('.js-adv-location-select').val(null).trigger("change");
            $('.js-adv-situation-select').val(null).trigger("change");
        },
        setMileage: function(e) {
            this.selectedMileage = e.target.value;
        },
        setTitle: function(e) {
            this.listingTitle = e.target.value;
        },
        setDesc: function(e) {
            this.listingDesc = e.target.textContent;
        },
        setPrice: function(e) {
            this.listingPrice = e.target.value;
        },
        setApply: function(e) {
            if(e.target.checked) {
                this.formApplied = true;
            } else {
                this.formApplied = false;
            }
        },
        addAttr: function(g) {
            var find = this.listingAttributes.find((la) => la == g);
            if(find != undefined) {
                var ind = this.listingAttributes.findIndex((la) => la == g);
                if(ind != -1) {
                    this.listingAttributes.splice(ind, 1);
                }
            } else {
                this.listingAttributes.push(g);
            }
        },
        selectListingType: function(type) {
            $('.listing-about').addClass('op0');
            this.listingType = type;
            setTimeout(() => {
                this.syncSelects();
            }, 100);
            setTimeout(() => {
                $('.listing-about').removeClass('op0');
            }, 300);
        },
        completeFirstStep: function(type) {
            $('.c-select2--air').next().removeClass('select2-error');
            $('.c-input--air').removeClass('select2-error');
            this.hasError = false;

            if(this.selectedLocation == null) {
                $('.js-adv-location-select').next().addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedSituation == null) {
                $('.js-adv-situation-select').next().addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedVehicleType == null) {
                $('.js-adv-subcat-select').next().addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedBrand == null) {
                $('.js-adv-brand-select').next().addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedModel == null) {
                $('.js-adv-model-select').next().addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedYear == null) {
                $('.js-adv-year-select').next().addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedMileage == null) {
                $('.js-adv-mileage').addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedColor == null) {
                $('.js-adv-color-select').next().addClass('select2-error');
                this.hasError = true;
            }
            if(this.selectedFuel == null) {
                $('.js-adv-fuel-select').next().addClass('select2-error');
                this.hasError = true;
            }

            if(!this.hasError) {
                this.firstCompleted = true;
                setTimeout(() => {
                    this.showSecondDetail = true;
                    setTimeout(() => {
                        this.loadSecondStepPlugins();
                    }, 200);
                }, 1500);
            }
        },
        syncSelects: function() {
            var self = this;
            $('.js-adv-location-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'موقع' : 'Location',
            }).on('select2:selecting', function(e) {
                console.log(e.params.args.data.element.dataset);
                self.selectedCountry = e.params.args.data.element.dataset.cnt;
                self.selectedCountryLabel = e.params.args.data.element.dataset.cntlael;
                self.latitude = e.params.args.data.element.dataset.lat;
                self.longitude = e.params.args.data.element.dataset.lng;
                self.latitudeRaw = e.params.args.data.element.dataset.lat;
                self.longitudeRaw = e.params.args.data.element.dataset.lng;
                self.selectedCurrency = e.params.args.data.element.dataset.cur;
                self.selectedLocation = e.params.args.data.id;
            });

            // vehicle situation
            $('.js-adv-situation-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'قارة' : 'Situation',
            }).on('select2:selecting', function(e) {
                self.selectedSituation = e.params.args.data.id;
            });

            // vehicle color
            $('.js-adv-color-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'قارة' : 'Color',
            }).on('select2:selecting', function(e) {
                self.selectedColor = e.params.args.data.id;
            });

            // vehicle fuel type
            $('.js-adv-fuel-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'قارة' : 'Fuel Type',
            }).on('select2:selecting', function(e) {
                self.selectedFuel = e.params.args.data.id;
            });

            // vehicle sub category
            $('.js-adv-subcat-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'نوع' : 'Type',
            }).on('select2:selecting', function(e) {
                self.selectedVehicleType = e.params.args.data.id;
            });

            // vehicle Brand Spec
            $('.js-adv-brand-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'ماركة' : 'Brand',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/member/listing-brands',
                    data: function (params) {
                        var query = {
                            _token: self.csrf,
                            brand: params.term,
                            category: self.selectedCategory
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
            });

            // vehicle model
            $('.js-adv-model-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'نموذج' : 'Model',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/member/listing-models',
                    data: function (params) {
                      var query = {
                        _token: self.csrf,
                        model: params.term,
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
            });

            // vehicle trim
            $('.js-adv-trim-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'تقليم' : 'Trim',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/member/listing-trims',
                    data: function (params) {
                      var query = {
                        _token: self.csrf,
                        trim: params.term,
                        model: self.selectedModel,
                        year: self.selectedYear
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
                self.selectedTrim = e.params.args.data.id;
            });

            // vehicle color
            $('.js-adv-year-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'عام' : 'Year',
            }).on('select2:selecting', function(e) {
                self.selectedYear = e.params.args.data.id;
            });
        },
        loadSecondStepPlugins: function() {
            var self = this;
            $('.summernote-en').summernote({
                toolbar: [
                    ['cleaner', ['cleaner']],
                    ['style', ['style', 'bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                ],
                fontNames: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
                fontNamesIgnoreCheck: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
                cleaner: {
                    action: 'paste',
                },
                height: 300,
                resize: false,
                lang: 'en-US',
                callbacks: {
                    onKeyup: function(e) {
                      self.setDesc(e);
                    }
                }
            });

            $('.summernote-ar').summernote({
                toolbar: [
                    ['cleaner', ['cleaner']],
                    ['style', ['style', 'bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                ],
                fontNames: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
                fontNamesIgnoreCheck: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
                cleaner: {
                    action: 'paste',
                },
                height: 300,
                resize: false,
                // lang: 'en-US',
                lang: 'ar-AR',
                callbacks: {
                    onKeyup: function(e) {
                      self.setDesc(e);
                    }
                }
            });

            // listing image uploader
            $('.listing-img-uploader').imageUploader({
                imagesInputName: 'photos',
                maxFiles: 20,
            });

            //for sorting images
            $(function () {
                $('.uploaded').sortable({
                    items: '.uploaded-image',
                    cancel: '.image-uploader-btn',

                    start: function (event, ui) {
                        $(ui.item).addClass('dragElemThumbnail');
                        ui.placeholder.height(ui.item.height());
                    },
                    stop: function (event, ui) {
                        $(ui.item).removeClass('dragElemThumbnail');
                    },
                });
                $('.uploaded').disableSelection();
            });

            $('.js-adv-country-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'دولة' : 'Country',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/member/listing-countries',
                    data: function (params) {
                      var query = {
                        _token: self.csrf,
                        country: params.term,
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
                $('.js-adv-state-select').val(null).trigger("change");
                $('.js-adv-city-select').val(null).trigger("change");
                self.selectedCountry = e.params.args.data.id;
                self.latitude = e.params.args.data.lat;
                self.longitude = e.params.args.data.lng;
                self.latitudeRaw = e.params.args.data.lat;
                self.longitudeRaw = e.params.args.data.lng;
                self.marker.setPosition(new google.maps.LatLng(self.latitude,self.longitude));
                self.map.setCenter(new google.maps.LatLng(self.latitude,self.longitude));
            });

            $('.js-adv-state-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'دولة' : 'State',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/member/listing-states',
                    data: function (params) {
                      var query = {
                        _token: self.csrf,
                        country: self.selectedCountry,
                        state: params.term,
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
                $('.js-adv-city-select').val(null).trigger("change");
                self.selectedState = e.params.args.data.id;
                self.latitude = e.params.args.data.lat;
                self.longitude = e.params.args.data.lng;
                self.latitudeRaw = e.params.args.data.lat;
                self.longitudeRaw = e.params.args.data.lng;
                self.marker.setPosition(new google.maps.LatLng(self.latitude,self.longitude));
                self.map.setCenter(new google.maps.LatLng(self.latitude,self.longitude));
            });

            $('.js-adv-city-select').select2({
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'مدينة' : 'City',
                ajax: {
                    type: 'POST',
                    url: window.location.origin+'/member/listing-cities',
                    data: function (params) {
                      var query = {
                        _token: self.csrf,
                        state: self.selectedState,
                        city: params.term,
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
                self.selectedCity = e.params.args.data.id;
                self.latitude = e.params.args.data.lat;
                self.longitude = e.params.args.data.lng;
                self.latitudeRaw = e.params.args.data.lat;
                self.longitudeRaw = e.params.args.data.lng;
                self.marker.setPosition(new google.maps.LatLng(self.latitude,self.longitude));
                self.map.setCenter(new google.maps.LatLng(self.latitude,self.longitude));
            });

            setTimeout(() => {
                this.loadMap();
            }, 1600);

            this.categoryAttributes();
        },
        categoryAttributes: function() {
            var postdata = new FormData();
            postdata.append('category',this.selectedCategory);
            axios.post(window.location.origin+'/member/listing-category-attributes', postdata).then((d) => {
                if(d.status == 200) {
                    this.attributeGroups = d.data;
                }
            })
        },
        completeListing: function() {
            this.hasError = false;
            $('.c-select2--air').next().removeClass('select2-error');
            $('.c-input').removeClass('select2-error');
            $('.note-editor').removeClass('select2-error');
            $('.attrbox').removeClass('select2-error');
            $('#accepting').parent().removeClass('select2-error');

            if(this.firstCompleted) {
                if(this.listingTitle == null) {
                    $('#listing_title').addClass('select2-error');
                    this.hasError = true;
                }
                if(this.listingDesc == null) {
                    $('.note-editor').addClass('select2-error');
                    this.hasError = true;
                }
                if(this.listingPrice == null) {
                    $('#listing_price').addClass('select2-error');
                    this.hasError = true;
                }
                if(this.selectedCountry == null) {
                    $('.js-adv-country-select').next().addClass('select2-error');
                    this.hasError = true;
                }
                if(this.selectedState == null) {
                    $('.js-adv-state-select').next().addClass('select2-error');
                    this.hasError = true;
                }
                if(this.selectedCity == null) {
                    $('.js-adv-city-select').next().addClass('select2-error');
                    this.hasError = true;
                }
                if(this.listingAttributes.length == 0) {
                    $('.attrbox').addClass('select2-error');
                    this.hasError = true;
                }
                if(!this.formApplied) {
                    $('#accepting').parent().addClass('select2-error');
                    this.hasError = true;
                }

                if(this.hasError) {
                    $(window).scrollTop(0);
                } else {
                    $('#submit_create_form').click();
                }
            }
        },
        loadMap: function() {
            var self = this;

            var mapOptions = {
                zoom: 13,
                center: {
                    lat: +this.latitude,
                    lng: +this.longitude
                    },
                scrollwheel: true,
                styles: [{"featureType":"all","elementType":"geometry.fill","stylers":[{"weight":"2.00"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"color":"#9c9c9c"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#eeeeee"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c8d7d4"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#070707"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}],
                disableDefaultUI: true
            }

            this.map = new google.maps.Map(document.getElementById('gmap'), mapOptions);

            var latlon = {lat: +this.latitude, lng: +this.longitude};

            this.marker = new google.maps.Marker({
                position: latlon,
                draggable: true,
                map: self.map
            });

            google.maps.event.addListener(this.marker, 'dragend', function (evt) {
                var selected_lat = evt.latLng.lat().toFixed(6);
                var selected_long = evt.latLng.lng().toFixed(6);
                if(+self.latitudeRaw - +selected_lat > 2.5 || +self.latitudeRaw - +selected_lat < -2.5 || +self.longitudeRaw - +selected_long > 2.5 || +self.longitudeRaw - +selected_long < -2.5) {
                    self.coordError = true;
                    setTimeout(() => {
                        self.coordError = false;
                    }, 8000);
                    var markerLatLng = new google.maps.LatLng(+self.latitudeRaw,+self.longitudeRaw);
                    self.marker.setPosition(markerLatLng);
                } else {
                    self.latitude = selected_lat;
                    self.longitude = selected_long;
                    self.map.setCenter(self.marker.position);
                }
            });
        }
    },
    mounted() {
        setTimeout(() => {
            mcl.listingDatas();
        }, 500);
    }
});
