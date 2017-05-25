{{--
 * LaraClassified - Geo Classified Ads CMS
 * Copyright (c) Mayeul Akpovi. All Rights Reserved
 *
 * Email: mayeul.a@larapen.com
 * Website: http://larapen.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}
@extends('classified.layouts.master')
@section('content')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (isset($errors) and count($errors) > 0)
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>Oops ! An error has occurred. Please correct the red fields in the form</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				@if (Session::has('flash_notification.message'))
					<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-md-8 page-content">
					<div class="inner-box category-content">
						<h2 class="title-2"><strong> <i class="icon-user-add"></i> {{ t('Create your account, Its free') }}</strong></h2>
						<div class="row">
							<div class="col-sm-12">
								<form id="signup_form" class="form-horizontal" method="POST" action="{{ lurl('signup-post') }}">
									{!! csrf_field() !!}
									<fieldset>
										<!-- Gender -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('gender')) ? 'has-error' : ''; ?>">
											<label class="col-md-4 control-label">{{ t('Gender') }} <sup>*</sup></label>
											<div class="col-md-6">
												<select name="gender" id="gender" class="form-control selecter">
													<option value="0"
															@if(old('gender')=='' or old('gender')==0)selected="selected"@endif> {{ t('Select') }} </option>
													@foreach ($genders as $gender)
														<option value="{{ $gender->tid }}" @if(old('gender')==$gender->tid)selected="selected"@endif>
															{{ $gender->name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>

										<!-- First Name -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('name')) ? 'has-error' : ''; ?>">
											<label class="col-md-4 control-label">{{ t('Name') }} <sup>*</sup></label>
											<div class="col-md-6">
												<input name="name" placeholder="{{ t('Name') }}" class="form-control input-md" type="text"
													   value="{{ old('name') }}">
											</div>
										</div>

										<!-- User Type -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('user_type')) ? 'has-error' : ''; ?>">
											<label class="col-md-4 control-label">{{ t('You are a') }} <sup>*</sup></label>
											<div class="col-md-6">
												@foreach ($userTypes as $type)
													<label class="radio-inline" for="user_type-{{ $type->id }}">
														<input type="radio" name="user_type" id="user_type-{{ $type->id }}"
															   value="{{ $type->id }}" {{ (old('user_type')==$type->id) ? 'checked="checked"' : '' }}>
														{{ t('' . $type->name) }}
													</label>
												@endforeach
											</div>
										</div>

										<!-- Country -->
										<?php /*@if (!$ip_country)*/ ?>
										<div class="form-group required <?php echo (isset($errors) and $errors->has('country')) ? 'has-error' : ''; ?>">
											<label class="col-md-4 control-label" for="country">{{ t('Your Country') }} <sup>*</sup></label>
											<div class="col-md-6">
												<select id="country" name="country" class="form-control sselecter">
													<option value="0" {{ (!old('country') or old('country')==0) ? 'selected="selected"' : '' }}>{{ t('Select') }}</option>
													@foreach ($countries as $code => $item)
														<option value="{{ $code }}" {{ (old('country', (!$country->isEmpty()) ? $country->get('code') : 0)==$code) ? 'selected="selected"' : '' }}>
															{{ $item->get('name') }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
										<?php /*@else
										<input id="country" name="country" type="hidden" value="{{ $country->get('code') }}">
									@endif*/ ?>

												<!-- Phone Number -->
										<div class="form-group required <?php echo (isset($errors) and $errors->has('phone')) ? 'has-error' : ''; ?>">
											<label class="col-md-4 control-label">{{ t('Phone') }} <sup>*</sup></label>
											<div class="col-md-6">
												<div class="input-group"><span id="phone_country" class="input-group-addon"><i class="icon-mail"></i></span>
													<input name="phone" placeholder="{{ t('Phone Number') }}" class="form-control input-md"
														   type="text" value="{{ old('phone') }}">
												</div>
											</div>
										</div>

										<div class="form-group required <?php echo (isset($errors) and $errors->has('email')) ? 'has-error' : ''; ?>">
											<label class="col-md-4 control-label" for="email">{{ t('Email') }} <sup>*</sup></label>
											<div class="col-md-6">
												<div class="input-group">
													<span class="input-group-addon"><i class="icon-mail"></i></span>
													<input id="email" name="email" type="email" class="form-control" placeholder="{{ t('Email') }}"
														   value="{{ old('email') }}">
												</div>
											</div>
										</div>

										<div class="form-group required <?php echo (isset($errors) and $errors->has('password')) ? 'has-error' : ''; ?>">
											<label class="col-md-4 control-label" for="password">{{ t('Password') }} <sup>*</sup></label>
											<div class="col-md-6">
												<input id="password" name="password" type="password" class="form-control"
													   placeholder="{{ trans('Password') }}">
												<br>
												<input id="password_confirmation" name="password_confirmation" type="password" class="form-control"
													   placeholder="{{ t('Password Confirmation') }}">
												<p class="help-block">{{ t('At least 5 characters') }}</p>
											</div>
										</div>

										@if (config('settings.activation_recaptcha'))
											<div class="form-group required <?php echo (isset($errors) and $errors->has('g-recaptcha-response')) ? 'has-error' : ''; ?>">
												<label class="col-md-4 control-label" for="g-recaptcha-response"></label>
												<div class="col-md-6">
													{!! Recaptcha::render(['lang' => $lang->get('abbr')]) !!}
												</div>
											</div>
										@endif

										<div class="form-group required <?php echo (isset($errors) and $errors->has('term')) ? 'has-error' : ''; ?>"
											 style="margin-top: -10px;">
											<label class="col-md-4 control-label"></label>
											<div class="col-md-8">
												<div class="termbox mb10">
													<label class="checkbox-inline" for="term">
														<input name="term" id="term" value="1"
															   type="checkbox" {{ (old('term')=='1') ? 'checked="checked"' : '' }}>
														{!! t('I have read and agree to the <a href=":url">Terms & Conditions</a>', ['url' => lurl(trans('routes.terms'))]) !!}
													</label>
												</div>
												<div style="clear:both"></div>
											</div>
										</div>

										<!-- Button  -->
										<div class="form-group">
											<label class="col-md-4 control-label"></label>
											<div class="col-md-8">
												<button id="signup_btn" class="btn btn-success btn-lg"> {{ t('Register') }} </button>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 reg-sidebar">
					<div class="reg-sidebar-inner text-center">
						<div class="promo-text-box"><i class=" icon-picture fa fa-4x icon-color-1"></i>
							<h3><strong>{{ t('Post a Free Classified') }}</strong></h3>
							<p>
								{{ t('Do you have something to sell, to rent, any service to offer or a job offer? Post it at :app_name, its free, local, easy, reliable and super fast!',
								['app_name' => getDomain()]) }}
							</p>
						</div>
						<div class="promo-text-box"><i class=" icon-pencil-circled fa fa-4x icon-color-2"></i>
							<h3><strong>{{ t('Create and Manage Items') }}</strong></h3>
							<p>{{ t('Become a best seller or buyer. Create and Manage your ads. Repost your old ads, etc.') }}</p>
						</div>
						<div class="promo-text-box"><i class="  icon-heart-2 fa fa-4x icon-color-3"></i>
							<h3><strong>{{ t('Create your Favorite ads list.') }}</strong></h3>
							<p>{{ t('Create your Favorite ads list, and save your searchs. Don\'t forget any deal!') }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	@parent
	<script language="javascript">
		$(document).ready(function () {
			var countries = <?php echo (isset($countries)) ? $countries->toJson() : '{}'; ?>;
			var countryCode = '<?php echo old('country', ($country) ? $country->get('code') : 0); ?>';

			/* Set Country Phone Code */
			setCountryPhoneCode(countryCode, countries);
			$('#country').change(function () {
				setCountryPhoneCode($(this).val(), countries);
			});

			/* Submit Form */
			$("#signup_btn").click(function () {
				$("#signup_form").submit();
				return false;
			});
		});
	</script>
@endsection