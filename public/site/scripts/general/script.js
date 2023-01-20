// AOS.init({
// 	duration: 1200,
// 	disable: 'mobile',
// 	startEvent: 'load',
// });

$(document).ready(function () {
	// go to top bytton
	$('.c-go-top').click(function () {
		$('html, body').animate({ scrollTop: 0 }, 700);
		return false;
	});

	// loc select
	$('.js-loc-select').select2({
		minimumResultsForSearch: Infinity,
		dropdownCssClass: 'c-select2--loc__dropdown',
	});

	// close mobile menu
	$('.c-mobile-menu__header-r').click(function () {
		$('body').removeClass('menu-isOpen');
	});

	// hamburger icon
	$('.c-hamburger').click(function () {
		// $(this).toggleClass('c-hamburger--isActive');
		$('body').toggleClass('menu-isOpen');
	});

	// country select2
	$('.js-indistury-select').select2({
		minimumResultsForSearch: Infinity,
		dropdownCssClass: 'c-select2--indistury__dropdown',
		placeholder: 'Select an option',
	});

	//filter select country
	$('.js-filter-select--country').select2({
		minimumResultsForSearch: Infinity,
		dropdownCssClass: 'c-select2--filter__dropdown',
		placeholder: 'Country',
	});

	//filter select vehicle
	$('.js-filter-select--vehicle').select2({
		minimumResultsForSearch: Infinity,
		dropdownCssClass: 'c-select2--filter__dropdown',
		placeholder: 'Vehicle',
	});

	//filter select type
	$('.js-filter-select--type').select2({
		minimumResultsForSearch: Infinity,
		dropdownCssClass: 'c-select2--filter__dropdown',
		placeholder: 'Type',
	});

	//filter select seller type
	$('.js-filter-select--seller-type').select2({
		minimumResultsForSearch: Infinity,
		dropdownCssClass: 'c-select2--filter__dropdown',
		placeholder: 'Seller Type',
	});

	// show filter button trigger
	$('#filterTrigger').click(function () {
		$('.c-filter').addClass('is-visible');
		$('body').css('overflow-y', 'hidden');
	});

	// hide filter button trigger
	$('#closeFilter').click(function () {
		$('.c-filter').removeClass('is-visible');
		$('body').css('overflow-y', '');
	});

	// custom dropdown toggle actions at navbar
	$('.l-navbar__right-item .dropdown-toggle').on('click', function (event) {
		if ($(this).parent().hasClass('is-open')) {
			$(this).parent().removeClass('is-open');
		} else {
			$('.l-navbar__right-item').removeClass('is-open');
			$(this).parent().addClass('is-open');
			$('body').removeClass('menu-isOpen');
			$('.c-hamburger').removeClass('c-hamburger--isActive');
		}
	});

	//close search dropdown when clicked close icon
	$('.dropdown-menu--search__close').click(function () {
		$('.l-navbar__right-item').removeClass('is-open');
	});

	//close dropdown when clicked outside at navbar
	$('html').on('click', function (e) {
		if (!$('.dropdown-menu').is(e.target) && $('.dropdown-menu').has(e.target).length === 0 && $('.is-open').has(e.target).length === 0) {
			$('.l-navbar__right-item').removeClass('is-open');
			$('.c-msg__more').removeClass('is-open');
			$('.c-widget__dropdown').removeClass('is-open');
			$('.c-h-card__dropdown').removeClass('is-open');
		}
	});

	//show message dropdown
	$('.c-msg__more.dropdown-toggle, .c-h-card__dropdown.dropdown-toggle').on('click', function (event) {
		if ($(this).hasClass('is-open')) {
			$(this).removeClass('is-open');
		} else {
			$('.c-msg__more').removeClass('is-open');
			$(this).addClass('is-open');
			$('body').removeClass('menu-isOpen');
			$('.c-hamburger').removeClass('c-hamburger--isActive');
		}
	});

	// scroll down message
	if ($('.c-msg__right-body').length !== 0) {
		$(window).on('load', function (e) {
			var msgRightHeight = $('.c-msg__right-body')[0].scrollHeight;
			$('.c-msg__right-body').scrollTop(msgRightHeight);
		});
	}

	var dir = $('html').attr('dir');
	if (dir == 'rtl') {
		//main page overflowed sliders
		$('.c-slider--overflowed').owlCarousel({
			loop: false,
			margin: 25,
			nav: false,
			dots: true,
			rtl: true,
			responsive: {
				0: {
					items: 1,
					center: false,
					stagePadding: 50,
				},
				576: {
					items: 2,
					stagePadding: 100,
				},
				768: {
					items: 2,
					stagePadding: 131,
				},
				992: {
					items: 3,
					stagePadding: 100,
					dots: false,
				},
				1200: {
					items: 3,
					stagePadding: 200,
					dots: false,
				},
				1400: {
					items: 3,
					stagePadding: 200,
					dots: false,
				},
				1600: {
					items: 4,
					stagePadding: 200,
					dots: false,
				},
			},
		});
		console.log('arabic');
		//product detail slider
		$('.c-product__slider').slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
			rtl: true,
		});
		//product detail mobile slider
		$('.c-product__slider--mobile').slick({
			centerMode: true,
			infinite: true,
			dots: false,
			rtl: true,
			speed: 210,
			cssEase: 'ease',
			responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2,
						centerMode: false,
						centerPadding: '0',
						infinite: false,
					},
				},
				{
					breakpoint: 576,
					settings: {
						slidesToShow: 1,
						centerMode: true,
						centerPadding: '60px',
					},
				},
			],
		});
		//year range slider
		var yearSlider = document.getElementById('js-year-range');
		var yearSliderValues = [document.getElementById('js-year-range__low'), document.getElementById('js-year-range__high')];
		var currentYear = new Date().getFullYear();

		if(yearSlider != null) {
			noUiSlider.create(yearSlider, {
				connect: true,
				start: [1, currentYear],
				step: 1,
				direction: 'rtl',
				range: {
					min: 1900,
					max: currentYear,
				},
				format: wNumb({
					decimals: 0,
					thousand: '',
				}),
			});

			yearSlider.noUiSlider.on('update', function (values, handle) {
				var year_min = $('#yearmin_input');
				var year_max = $('#yearmax_input');
				if(year_min.length > 0) {
					year_min.val(values[0]);
					$('#yearmin_fun').click();
				}
				if(year_max.length > 0) {
					year_max.val(values[1]);
					$('#yearmax_fun').click();
				}
				yearSliderValues[handle].innerHTML = values[handle];
			});
		}

		//km range slider
		var kmSlider = document.getElementById('js-km-range');
		var kmSliderValues = [document.getElementById('js-km-range__low'), document.getElementById('js-km-range__high')];

		if(kmSlider != null) {
			noUiSlider.create(kmSlider, {
				connect: true,
				start: [1, 400000],
				step: 1,
				direction: 'rtl',
				range: {
					min: 1,
					max: 999000,
				},
				format: wNumb({
					decimals: 3,
					thousand: '.',
					suffix: ' KM',
				}),
			});

			kmSlider.noUiSlider.on('update', function (values, handle) {
				var km_min = $('#kmmin_input');
				var km_max = $('#kmmax_input');
				if(km_min.length > 0) {
					km_min.val(values[0]);
					$('#kmmin_fun').click();
				}
				if(km_max.length > 0) {
					km_max.val(values[1]);
					$('#kmmax_fun').click();
				}
				kmSliderValues[handle].innerHTML = values[handle];
			});
		}
	} else {
		//product slider LTR
		$('.c-slider--overflowed').owlCarousel({
			loop: false,
			margin: 25,
			nav: false,
			dots: true,
			responsive: {
				0: {
					items: 1,
					center: false,
					stagePadding: 50,
				},
				576: {
					items: 2,
					stagePadding: 100,
				},
				768: {
					items: 2,
					stagePadding: 131,
				},
				992: {
					items: 3,
					stagePadding: 100,
					dots: false,
				},
				1200: {
					items: 3,
					stagePadding: 200,
					dots: false,
				},
				1400: {
					items: 3,
					stagePadding: 200,
					dots: false,
				},
				1600: {
					items: 4,
					stagePadding: 200,
					dots: false,
				},
			},
		});
		$('.c-product__slider').slick({
			infinite: true,
			slidesToShow: 3,
			slidesToScroll: 1,
			rtl: false,
		});
		//product detail mobile slider
		$('.c-product__slider--mobile').slick({
			centerMode: true,
			infinite: true,
			dots: false,
			rtl: false,
			speed: 210,
			cssEase: 'ease',
			responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 2,
						centerMode: false,
						centerPadding: '0',
						infinite: false,
					},
				},
				{
					breakpoint: 576,
					settings: {
						slidesToShow: 1,
						centerMode: true,
						centerPadding: '60px',
					},
				},
			],
		});
		//year slider
		var yearSlider = document.getElementById('js-year-range');
		var yearSliderValues = [document.getElementById('js-year-range__low'), document.getElementById('js-year-range__high')];
		var currentYear = new Date().getFullYear();

		if(yearSlider != null) {
			noUiSlider.create(yearSlider, {
				connect: true,
				start: [1, currentYear],
				step: 1,
				range: {
					min: 1900,
					max: currentYear,
				},
				format: wNumb({
					decimals: 0,
					thousand: '',
				}),
			});

			yearSlider.noUiSlider.on('update', function (values, handle) {
				var year_min = $('#yearmin_input');
				var year_max = $('#yearmax_input');
				if(year_min.length > 0) {
					year_min.val(values[0]);
					$('#yearmin_fun').click();
				}
				if(year_max.length > 0) {
					year_max.val(values[1]);
					$('#yearmax_fun').click();
				}
				yearSliderValues[handle].innerHTML = values[handle];
			});
		}

		//km slider
		var kmSlider = document.getElementById('js-km-range');
		var kmSliderValues = [document.getElementById('js-km-range__low'), document.getElementById('js-km-range__high')];

		if(kmSlider != null) {
			noUiSlider.create(kmSlider, {
				connect: true,
				start: [1, 400000],
				step: 1,
				range: {
					min: 1,
					max: 999000,
				},
				format: wNumb({
					decimals: 3,
					thousand: '.',
					suffix: ' KM',
				}),
			});

			kmSlider.noUiSlider.on('update', function (values, handle) {
				var km_min = $('#kmmin_input');
				var km_max = $('#kmmax_input');
				if(km_min.length > 0) {
					km_min.val(values[0]);
					$('#kmmin_fun').click();
				}
				if(km_max.length > 0) {
					km_max.val(values[1]);
					$('#kmmax_fun').click();
				}
				kmSliderValues[handle].innerHTML = values[handle];
			});
		}
	}

	// featured slider custom nav buttons
	var owlFeatured = $('.c-slider--featured');
	owlFeatured.owlCarousel();
	$('.c-slider--featured__nextBtn').click(function () {
		owlFeatured.trigger('next.owl.carousel');
	});
	$('.c-slider--featured__prevBtn').click(function () {
		owlFeatured.trigger('prev.owl.carousel', [300]);
	});

	// showcase slider custom nav buttons
	var owlShowwcase1 = $('.c-slider--showcase-1');
	owlShowwcase1.owlCarousel();
	$('.c-slider--showcase-1__nextBtn').click(function () {
		owlShowwcase1.trigger('next.owl.carousel');
	});
	$('.c-slider--showcase-1__prevBtn').click(function () {
		owlShowwcase1.trigger('prev.owl.carousel', [300]);
	});

	// showcase 2 slider (yatchs) custom nav buttons
	var owlShowwcase2 = $('.c-slider--showcase-2');
	owlShowwcase2.owlCarousel();
	$('.c-slider--showcase-2__nextBtn').click(function () {
		owlShowwcase2.trigger('next.owl.carousel');
	});
	$('.c-slider--showcase-2__prevBtn').click(function () {
		owlShowwcase2.trigger('prev.owl.carousel', [300]);
	});

	// showcase 3 slider (yatchs) custom nav buttons
	var owlShowwcase3 = $('.c-slider--showcase-3');
	owlShowwcase3.owlCarousel();
	$('.c-slider--showcase-3__nextBtn').click(function () {
		owlShowwcase3.trigger('next.owl.carousel');
	});
	$('.c-slider--showcase-3__prevBtn').click(function () {
		owlShowwcase3.trigger('prev.owl.carousel', [300]);
	});

	//floating input revealing
	$('.c-input--floating__revealing').click(function () {
		$(this).closest('.form-floating').find('.c-input').removeAttr('disabled').focus();
		$(this).parent('.form-floating').toggleClass('no-disabled');
	});

	//radio input revealing
	$('.c-input--floating__revealing--radio').click(function () {
		$(this).closest('.c-input--radio__wrapper').find('.c-input--radio').removeAttr('disabled');
		$(this).parent('.c-input--radio__wrapper').parent().toggleClass('no-disabled');
	});

	//toggle input revealing
	$('.c-input--floating__revealing--toggle').click(function () {
		$(this).closest('.c-input--toggle__wrapper').find('.c-input--toggle').removeAttr('disabled');
		$(this).parent('.c-input--toggle__wrapper').parent().toggleClass('no-disabled');
	});

	//multiple input focus
	$('.c-input__row-multiple .c-input').focus(function () {
		$(this).siblings().css('borderColor', 'var(--color-black)');
	});

	//multiple input blur
	$('.c-input__row-multiple .c-input').blur(function () {
		$(this).siblings().css('borderColor', '');
	});

	//file upload
	var large = '<div class="image-uploader-btn"><svg class="c-icon__svg c-icon--sm image-uploader-icon"><use xlink:href="../assets/images/sprite.svg#plus"></use></svg>Add Image</div>';
	setTimeout(function () {
		$('.image-uploader .uploaded').append(large);
	}, 300);

	// listing image uploader
	const listingUploader = $('.listing-img-uploader');
	if (listingUploader.length !== 0) {
		$('.listing-img-uploader').imageUploader({
			maxFiles: 20,
			preloadedInputName: 'old',
		});
	}

	const listingDetailNote = $('#summernote-listing-detail');
	if (listingDetailNote.length !== 0) {
		$('#summernote-listing-detail').summernote({
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
		});
	}

	//calculate result
	$('#creditCalc').click(function () {
		$('.js-calc-offer-container').slideDown(200, 'linear');
		$('.js-filter__card-container').slideDown(200, 'linear');
	});

	//accordion;
	$('.js-accordion-btn > a').on('click', function () {
		if ($(this).hasClass('is-active')) {
			$(this).removeClass('is-active');
			$(this).parent('.js-accordion-btn').removeClass('is-active');
			$(this).siblings('.js-accordion-content').slideUp(200);
			$(this).parent('.js-accordion-btn').prev().removeClass('c-accordion__row--no-border');
		} else {
			$('.js-accordion-btn > a').removeClass('is-active');
			$('.js-accordion-btn').removeClass('is-active');
			$(this).addClass('is-active');
			$(this).parent('.js-accordion-btn').addClass('is-active');
			$('.js-accordion-btn').prev().removeClass('c-accordion__row--no-border');
			$(this).parent('.js-accordion-btn').prev().addClass('c-accordion__row--no-border');

			$('.js-accordion-content').slideUp(200);
			$(this).siblings('.js-accordion-content').slideDown(200);
		}
	});

	//message height
	const msgTopHeight = $('.c-msg__wrapper').prevAll().innerHeight();
	const msgWrapper = $('.c-msg__wrapper');

	if ($(window).width() < 992) {
		msgWrapper.css('height', '100%');
	} else {
		msgWrapper.css('height', 'calc(100% - ' + msgTopHeight + 'px)');
	}

	// sticky bar
	if ($(window).width() > 767) {
		$(window).scroll(function () {
			const scroll = $(window).scrollTop();
			const header = $('.c-sticky-bar');
			const body = $('body');

			if (header.length !== 0) {
				if (scroll >= 60) {
					header.addClass('is-sticky');
					body.addClass('is-sticked');
				} else {
					header.removeClass('is-sticky');
					body.removeClass('is-sticked');
				}
			}
		});
	}

	//widget dropdowns
	$('.c-widget__dropdown .dropdown-toggle').on('click', function (event) {
		if ($(this).parent().hasClass('is-open')) {
			$(this).parent().removeClass('is-open');
		} else {
			$('.c-widget__dropdown').removeClass('is-open');
			$(this).parent().addClass('is-open');
			$('body').removeClass('menu-isOpen');
			$('.c-hamburger').removeClass('c-hamburger--isActive');
		}
	});



	// product detail thumbnails height
	var image = $('.c-product__gallery .c-product__gallery-col .c-product__gallery-image');
	var wrapper = $('.c-product__gallery .c-product__gallery-col--list');
	var imageHeight = image.innerHeight();

	var footerHeight = $('.c-product__gallery-col--list-footer').innerHeight();
	var thumb = $('.c-product__gallery-thumb');
	var thumbHeight = (imageHeight - footerHeight - 12) / 3;
	wrapper.css('height', `${imageHeight}`);

	thumb.css('height', `${thumbHeight}`);

	// single image uploader
	function readURL() {
		var $input = $(this);
		var $newinput = $(this).parent().parent().parent().find('.c-uploader__img ');
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				reset($newinput.next('.c-uploader__remove'), true);
				$newinput.attr('src', e.target.result).show();
				$newinput.after('<div class="c-uploader__remove"><svg class="c-icon__svg c-icon--xs"><use xlink:href="../assets/images/sprite.svg#close"></use></svg></div>');
			};
			reader.readAsDataURL(this.files[0]);
		}
	}
	$('.c-uploader__input').change(readURL);
	$('form').on('click', '.c-uploader__remove', function (e) {
		reset($(this));
	});

	function reset(elm, prserveFileName) {
		if (elm && elm.length > 0) {
			var $input = elm;
			$input.prev('.c-uploader__img').attr('src', '').hide();
			if (!prserveFileName) {
				$($input).parent().parent().parent().find('input.c-uploader__input ').val('');
				//input.fileUpload and input#uploadre both need to empty values for particular div
			}
			elm.remove();
		}
	}
});
