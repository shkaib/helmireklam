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
	@if(view()->exists('vendor.backpack.crud.fields.'.$field['type']))
		@include('vendor.backpack.crud.fields.'.$field['type'], array('field' => $field))
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
			@if (View::exists('vendor.backpack.crud.fields.assets.css.'.$field['type'], array('field' => $field)))
				@include('vendor.backpack.crud.fields.assets.css.'.$field['type'], array('field' => $field))
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
			@if (View::exists('vendor.backpack.crud.fields.assets.js.'.$field['type'], array('field' => $field)))
				@include('vendor.backpack.crud.fields.assets.js.'.$field['type'], array('field' => $field))
				<?php $loaded_form_types_js[$field['type']] = true; ?>
			@elseif (View::exists('crud::fields.assets.js.'.$field['type'], array('field' => $field)))
				@include('crud::fields.assets.js.'.$field['type'], array('field' => $field))
				<?php $loaded_form_types_js[$field['type']] = true; ?>
			@endif
		@endif
	@endforeach


<script type="text/javascript">
	jQuery(document).ready(function($) {

		$.ajaxPrefilter(function(options, originalOptions, xhr) {
			var token = $('meta[name="csrf_token"]').attr('content');

			if (token) {
				return xhr.setRequestHeader('X-XSRF-TOKEN', token);
			}
		});

		// make the delete button work in the first result page
		register_delete_button_action();

		function register_delete_button_action() {
			$("[data-button-type=delete]").unbind('click');
			// CRUD Delete
			// ask for confirmation before deleting an item
			$("[data-button-type=delete]").click(function(e) {
				e.preventDefault();
				var delete_button = $(this);
				var delete_url = $(this).attr('href');

				if (confirm("{{ trans('backpack::crud.delete_confirm') }}") == true) {
					$.ajax({
						url: delete_url,
						type: 'DELETE',
						success: function(result) {
							// Show an alert with the result
							new PNotify({
								title: "{{ trans('backpack::crud.delete_confirmation_title') }}",
								text: "{{ trans('backpack::crud.delete_confirmation_message') }}",
								type: "success"
							});
							// delete the row from the table
							window.location.replace($("input[name=edit_url]").val());
							window.location.href = $("input[name=edit_url]").val();
						},
						error: function(result) {
							// Show an alert with the result
							new PNotify({
								title: "{{ trans('backpack::crud.delete_confirmation_not_title') }}",
								text: "{{ trans('backpack::crud.delete_confirmation_not_message') }}",
								type: "warning"
							});
						}
					});
				} else {
					new PNotify({
						title: "{{ trans('backpack::crud.delete_confirmation_not_deleted_title') }}",
						text: "{{ trans('backpack::crud.delete_confirmation_not_deleted_message') }}",
						type: "info"
					});
				}
			});
		}


	});
</script>
@endsection