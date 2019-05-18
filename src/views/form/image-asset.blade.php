<div class="row form-group">
	<?php $key = isset($data['unique_id']) ? $data['key'] . '_' . $data['unique_id'] : $data['key'] ; ?>
		{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for($key) }}
		<div class="col-md-10">

			<div class="input-group">
					<div class="input-group-prepend">
							<button class="btn btn-primary ams-upload-button" data-target="#{{ $key }}" type="button" value="Upload" type="button">Upload</button>
						</div>

			<?php $input = html()
			->text()
			->attribute('data-target', "#{$key}")
			->attribute('id', $key . '_file')
			->placeholder('Choose File')
			->class('form-control ams-upload-filename');
			if($data['required']){
				$input = $input->required();
			}
			if($asset_classname){
				$name = 'assetInjectionform[' . $asset_classname . '][' . $data['unique_id'] . '][' . $data['key'] . ']';
			}else{
				$name = $data['key'];
			}

			$name .= (isset($multiform) && $multiform) ? '[]' : '';?>

			{{ $input->name($name)->value(old($name, !empty($data['value']) ? $data['value'] : NULL)) }}

			</div>

			<input id="{{ $key }}" accept="{{ $data['accept'] ? $data['accept'] : 'image/x-png,image/gif,image/jpeg,image/jpg' }}" type="file" style="display:none"  />

			<?php if ($data['help']): ?>
				<small class="help-block form-text text-muted"><?php echo $data['help']; ?></small>
			<?php endif; ?>
		</div>
	</div>
