<?php
	$user_labels = is_array($data['meta']) ?  $data['meta'] : [];
	$options = !empty($data['options']) ? $data['options'] : [];

	$uid =  $data['key'] . $data['unique_id'];
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
<div class="row form-group awd_list_container" data-uid="{{ $uid }}" data-name="{{ $name }}">

		{{ html()->label($data['name'])->class('col-md-2 form-control-label')->for($data['key']) }}

		<div class="col-md-3" id="aw_{{ $uid }}">
			<select class="form-control asset_callback_selector" id="asset_callback_selector_{{ $data['key'] }}">
				@foreach($options as $optionKey => $optionValue)
				<option value={{  $optionKey }} >{{ $optionValue }}</option>
				@endforeach
			</select>
		</div>

		<div class="col-md-7">
			<div class="input-group mb-3">
					<input data-type="{{  !empty($callback['method']) ? $callback['method'] : 'GET' }}"" data-url={{ $callback['url'] }} class="asset_autocallback form-control" id="{{ $uid }}" type="text" value="" />
					<div class="input-group-append">
						<button data-target="{{ $uid }}" class="btn btn-outline-primary" type="button" id="addContent"><i class="fa fa-plus"></i></button>
					</div>
					<input class="asset_autocallback_id" id="ph_{{ $uid }}" type="hidden" value="{{ $data['value'] }}" />
					<input class="asset_autocallback_meta" id="ph_meta_{{ $uid }}" type="hidden" value="" />
					<input class="asset_autocallback_value" id="asset_{{ $uid }}" type="hidden" name="{{ $name }}" value="{{ $data['value'] }}" />

			</div>
		</div>
		@if ($data['help'])
			<div class="col-md-10 offset-md-2">
				<small class="help-block form-text text-muted">{{ $data['help'] }}</small>
			</div>
		@endif

		<div class="col-md-10 offset-md-2 " id="awd_{{ $uid }}">

			<div id="nt_{{ $uid }}" class="alert alert-warning information frmmsg"><b>No Content.</b> There is currently no content assigned. Select the desired content above and click the Add Content button.</div>
			<div id="nto_{{ $uid }}" class="alert alert-info notification note frmmsg"><b>Sort Content.</b> You can sort the items below by dragging and dropping. </div>

			<ol class="list-group sortableMenu ui-sortable" id="assetadder_{{ $uid }}">

					<?php
					//get page items
					if (isset($data['value'])) {
						//$items = db::batch(zpASSET, explode('|', $data['value']));

						$items = \Phpsa\Datastore\Datastore::find(explode('|', $data['value']));

						if ($items) {
							$i = 0;
							foreach ($items as $item) {
								?>
								<li id="list_'+n+'" class="sortableNode no-nest list-group-item">
									<i class="fas fa-arrows-alt handle"></i>
									<span data-val="{{ $item->val() }}" class="label">{{ $item->val() }}</span>
									<button type="button" class="btn-clear-sorted btn btn-danger btn-sm float-right"><i original-title="Remove Item" rel="tooltip" class="fa fa-trash"></i></button>
									<input type="hidden" class="idx" name="{{ $nameMeta }}[{{ $i }}]"  value="<?php echo $item->id; ?>" />
									<input type="hidden" id="asset_meta_{{ $uid }}" name="{{ $nameMeta }}[{{ $i }}]"  value="|<?php echo $item->val(); ?>" />

								</li>
								<?php
								++$i;
							}
						}
					}
					?>
				</ol>

		</div>

</div>




@push('after-scripts')

				<script type="text/javascript">


                    $(function() {


                        $("#addContent").click(function() {
							//ensure content has been selected
							var container = $(this).closest('.awd_list_container');
							var acInput = container.find('.asset_autocallback');
							var title = acInput.val();
							var id = container.find('.asset_autocallback_id').val();
							var meta = container.find('.asset_autocallback_meta').val();

							var uid = container.data('uid');
							var name = container.data('name');

                            if(id == '' || meta == '') {
                                return false;
                            }

                            var n = container.find(".idx").length;

							var content = '<li id="list_'+n+'" class="sortableNode no-nest list-group-item">';
								content += '<i class="fas fa-arrows-alt handle"></i>';
								content += ' <span data-val="' + title + '" class="label">'+title+'</span>'
								content += ' <button type="button" class="btn-clear-sorted btn btn-danger btn-sm float-right"><i original-title="Remove Item" rel="tooltip" class="fa fa-trash"></i></button>';
                            	content += ' <input type="hidden" class="idx" name="_meta_' + name + '[\'idx_'+n+'\']"  value="'+id+'" />';
                            	content += ' <input type="hidden" id="asset_meta_' + uid + '" name="_meta_[' + name + '][\'idx_'+n+'\']"  value="'+meta+'" />';
                            	content += '</li>';



							container.find('.sortableMenu').append(content);

                            //clear autocomplete
							$('#' + uid).val('');

							updateSortedList();

						});



                    });

                </script>
@endpush