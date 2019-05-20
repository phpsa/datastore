@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('phpsa-datastore::backend.titles.list'))



@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{ __('phpsa-datastore::backend.labels.content.list') }} <small class="text-muted">{{ $asset['name'] }}</small>
				</h4>

            </div><!--col-->

            <div class="col-sm-7">
				<?php if($asset['max_instances'] == 0 || $content->total() < $asset['max_instances']): ?>
				@include('phpsa-datastore::backend.ams.includes.header-buttons')
			<?php endif; ?>
			</div><!--col-->
			<?php if($asset['about']): ?>
			<div class="col-sm-11">
				<div class="alert alert-info">{{ $asset['about'] }}</div>
			</div>
		<?php endif; ?>
        </div><!--row-->



        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
							<th>@lang('phpsa-datastore::backend.labels.asset.title')</th>
							@if(!$asset['private'])
							<th>@lang('phpsa-datastore::backend.labels.asset.link')</th>
							@endif
                            <th>@lang('phpsa-datastore::backend.labels.asset.created')</th>
							<th>@lang('phpsa-datastore::backend.labels.asset.updated')</th>
							@if($asset['status_equals'])
							<th>@lang('phpsa-datastore::backend.labels.asset.status')</th>
							@endif
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($content as $contentItem)
                            <tr>
								<td>

									{{ $contentItem->datastore }}
								</td>
								@if(!$asset['private'])
								<td>
									@if($contentItem->page)
									<a href="{{ $contentItem->route }}" target="_blank">{{ $contentItem->route }}</a>
									@endif
								</td>
								@endif
								<td>
									{{ $contentItem->created_at->diffForHumans()}}
								</td>
								<td>
										{{ $contentItem->updated_at->diffForHumans()}}
									</td>
									@if($asset['status_equals'])
									<td>
										{{ $contentItem->datastore->getStatusValue() }}
									</td>
									@endif
									<td>
											{!! $contentItem->action_buttons !!}
									</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {!! $content->total() !!} {{ trans_choice('phpsa-datastore::backend.labels.assets.table.total', $content->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $content->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
