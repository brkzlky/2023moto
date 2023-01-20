<div class="row">
    <form class="col-12 col-md-12 col-lg-5 col-xl-4" method="post" action="{{ route('member.profile_pw_change') }}">
        @csrf
        <div class="form-floating c-input--floating__wrapper">
            <input type="password" class="form-control c-input c-input--floating" name="old_password" id="password" value="" placeholder="{{ __('module/label.old_password') }}" required>

            <label class="c-input__label c-input--floating__label" for="password">{{ __('module/label.old_password') }}</label>
        </div>
        <div class="form-floating c-input--floating__wrapper">
            <input type="password" class="form-control c-input c-input--floating" name="new_password" id="password" value="" placeholder="{{ __('module/label.new_password') }}" required>

            <label class="c-input__label c-input--floating__label" for="password">{{ __('module/label.new_password') }}</label>
        </div>
        <div class="form-floating c-input--floating__wrapper">
            <input type="password" class="form-control c-input c-input--floating" name="new_password_confirmation" id="password" value="" placeholder="{{ __('module/label.new_password_confirm') }}" required>

            <label class="c-input__label c-input--floating__label" for="password">{{ __('module/label.new_password_confirm') }}</label>
        </div>
        <span class="c-light-tab__pane-footer">
            <button class="c-button c-button--black c-button--sm c-button--md-w c-button--uppercase">{{ __('module/button.save') }}</button>
        </span>
    </form>
</div>