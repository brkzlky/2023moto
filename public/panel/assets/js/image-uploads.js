"use strict";

// Class definition
var KTImageInputDemo = (function () {
    // Private functions
    var initDemos = function () {
        // Example 5
        var image = new KTImageInput("kt_image");

        image.on("cancel", function (imageInput) {
            swal.fire({
                title: "Resim başarıyla kaldırıldı!",
                type: "success",
                buttonsStyling: false,
                confirmButtonText: "Tamam",
                confirmButtonClass: "btn btn-primary font-weight-bold",
            });
        });

        image.on("change", function (imageInput) {
            swal.fire({
                title: "Resim başarıyla değiştirildi!",
                type: "success",
                buttonsStyling: false,
                confirmButtonText: "Tamam",
                confirmButtonClass: "btn btn-primary font-weight-bold",
            });
        });

        image.on("remove", function (imageInput) {
            swal.fire({
                title: "Resim başarıyla silindi!",
                type: "error",
                buttonsStyling: false,
                confirmButtonText: "Tamam",
                confirmButtonClass: "btn btn-primary font-weight-bold",
            });
        });
    };

    return {
        // public functions
        init: function () {
            initDemos();
        },
    };
})();

KTUtil.ready(function () {
    KTImageInputDemo.init();
});
