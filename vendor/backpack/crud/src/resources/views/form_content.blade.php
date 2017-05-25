<form role="form">
  {{-- Show the erros, if any --}}
  @if ($errors->any())
  	<div class="callout callout-danger">
        <h4>{{ trans('validation.please_fix') }}</h4>
        <ul>
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	</div>
  @endif

  {{-- Show the inputs --}}
  @foreach ($crud['fields'] as $field)
    <!-- load the view from the application if it exists, otherwise load the one in the package -->
	@if(view()->exists('vendor.dick.crud.fields.'.$field['type']))
		@include('vendor.dick.crud.fields.'.$field['type'], array('field' => $field))
	@else
		@include('crud::fields.'.$field['type'], array('field' => $field))
	@endif
  @endforeach
</form>

{{-- For each form type, load its assets, if needed --}}
{{-- But only once per field type (no need to include the same css/js files multiple times on the same page) --}}
<?php
	$loaded_form_types_css = array();
	$loaded_form_types_js = array();
?>

@section('after_styles')
	<!-- FORM CONTENT CSS ASSETS -->
	@foreach ($crud['fields'] as $field)
		@if(!isset($loaded_form_types_css[$field['type']]) || $loaded_form_types_css[$field['type']]==false)
			@if (View::exists('vendor.dick.crud.fields.assets.css.'.$field['type'], array('field' => $field)))
				@include('vendor.dick.crud.fields.assets.css.'.$field['type'], array('field' => $field))
				<?php $loaded_form_types_css[$field['type']] = true; ?>
			@elseif (View::exists('crud::fields.assets.css.'.$field['type'], array('field' => $field)))
				@include('crud::fields.assets.css.'.$field['type'], array('field' => $field))
				<?php $loaded_form_types_css[$field['type']] = true; ?>
			@endif
		@endif
	@endforeach
@endsection

@section('after_scripts')
	<!-- FORM CONTENT JAVSCRIPT ASSETS -->
	@foreach ($crud['fields'] as $field)
		@if(!isset($loaded_form_types_js[$field['type']]) || $loaded_form_types_js[$field['type']]==false)
			@if (View::exists('vendor.dick.crud.fields.assets.js.'.$field['type'], array('field' => $field)))
				@include('vendor.dick.crud.fields.assets.js.'.$field['type'], array('field' => $field))
				<?php $loaded_form_types_js[$field['type']] = true; ?>
			@elseif (View::exists('crud::fields.assets.js.'.$field['type'], array('field' => $field)))
				@include('crud::fields.assets.js.'.$field['type'], array('field' => $field))
				<?php $loaded_form_types_js[$field['type']] = true; ?>
			@endif
		@endif
	@endforeach
@endsection