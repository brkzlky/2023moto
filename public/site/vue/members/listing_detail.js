var ld = new Vue({
    el: '#member_listing_detail',
    data: {
        listingNo: $('#listing_no').val(),
        listing: null,
        user: null,
        vehicles: [],
        locations: [],
        currencies: [],
        brands: [],
        attributeGroups: [],
        listingAttributes: [],
        listingTitle: null,
        listingDesc: null,
        selectedCurrency: null,
        listingPrice: null,
        selectedColor: null,
        showDetail: false,
        latitude: null,
        longitude: null,
        latitudeRaw: null,
        longitudeRaw: null,
        coordError: false,
        marker: null,
        map: null,
        lang: $('html')[0].lang,
        csrf: $('meta[name="csrf-token"]').attr('content'),
        assetpath: window.location.origin+'/storage/listings'
    },
    methods: {
        listingDetail: function() {
            var postdata = new FormData();
            postdata.append('listing_no',this.listingNo);
            axios.post(window.location.origin+'/member/listing-detail', postdata).then((d) => {
                if(d.status == 200) {
                    this.listing = d.data.listing;
                    if(d.data.listing.latitude != null) {
                        this.latitude = d.data.listing.latitude;
                        this.latitudeRaw = d.data.listing.city.latitude;
                    }
                    if(d.data.listing.longitude != null) {
                        this.longitude = d.data.listing.longitude;
                        this.longitudeRaw = d.data.listing.city.longitude;
                    }
                    this.listingTitle = d.data.listing.name_en;
                    this.listingDesc = d.data.listing.description;
                    this.selectedCurrency = d.data.listing.currency_guid;
                    this.selectedColor = d.data.listing.color_guid;
                    this.listingPrice = d.data.listing.price;
                    this.listingAttributes = d.data.listing_attributes;
                    this.attributeGroups = d.data.attribute_groups;
                    this.currencies = d.data.currencies;
                    this.locations = d.data.locations;
                    this.brands = d.data.brands;
                    this.vehicles = d.data.vehicles;
                    this.user = d.data.user;

                    var preloadedImages = [];
                    if(d.data.listing.images.length > 0) {
                        for(var i = 0; i < d.data.listing.images.length; i++) {
                            var li = d.data.listing.images[i];
                            preloadedImages.push(
                                {id: li.image_guid, src: this.assetpath+'/'+this.listingNo+'/'+li.name}
                            )
                        }
                    }

                    setTimeout(() => {
                        this.showDetail = true;
                        setTimeout(() => {
                            this.syncSelects();

                            var large = '<div class="image-uploader-btn"><svg class="c-icon__svg c-icon--sm image-uploader-icon"><use xlink:href="'+window.location.origin+'/site/assets/images/sprite.svg#plus"></use></svg>Add Image</div>';
                            setTimeout(function () {
                                $('.image-uploader .uploaded').prepend(large);
                            }, 300);

                            // listing image uploader
                            $('.listing-img-uploader').imageUploader({
                                imagesInputName: 'newphotos',
                                preloadedInputName: 'photos',
                                preloaded: preloadedImages,
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
                        }, 200);
                    }, 300);

                    setTimeout(() => {
                        this.loadMap();


                    }, 1000);
                }
            })
        },
        checkAttr: function(guid) {
            if(jQuery.inArray(guid, this.listingAttributes) != -1) {
                return true;
            } else {
                return false;
            }
        },
        syncSelects: function() {
            var self = this;
            $('.js-adv-subcat-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'نوع' : 'Type',
            });
            $('.js-adv-location-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'موقع' : 'Location',
            });

            // vehicle situation
            $('.js-adv-situation-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'قارة' : 'Situation',
            });

            // vehicle Brand Spec
            $('.js-adv-brand-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'ماركة' : 'Brand',
            });

            // vehicle model
            $('.js-adv-model-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'نموذج' : 'Model',
            });

            // vehicle trim
            $('.js-adv-trim-select').select2({
                minimumResultsForSearch: Infinity,
                dropdownCssClass: 'c-select2--air__dropdown',
                placeholder: this.lang == 'ar' ? 'تقليم' : 'Trim',
            });

            $('.js-adv-color-select').select2();

            $('.js-adv-fuel-select').select2();

            $('.js-adv-year').mask('0000');

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
        },
        setTitle: function(e) {
            this.listingTitle = e.target.value;
        },
        setDesc: function(e) {
            this.listingDesc = e.target.textContent;
        },
        setCurrency: function(e){
            this.selectedCurrency = e.target.value;
        },
        setPrice: function(e) {
            this.listingPrice = e.target.value;
        },
        dragMarker:function(marker,map){
            var self = this;

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
                    self.map.setCenter(self.marker.position);
                } else {
                    self.latitude = selected_lat;
                    self.longitude = selected_long;
                    self.map.setCenter(self.marker.position);
                }
            });
        },
        loadMap: function() {
            var self = this;

            var mapOptions = {
              zoom: 4,
              center: {
                  lat: +this.latitude,
                  lng: +this.longitude
              },
              scrollwheel: true,
              styles: [{"featureType":"all","elementType":"geometry.fill","stylers":[{"weight":"2.00"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"color":"#9c9c9c"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#eeeeee"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c8d7d4"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#070707"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}],
              disableDefaultUI: true
            }

            this.map = new google.maps.Map(document.getElementById('gmap'), mapOptions);

            var latlon = new google.maps.LatLng(+this.latitude, +this.longitude);

            if(this.latitude != null) {
                self.map.setZoom(15);
            }

            this.marker = new google.maps.Marker({
                position: latlon,
                draggable: true,
                map: self.map
            });

            this.dragMarker(this.marker, this.map);
        }
    },
    mounted() {
        setTimeout(() => {
            ld.listingDetail();
        }, 500);
    }
});
