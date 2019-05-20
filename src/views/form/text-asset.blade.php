<div class="row form-group">

	{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for('asset_' . $data['key']) }}
	<div class="col-md-10">

		<?php $input = html()
		->textarea()
		->attribute('rows', 5)
		->attribute('cols', 40)
		->attribute('id', $data['key'])

		->class('form-control');
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

		<?php if ($data['help']): ?>
			<small class="help-block form-text text-muted"><?php echo $data['help']; ?></small>
		<?php endif; ?>
	</div>
</div>
