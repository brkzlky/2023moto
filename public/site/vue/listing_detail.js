var listing_detail = new Vue({
    el: '#listing_detail',
    data: {
        loan: $('#creditChange').val(),
        msg: null,
        listing: $('#listing').val(),
        alertMsg: null,
        receiver: $('#receiver').val(),
        csrf: $('meta[name="csrf-token"]').attr('content'),
        latitude: $('#latitude').val(),
        longitude: $('#longitude').val(),
        isFav: $('#listingFav').val(),
    },
    methods: {
        setLoan: function(e) {
            this.loan = e.target.value;
        },
        goFinancePage: function() {
            $('#creditChange').removeClass('input-error');
            if($.isNumeric(this.loan) && this.loan > 0) {
                window.location = window.location.origin+'/finance?loan='+this.loan;
            } else {
                $('#creditChange').addClass('input-error');
            }
        },
        openMessageModal: function() {
            $('#offerModal').modal('show');
        },
        closeMessageModal: function() {
            $('#offerModal').modal('hide');
        },
        setMsg: function(e) {
            this.msg = e.target.value;
        },
        sendMessage: function() {
            var postdata = new FormData();
            postdata.append('receiver', this.receiver);
            postdata.append('listing', this.listing);
            postdata.append('msg', this.msg);
            postdata.append('_token', this.csrf);
            axios.post(window.location.origin+'/member/create-message', postdata).then((d) => {
                if(d.status == 200) {
                    if(d.data.result == 200) {
                        this.alertMsg = d.data.msg;
                        this.showAlert('success');

                        setTimeout(() => {
                            this.closeMessageModal();
                        }, 1000);
                    } else {
                        this.alertMsg = d.data.msg;
                        this.showAlert('error');
                    }
                }
            });
        },
        showAlert: function(type) {
            $('.alertbox').addClass(type);

            setTimeout(() => {
                $('.alertbox').removeClass(type);

                setTimeout(() => {
                    this.alertMsg = null;
                }, 500);
            }, 4000);
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
                draggable: false,
                map: self.map
            });
        },
        addToFav: function() {
            var postdata = new FormData();
            console.log(this.listing);
            postdata.append('listing', this.listing);
            postdata.append('_token', this.csrf);
            axios.post(window.location.origin+'/member/add-to-fav', postdata).then((d) => {
                if(d.status == 200) {
                    if(d.data.result == 200) {
                        this.isFav = !this.isFav
                    }
                }
            });
        },
        removeFromFav: function() {
            var postdata = new FormData();
            postdata.append('listing', this.listing);
            postdata.append('_token', this.csrf);
            axios.post(window.location.origin+'/member/remove-from-fav', postdata).then((d) => {
                if(d.status == 200) {
                    if(d.data.result == 200) {
                        this.isFav = !this.isFav
                    }
                }
            });
        },
    },
    mounted() {
        $('#offerModal').on('hidden.bs.modal', function(){
            this.alertMsg = null;
        });
        setTimeout(() => {
            this.loadMap()
        }, 300);
    }
});