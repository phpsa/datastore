<div class="row form-group">
	<?php $key = isset($data['unique_id']) ? $data['key'] . '_' . $data['unique_id'] : $data['key'] ; ?>
		{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for($key) }}
		<div class="col-md-10">
			<?php
			if($asset_classname){
				$name = 'assetInjectionform[' . $asset_classname . '][' . $data['unique_id'] . '][' . $data['key'] . ']';
			}else{
				$name = $data['key'];
			}

			$name .= (isset($multiform) && $multiform) ? '[]' : '';
			$src = asset('vendor/phpsa-datastore/thumbs/' . old($name, !empty($data['value']) ? $data['value'] : 'noimage.jpg'));
		//	dd(asset($value));
			?>
				<div class="card ams-image-upload-card">
					<img data-rel="{{$key}}_file" data-placeholder="{{ asset('vendor/phpsa-datastore/thumbs/noimage.jpg') }}" src="{{ $src }}" class="card-img-top img-fluid" />
					<progress data-for="{{$key}}_file"></progress>
					<div class="btn-group" role="group" >
							<button class="btn btn-primary ams-upload-button" data-target="{{ $key }}" type="button" value="Upload" type="button"><i class="fa fa-upload"></i></button>
							<button class="btn btn-danger ams-upload-clear-button" data-target="{{ $key }}" type="button" value="Upload" type="button"><i class="fa fa-trash"></i></button>
					</div>

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
			?>

			{{ $input->type('hidden')->name($name)->value(old($name, !empty($data['value']) ? $data['value'] : NULL)) }}



			<input class="ams-image" id="{{ $key }}" accept="{{ $data['accept'] ? $data['accept'] : 'image/x-png,image/gif,image/jpeg,image/jpg' }}" type="file" style="display:none"  />

			<?php if ($data['help']): ?>
				<small class="help-block form-text text-muted"><?php echo $data['help']; ?></small>
			<?php endif; ?>
		</div>
	</div>
