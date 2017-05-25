<!-- html5 url input -->
  <div class="form-group">
    <label>{{ $field['label'] }}</label>
    <input
    	type="url"
    	class="form-control"

    	@foreach ($field as $attribute => $value)
    		{{ $attribute }}="{{ $value }}"
    	@endforeach
    	>
  </div>