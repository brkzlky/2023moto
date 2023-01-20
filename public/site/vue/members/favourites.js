var favourites = new Vue({
    el: '#favourites',
    data: {
        csrf: $('meta[name="csrf-token"]').attr('content'),
        lang: $('html')[0].lang,
        listingNo: null,
        deleteFavText: $('#delete_msg').html()
    },
    methods: {
        deleteFav: function(id, guid) {
            this.listingNo = guid;
            this.deleteFavText = this.deleteFavText.split(':listing_no').join(id);
            setTimeout(() => {
                $('#deleteFavModal').modal('show');
            }, 100);
        }
    },
    mounted() {
        $('#deleteFavModal').on('hidden.bs.modal', function(){
            this.listingNo = null;
            this.deleteFavText = $('#delete_msg').html();
        });
    }
});