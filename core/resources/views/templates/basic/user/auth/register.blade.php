@extends($activeTemplate.'layouts.frontend')
@section('content')

    @include($activeTemplate.'partials.authentication')
                        <div class="account-header text-center">
                            <h3 class="title">@lang('Create your account')</h3>
                        </div>
                        <form class="account-form verify-gcaptcha" action="{{ route('user.register') }}" method="POST">
                            @csrf
                            <div class="row ml-b-20">
                                @if(session()->get('reference') != null)
                                    <div class="form-group">
                                        <label>@lang('Reference By')</label>
                                        <input type="text" class="form-control form--control" name="referBy" value="{{session()->get('reference')}}" readonly>
                                    </div>
                                @endif

                                <div class="col-lg-6 form-group">
                                    <label>@lang('Username')</label>
                                    <input type="text" class="form-control form--control checkUser" name="username" value="{{old('username')}}" required>
                                    <small class="text--danger usernameExist"></small>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>@lang('E-Mail Address')</label>
                                    <input type="text" class="form-control form--control checkUser" name="email" value="{{old('email')}}" required>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>@lang('Country')</label>
                                    <select name="country" class="form-control form--control" required>
                                        @foreach($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>@lang('Mobile')</label>
                                    <div class="input-group country-code">
                                        <span class="input-group-text mobile-code"></span>
                                        <input type="number" name="mobile" value="{{ old('mobile') }}" class="form-control form--control checkUser" required>
                                        <input type="hidden" name="mobile_code">
                                        <input type="hidden" name="country_code">
                                    </div>
                                    <small class="text--danger mobileExist"></small>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>@lang('Password')</label>
                                    <input type="password" class="form-control form--control" name="password" required>
                                    @include($activeTemplate.'partials.secure_password')
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>@lang('Confirm Password')</label>
                                    <input type="password" class="form-control form--control" name="password_confirmation" required>
                                </div>

                                <x-captcha />

                                @if($general->agree)
                                    <div class="col-lg-12 form-group">
                                        <div class="form-group custom-check-group">
                                            <input type="checkbox" name="agree" id="agree">
                                            <label for="agree">@lang('I agree with') <span>@foreach($policyPages as $policy) <a class="text--base" href="{{ route('policy.pages',[slug($policy->data_values->title),$policy->id]) }}">{{ __($policy->data_values->title) }}</a> @if(!$loop->last), @endif @endforeach</span></label>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <button type="submit" class="submit-btn w-100">@lang('Register Now')</button>
                                </div>

                                <div class="col-lg-12 text-center">
                                    <div class="account-item mt-10">
                                        <label>@lang('Already Have An Account')? <a href="{{route('user.login')}}" class="text--base">@lang('Sign In')</a></label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <div class="modal-body">
                <p class="text-center">@lang('You already have an account please Login ')</p>
            </div>
            <div class="modal-footer">
                <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
            </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
      "use strict";

        (function ($) {
            @if($mobileCode)
            $(`option[data-code={{ $mobileCode }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                  if (response.data != false && response.type == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response.data != false){
                    $(`.${response.type}Exist`).text(`${response.type} already exist`);
                  }else{
                    $(`.${response.type}Exist`).text('');
                  }
                });
            });
        })(jQuery);
    </script>
@endpush
