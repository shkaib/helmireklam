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

				@if (Session::has('flash_notification.message'))
					<div class="container" style="margin-bottom: -10px; margin-top: -10px;">
						<div class="row">
							<div class="col-lg-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-md-12 page-content">

					@if(session('success'))
						<div class="inner-box category-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-success pgray  alert-lg" role="alert">
										<h2 class="no-margin no-padding">&#10004; {{ t('Congratulations!') }}</h2>
										<p>{{ session('message') }}</p>
									</div>
								</div>
							</div>
						</div>
					@endif

				</div>
			</div>
		</div>
	</div>
@endsection

@section('javascript')
	@parent
@endsection
