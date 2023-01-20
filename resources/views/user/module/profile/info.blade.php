<div class="row">
    <form class="col-12 col-md-12 col-lg-6 col-xl-6" action="{{ route('member.profile_info_update') }}" method="post" id="infoForm" enctype="multipart/form-data">
        @csrf
        <!-- text input -->
        <div class="form-floating c-input--floating__wrapper">
            <input type="text" class="form-control c-input c-input--floating" name="name" id="nameSurname" value="{{ $user->name }}" placeholder="{{ __('module/label.name_surname') }}" disabled>
            <span class="c-input--floating__revealing"> {{ __('module/button.edit') }} </span>

            <label class="c-input__label c-input--floating__label" for="nameSurname">
                {{ __('module/label.name_surname') }}
            </label>
        </div>
        <div class="form-floating c-input--floating__wrapper">
            <input type="text" class="form-control c-input c-input--floating" name="email" id="nameSurname" value="{{ $user->email }}" placeholder="{{ __('module/label.email') }}" disabled>
            <span class="c-input--floating__revealing"> {{ __('module/button.edit') }} </span>

            <label class="c-input__label c-input--floating__label" for="nameSurname">
                {{ __('module/label.email') }}
            </label>
        </div>
        <div class="form-floating c-input--floating__wrapper">
            <input type="text" class="form-control c-input c-input--floating" name="phone" id="nameSurname" value="{{ $user->phone }}" placeholder="{{ __('module/label.phone') }}" disabled>
            <span class="c-input--floating__revealing"> {{ __('module/button.edit') }} </span>

            <label class="c-input__label c-input--floating__label" for="nameSurname">
                {{ __('module/label.phone') }}
            </label>
        </div>
        <div class="form-floating c-input--floating__wrapper">
            <input type="text" class="form-control c-input c-input--floating" name="whatsapp" id="nameSurname" value="{{ $user->whatsapp }}" placeholder="{{ __('module/label.whatsapp') }}" disabled>
            <span class="c-input--floating__revealing"> {{ __('module/button.edit') }} </span>

            <label class="c-input__label c-input--floating__label" for="nameSurname">
                {{ __('module/label.whatsapp') }}
            </label>
        </div>
        <div class="form-floating c-input--floating__wrapper">
            <select class="form-select c-input c-input--floating" id="floatingSelect" aria-label="Floating label select example" name="country" disabled>
                <option value=""></option>
                @foreach($countries as $c)
                <option value="{{ $c->country_guid }}" {{ $user->country_guid == $c->country_guid ? 'selected' : null }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <span class="c-input--floating__revealing"> {{ __('module/button.edit') }} </span>

            <label class="c-input__label c-input--floating__label" for="nameSurname">{{ __('module/label.country') }}</label>

        </div>
        @if($user->type->id == '1')
        <div class="form-floating c-input--floating__wrapper">
            <select class="form-select c-input c-input--floating" id="floatingSelect" aria-label="Floating label select example" name="gender" disabled>
                <option value="m" {{ $user->gender == 'm' ? 'selected' : null }}>{{ __('module/label.male') }}</option>
                <option value="f" {{ $user->gender == 'f' ? 'selected' : null }}>{{ __('module/label.female') }}</option>
            </select>
            <span class="c-input--floating__revealing"> {{ __('module/button.edit') }} </span>

            <label class="c-input__label c-input--floating__label" for="nameSurname">{{ __('module/label.gender') }}</label>

        </div>
        <div class="form-floating c-input--floating__wrapper">
            <input type="date" class="form-control c-input c-input--floating" id="date" name="birthday" value="{{ $user->birthday }}" placeholder="00.00.0000" disabled>
            <span class="c-input--floating__revealing"> {{ __('module/button.edit') }} </span>
            <label class="c-input__label c-input--floating__label" for="date">
                {{ __('module/label.birthday') }}
            </label>
        </div>
        @endif
        @if($user->type->id == '2')
        <div
            class="form-floating c-input--floating__wrapper c-input--floating__wrapper-uploader">
            <label class="c-input__label">
                {{ __('module/label.logo') }}
            </label>
            <div class="c-uploader">
                <div class="c-uploader__trigger-wrapper">
                    <div class="c-uploader__trigger">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus') }}"></use>
                        </svg>
                        {{ __('module/label.choose_logo') }}
                    </div>
                    <input class="c-uploader__input" accept="image/jpeg, image/jpg" name="logo" type="file" value="Choose a file">
                </div>
                <div class="c-uploader__img-wrapper">
                    @if(!is_null($user->logo))
                    <img src="{{ secure_asset('/storage/user/'.$user->logo) }}" alt="your image" class="c-uploader__img d-block">
                    @else
                    <img alt="your image" class="c-uploader__img">
                    @endif
                </div>
            </div>
        </div>
        <div class="form-floating c-input--floating__wrapper c-input--floating__wrapper-uploader">
            <label class="c-input__label">
                {{ __('module/label.banner') }}
            </label>
            <div class="c-uploader c-uploader--cover">
                <div class="c-uploader__trigger-wrapper">
                    <div class="c-uploader__trigger">
                        <svg class="c-icon__svg c-icon--sm">
                            <use xlink:href="{{ secure_asset('site/assets/images/sprite.svg#plus') }}"></use>
                        </svg>
                        {{ __('module/label.choose_banner') }}
                    </div>
                    <input class="c-uploader__input" accept="image/jpeg, image/jpg"
                        name="background" type="file" value="Choose a file">
                </div>

                <div class="c-uploader__img-wrapper">
                    @if(!is_null($user->background))
                    <img src="{{ secure_asset('/storage/user/'.$user->background) }}" alt="your image" class="c-uploader__img d-block">
                    @else
                    <img alt="your image" class="c-uploader__img">
                    @endif
                </div>
            </div>
        </div>
        <div class="form-floating c-input--floating__wrapper" style="margin-top: 50px">
            <textarea class="form-control c-input c-input--floating summernote-en" id="desc" name="description">
                {!! $user->description !!}
            </textarea>
            <label class="c-input__label c-input--floating__label" for="desc" style="margin-top: -40px">
                {{ __('module/label.description') }}
            </label>
        </div>
        @endif
        <span class="c-light-tab__pane-footer">
            <button type="button" id="infoSaveBtn" class="c-button c-button--black c-button--sm c-button--md-w c-button--uppercase">{{ __('module/button.save') }}</button>
        </span>
    </form>
</div>
