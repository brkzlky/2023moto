    const latitude = document.getElementById('latitude');
    const longitude = document.getElementById('longitude');
    const gmap = document.getElementById('gmap');

    const mapOptions = {
        zoom: 13,
        center: {
            lat: +latitude.value,
            lng: +longitude.value
            },
        scrollwheel: true,
        styles: [{"featureType":"all","elementType":"geometry.fill","stylers":[{"weight":"2.00"}]},{"featureType":"all","elementType":"geometry.stroke","stylers":[{"color":"#9c9c9c"}]},{"featureType":"all","elementType":"labels.text","stylers":[{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#eeeeee"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#7b7b7b"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#c8d7d4"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#070707"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"}]}],
        disableDefaultUI: true
    }

    const map = new google.maps.Map(gmap, mapOptions);
    const latlon = {lat: +latitude.value, lng: +longitude.value};

    const marker = new google.maps.Marker({
        position: latlon,
        draggable: true,
        map: map
    });

    google.maps.event.addListener(marker, 'dragend', function (evt) {
        const selected_lat = evt.latLng.lat().toFixed(6);
        const selected_long = evt.latLng.lng().toFixed(6);
        latitude.value = selected_lat;
        longitude.value = selected_long;
        map.setCenter(marker.position);
    });
