<div class="row form-group">
		<?php $key = isset($data['unique_id']) ? $data['key'] . '_' . $data['unique_id'] : $data['key'] ; ?>
		<?php
			if($data['value']){
				$record = config('auth.providers.users.model')::find($data['value']);

				$user_label = $record ? $record->first_name . ' ' . $record->last_name : '';
			}?>


			{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for($key) }}
			<div class="col-md-10">

				<?php $input = html()
				->text()
				->attribute('data-url', route('admin.ams.autocomplete.identity'))
				->attribute('data-limit', '1')
				->attribute('data-target', $key)
				->class('form-control autoinput');

				if($asset_classname){
					$name = 'assetInjectionform[' . $asset_classname . '][' . $data['unique_id'] . '][' . $data['key'] . ']';
				}else{
					$name = $data['key'];
				}

				$name .= (isset($multiform) && $multiform) ? '[]' : '';?>

				{{ $input->value(old($name, !empty($user_label) ? $user_label : NULL)) }}
				<input <?php echo $data['required'] ? 'required="required"' : ''; ?> class="asset_identity_id" id="{{ $key }}" type="hidden" name="{{ $name }}" value="{{ $data['value'] }}" />

				<?php if ($data['help']): ?>
					<small class="help-block form-text text-muted"><?php echo $data['help']; ?></small>
				<?php endif; ?>
			</div>
		</div>
