var ml = new Vue({
    el: '#member_listings',
    data: {
        listingNo: null,
        deleteText: $('#delete_msg').html(),
        enableText: $('#enable_msg').html(),
        disableText: $('#disable_msg').html(),
        lang: $('html')[0].lang,
        csrf: $('meta[name="csrf-token"]').attr('content'),
    },
    methods: {
        deleteListing: function(no) {
            this.listingNo = no;
            this.deleteText = $('#delete_msg').html();
            this.deleteText = this.deleteText.split(':listing_no').join(no);
            
            setTimeout(() => {
                $('#deleteListingModal').modal('show');
            }, 150);
        },
        enableListing: function(no) {
            this.listingNo = no;
            this.enableText = $('#enable_msg').html();
            this.enableText = this.enableText.split(':listing_no').join(no);
            
            setTimeout(() => {
                $('#enableListingModal').modal('show');
            }, 150);
        },
        disableListing: function(no) {
            this.listingNo = no;
            this.disableText = $('#disable_msg').html();
            this.disableText = this.disableText.split(':listing_no').join(no);
            
            setTimeout(() => {
                $('#disableListingModal').modal('show');
            }, 150);
        }
    },
    mounted() {
        $('#deleteListingModal').on('hidden.bs.modal', function(){
            this.listingNo = null;
            this.deleteText = $('#delete_msg').html();
        });

        $('#enableListingModal').on('hidden.bs.modal', function(){
            this.listingNo = null;
            this.enableText = $('#enable_msg').html();
        });

        $('#disableListingModal').on('hidden.bs.modal', function(){
            this.listingNo = null;
            this.disableText = $('#disable_msg').html();
        });
    }
});