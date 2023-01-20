<div class="c-filter position-relative is-visible">
    <div class="loadmask" v-if="loadingResults">
    </div>
    <div class="c-filter__title c-filter__title--big">
        {{ __('page/listing.search') }}
        <div class="c-filter__clear" @click="clearFilter()" v-if="isFiltered">{{ __('module/label.clear_filter') }}</div>
        <span id="closeFilter" class="c-filter__close">
            <svg class="c-icon__svg c-icon--sm">
                <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#close') }}"></use>
            </svg>
        </span>
    </div>

    <div class="c-filter__body">
        <div class="c-filter__row">
            <select class="js-filter-select--loc c-select2--filter" name="location">
                <option value=""></option>
                <option :value="l.location_guid" v-for="l in locations" :selected="l.location_guid == filter.location">@{{ lang == 'ar' ? l.name_ar : l.name_en }}</option>
            </select>

            <select class="js-filter-select--category c-select2--filter" name="category">
                <option value=""></option>
                <option :value="c.category_guid" v-for="c in categories" :selected="c.category_guid == filter.category">@{{ lang == 'ar' ? c.name_ar : c.name_en }}</option>
            </select>

            <select class="js-filter-select--subcategory c-select2--filter" name="subcategory">
                <option value=""></option>
                <option :value="sc.category_guid" v-for="sc in subcategories" :selected="sc.category_guid == filter.subcategory" v-if="checkSubcat(sc.category_guid)">@{{ lang == 'ar' ? sc.name_ar : sc.name_en }}</option>
            </select>

            <select class="js-filter-select--brand c-select2--filter" name="brand">
                <option value="" v-if="filter.brand?.id == null"></option>
                <option :value="filter.brand?.id" v-if="filter.brand?.id != null">@{{ filter.brand?.text }}</option>
            </select>
            <input type="hidden" name="brand_name" :value="filter.brand?.text">

            <select class="js-filter-select--model c-select2--filter" name="model">
                <option value="" v-if="filter.model?.id == null"></option>
                <option :value="filter.model?.id" v-if="filter.model?.id != null">@{{ filter.model?.text }}</option>
            </select>
            <input type="hidden" name="model_name" :value="filter.model?.text">

            <select class="js-filter-select--color c-select2--filter" name="color">
                <option value=""></option>
                <option :value="cl.color_guid" v-for="cl in colors" :selected="cl.color_guid == filter.color.id" v-if="checkColor(cl.color_guid)">@{{ lang == 'ar' ? cl.name_ar : cl.name_en }}</option>
            </select>
            <input type="hidden" name="color_name" :value="filter.color?.text">

            <select class="js-filter-select--fueltype c-select2--filter" name="fuel_type">
                <option value=""></option>
                <option value="gasoline" :selected="filter.fuel_type == 'gasoline'" v-if="checkFuel('gasoline')">@{{ lang == 'ar' ? 'الغازولين' : 'Gasoline' }}</option>
                <option value="diesel" :selected="filter.fuel_type == 'diesel'" v-if="checkFuel('diesel')">@{{ lang == 'ar' ? 'ديزل' : 'Diesel Fuel' }}</option>
                <option value="lpg" :selected="filter.fuel_type == 'lpg'" v-if="checkFuel('lpg')">@{{ lang == 'ar' ? 'غاز البترول المسال' : 'LPG Gas' }}</option>
                <option value="electric" :selected="filter.fuel_type == 'electric'" v-if="checkFuel('electric')">@{{ lang == 'ar' ? 'سيارة كهربائية' : 'Electric Car' }}</option>
            </select>

            <select class="js-filter-select--listingtype c-select2--filter" name="type">
                <option value=""></option>
                <option value="sell" :selected="filter.type == 'sell'" v-if="checkListingType('sell')">{{ __('module/label.for_sale') }}</option>
                <option value="rent" :selected="filter.type == 'rent'" v-if="checkListingType('rent')">{{ __('module/label.for_rent') }}</option>
            </select>

            <select class="js-filter-select--situation c-select2--filter" name="situation">
                <option value=""></option>
                <option value="used" :selected="filter.type == 'used'" v-if="checkSituation('used')">@{{ lang == 'ar' ? 'سيارة مستعملة' : 'Used' }}</option>
                <option value="new" :selected="filter.situation == 'new'" v-if="checkSituation('new')">@{{ lang == 'ar' ? 'الجديد' : 'New' }}</option>
            </select>

            <select class="js-filter-select--seller-type c-select2--filter" name="seller_type">
                <option value=""></option>
                <option :value="t.type_guid" v-for="t in userTypes" v-if="checkSeller(t.type_guid)">@{{ lang == 'ar' ? t.name_ar : t.name_en }}</option>
            </select>
        </div>
        <div class="c-filter__row">
            <div class="c-filter__title">
                {{ __('page/listing.price_range') }}
            </div>
            <div class="c-filter__wrapper">
                <input type="text" inputmode="numeric" class="c-input c-input--filter" placeholder="min" name="price_min" :value="filter.price?.min" @keyup="setValue('price','min',$event)">
                <input type="text" inputmode="numeric" class="c-input c-input--filter" placeholder="max" name="price_max" :value="filter.price?.max" @keyup="setValue('price','max',$event)">
            </div>
        </div>
        <div class="c-filter__row">
            <div class="c-filter__title">
                {{ __('page/listing.year_range') }}
            </div>
            <div class="c-filter__wrapper">
                <input type="number" maxlength="4" min="0" class="c-input c-input--filter" placeholder="min" name="year_min" :value="filter.year?.min" @keyup="setValue('year','min',$event)">
                <input type="number" maxlength="4" min="0" class="c-input c-input--filter" placeholder="max" name="year_max" :value="filter.year?.max" @keyup="setValue('year','max',$event)">
            </div>
        </div>
        <div class="c-filter__row">
            <div class="c-filter__title">
                {{ __('page/listing.mileage_range') }}
            </div>
            <div class="c-filter__wrapper">
                <input type="number" class="c-input c-input--filter" placeholder="min" name="km_min" :value="filter.km?.min" @keyup="setValue('km','min',$event)">
                <input type="number" class="c-input c-input--filter" placeholder="max" name="km_max" :value="filter.km?.max" @keyup="setValue('km','max',$event)">
            </div>
        </div>
    </div>

    <button name="filter" @click="doFilter()" class="c-button c-button--black c-button--block c-button--sm c-filter__submit-btn mt-4">Search</button>
</div>