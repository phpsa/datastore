<div class="row form_group">
		{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for('asset_' . $data['key']) }}
		<div class="col-md-10">
			{{ html()->textarea($data['key'])
				->attribute('id', $data['key'])
				->attribute('rows', 5)
				->attribute('cols', 40)
				->value(old($data['name'], !empty($data['value']) ? $data['value'] : NULL))
				->class('form-control')
			 }}
			 	<?php if ($data['help']): ?>
					 <small class="help-block form-text text-muted"><?php echo $data['help']; ?></small>
				 <?php endif; ?>
		</div>
	</div>
