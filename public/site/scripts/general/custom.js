$('.cookie-accept').on('click', function() {
    axios.post(window.location.origin+'/accept-cookie').then((d) => {
        if(d.status == 200) {
            $('#cookiebox').addClass('remove');
        }
    })
});