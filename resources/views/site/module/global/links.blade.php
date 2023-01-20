
    <link rel="icon" href="{{ secure_asset('site/assets/images/favicon.ico') }}" type="image/x-icon" />
    <link href="{{ secure_asset('site/assets/css/aos.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/scripts/owl-carousel/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/scripts/owl-carousel/owl.theme.default.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/scripts/bootstrap/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/scripts/image-uploader/image-uploader.min.css') }}" rel="stylesheet">
    @if(App::getLocale() == 'ar')
    <link href="{{ secure_asset('site/scripts/bootstrap/bootstrap.rtl.min.css') }}" rel="stylesheet" />
    @endif
    <link href="{{ secure_asset('site/assets/css/slick.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/noUi-slider.min.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('site/assets/css/slick-theme.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/index.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/slick.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/slick-theme.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/fancybox.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/summernote.min.css') }}" rel="stylesheet" />
    <link href="{{ secure_asset('site/assets/css/custom.css') }}" rel="stylesheet" />
@yield('css')