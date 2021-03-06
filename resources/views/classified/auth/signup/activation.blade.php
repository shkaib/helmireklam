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
				<div class="col-md-12 page-content">

					@if ($error==0)
						<div class="inner-box category-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-success pgray  alert-lg" role="alert">
										<h2 class="no-margin no-padding">&#10004; {{ t('Congratulations!') }}</h2>
										<p>{{ $message }}</p>
									</div>
								</div>
							</div>
						</div>
					@else
						<div class="inner-box category-content">
							<div class="row">
								<div class="col-lg-12">
									<div class="alert alert-danger pgray  alert-lg" role="alert">
										<h2 class="no-margin no-padding">&#10004; {{ t('Oops!') }}</h2>
										<p>{{ $message }}</p>
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
