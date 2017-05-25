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

				@if (count($errors) > 0)
					<div class="col-lg-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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

				<div class="col-sm-5 login-box">
					<div class="panel panel-default">
						<div class="panel-intro text-center">
							<h2 class="logo-title">
								<span class="logo-icon"> </span> {{ t('Reset Password') }} <span> </span>
							</h2>
						</div>
						<div class="panel-body">
							<form method="POST" action="{{ lurl('password/reset') }}">
								{!! csrf_field() !!}
								<input type="hidden" name="token" value="{{ $token }}">

								<div class="form-group <?php echo ($errors->has('email')) ? 'has-error' : ''; ?>">
									<label for="email" class="control-label">{{ t('Email Address') }}:</label>
									<input type="email" name="email" value="{{ old('email') }}" placeholder="{{ t('Email Address') }}"
										   class="form-control email">
								</div>

								<div class="form-group <?php echo ($errors->has('password')) ? 'has-error' : ''; ?>">
									<label for="password" class="control-label">{{ t('Password') }}:</label>
									<input type="password" name="password" placeholder="" class="form-control email">
								</div>

								<div class="form-group <?php echo ($errors->has('password_confirmation')) ? 'has-error' : ''; ?>">
									<label for="password_confirmation" class="control-label">{{ t('Password Confirmation') }}:</label>
									<input type="password" name="password_confirmation" placeholder="" class="form-control email">
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-lg btn-block">{{ t('Reset the Password') }}</button>
								</div>
							</form>
						</div>
						<div class="panel-footer">
							<p class="text-center "><a href="<?php echo lurl(trans('routes.login')); ?>"> {{ t('Back to Login') }} </a></p>
							<div style=" clear:both"></div>
						</div>
					</div>
					<div class="login-box-btm text-center">
						<p> {{ t('Don\'t have an account?') }} <br>
							<a href="<?php echo lurl(trans('routes.signup')); ?>"><strong>{{ t('Sign Up !') }}</strong> </a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	@parent
	<script src="{{ url('assets/js/form-validation.js') }}"></script>

	<script language="javascript">
		$(document).ready(function () {
			$("#pwdBtn").click(function () {
				$("#pwdForm").submit();
				return false;
			});
		});
	</script>
@endsection