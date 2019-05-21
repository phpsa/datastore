<div class="row form-group">
	<?php $key = isset($data['unique_id']) ? $data['key'] . '_' . $data['unique_id'] : $data['key'] ; ?>

		{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for($key) }}
		<div class="col-md-10">

			<?php $input = html()
			->text()
			->attribute('data-url', $data['callback'])
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


	<?php
    $user_label = ($data['meta']) ? explode('|', $data['meta']) : array('', '');
?>

<dt>
<label for="asset_<?php echo $data['key']; ?>"><?php echo $data['name']; ?></label>
</dt>
<dd id="aw_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>">


	<select id="asset_callback_selector_<?php echo $data['key']; ?>">

                    <?php
                    if ($data['options']):
                        $opts = explode(',', $data['options']);
                        foreach ($opts as $opt):
                            if (trim($opt) == strtolower($user_label[0])):
                                ?>
                                <option value="<?php echo trim($opt); ?>" selected="selected"><?php echo ucwords($opt); ?></option>
                            <?php else: ?>
                                <option value="<?php echo trim($opt); ?>"><?php echo ucwords($opt); ?></option>
                            <?php
                            endif;
                        endforeach;
                    endif;
                    ?>
                </select>



    <?php if ($asset_classname) : ?>
        <input class="asset_autocallback medium" type="text" value="<?php echo $user_label[1]; ?>" />
        <input class="asset_autocallback_id" id="asset_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>" type="hidden" <?php echo ($data['required']) ? 'required="required"' : '';?> name="assetInjectionform[<?php echo $asset_classname; ?>][<?php echo $data['unique_id']; ?>][<?php echo $data['key']; ?>]" value="<?php echo $data['value']; ?>" />
        <input class="asset_autocallback_meta" id="asset_meta_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>" type="hidden" name="assetInjectionform[<?php echo $asset_classname; ?>][<?php echo $data['unique_id']; ?>][_meta_][<?php echo $data['key']; ?>]" value="<?php echo $data['meta']; ?>" />
    <?php else: ?>
        <input class="asset_autocallback medium" type="text" value="<?php echo $user_label[1]; ?>" />
        <input class="asset_autocallback_id" id="asset_<?php echo $data['unique_id']; ?>_<?php echo $data['key']; ?>" <?php echo ($data['required']) ? 'required="required"' : '';?> type="hidden" name="<?php echo $data['key']; ?>" value="<?php echo $data['value']; ?>" />
        <input class="asset_autocallback_meta" id="asset_meta_<?php echo $data['unique_id']; ?>_<?php echo $data['key']; ?>" type="hidden" name="_meta_[<?php echo $data['key']; ?>]" value="<?php echo $data['meta']; ?>" />
    <?php endif; ?>
    <?php if ($data['help']): ?>
        <p><?php echo $data['help']; ?></p>
    <?php endif; ?>
</dd>
<?php $callback = explode('::', $data['callback']);?>

@push('after-scripts')
<script type="text/javascript">
    $(function() {
        $('#asset_callback_selector_<?php echo $data['key']; ?>', "#aw_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>").change(function() {
            $( ".asset_autocallback", "#aw_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>").val('');
        });

        $( ".asset_autocallback" ).autoinput({
            source: function( request, response ) {
                $.getJSON( '/admin/ams/gettypedata?q=' + encodeURIComponent($('#asset_callback_selector_<?php echo $data['key']; ?>', "#aw_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>").val()), request, function( data, status, xhr ) {
                    response( data );
                });
            },
            focus: function( event, ui ) {
                $( this ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $( this ).val( ui.item.label );
                $( this ).siblings('.asset_autocallback_id').val( ui.item.value );
                $( this ).siblings('.asset_autocallback_meta').val( $('#asset_callback_selector_<?php echo $data['key']; ?>', "#aw_<?php echo $data['key']; ?><?php echo $data['unique_id']; ?>").val() + '|' + ui.item.label );
                return false;
            }
        });
    });
</script>
@endpush