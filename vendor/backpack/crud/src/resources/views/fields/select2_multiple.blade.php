<!-- select2 multiple -->
  <div class="form-group">
    <label>{{ $field['label'] }}</label>
    <select
    	class="form-control select2"

    	@foreach ($field as $attribute => $value)
    		@if ($attribute=='name')
    			{{ $attribute }}="{{ $value }}[]"
    		@else
    			{{ $attribute }}="{{ $value }}"
    		@endif
    	@endforeach
    	multiple>
    	<option value="">-</option>

	    	@if (isset($field['model']))
	    		@foreach ($field['model']::all() as $connected_entity_entry)
	    			<option value="{{ $connected_entity_entry->id }}"
						@if (isset($field['value']) && in_array($connected_entity_entry->id, $field['value']->lists('id', 'id')->toArray()))
							 selected
						@endif
	    			>{{ $connected_entity_entry->$field['attribute'] }}</option>
	    		@endforeach
	    	@endif
	</select>
  </div>