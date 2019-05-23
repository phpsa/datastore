<div class="row form-group">
	<?php
	$key = isset($data['unique_id']) ? $data['key'] . '_' . $data['unique_id'] : $data['key'] ;
	$user_label = ($data['meta']) ? explode('|', $data['meta']) : array('', '');
	$options = !empty($data['options']) ? $data['options'] : [];
	if($asset_classname){
		$name = 'assetInjectionform[' . $asset_classname . '][' . $data['unique_id'] . '][' . $data['key'] . ']';
		$nameMeta = "assetInjectionform[{$asset_classname}][{$data['unique_id']}][_meta_][{$data['key']}]";
	}else{
		$name = $data['key'];
		$nameMeta = "_meta_[{$data['key']}]";
	}
	$name .= (isset($multiform) && $multiform) ? '[]' : '';
	$callback = is_array($data['callback']) ? $data['callback'] : ['url' => $data['callback']];
?>

		{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for($key) }}
		<div class="col-md-3" id="aw_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>">
				<select class="form-control asset_callback_selector" id="asset_callback_selector_<?php echo $data['key']; ?>">

						@foreach($options as $optionKey => $optionValue)
						<option value={{  $optionKey }} @if($optionKey === $user_label[0])selected="selected"@endif>{{ $optionValue }}</option>
						@endforeach
					</select>
		</div>
		<div class="col-md-7">
			{{ html()->text()->attribute('data-url', $callback['url'])->attribute('data-type',!empty($callback['method']) ? $callback['method'] : 'GET')->class('form-control asset_autocallback')->value($user_label[1]) }}
			<input class="asset_autocallback_id" id="asset_{{$key }}" type="hidden" <?php echo ($data['required']) ? 'required="required"' : '';?> name="{{ $name }}" value="{{ old($name, $data['value']) }}" />
			<input class="asset_autocallback_meta" id="asset_meta_{{$key }}" type="hidden" name="{{ $nameMeta }}" value="{{ old($nameMeta, $data['meta']) }}" />

		</div>
		<?php if ($data['help']): ?>
		<div class="col-md-10 offset-md-2">
				<small class="help-block form-text text-muted"><?php echo $data['help']; ?></small>
			</div>
			<?php endif; ?>
	</div>
