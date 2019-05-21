<div class="row form-group">
	<?php $key = isset($data['unique_id']) ? $data['key'] . '_' . $data['unique_id'] : $data['key'] ; ?>

		{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for($key) }}
		<div class="col-md-10">
<?php
$callback = is_array($data['callback']) ? $data['callback'] : ['url' => $data['callback']];
	?>
			<?php $input = html()
			->text()
			->attribute('data-url', $callback['url'])
			->attribute('data-type', !empty($callback['method']) ? $callback['method'] : 'GET')
			->attribute('data-limit', '1')
			->attribute('id', $key)
			->class('form-control autoinput');

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
