@extends('frontend.layouts.app')

@include('phpsa-datastore::frontend.ams.includes.asset-common', $page)

@section('content')
    <div class="row mb-4">
        <div class="col">
			{{ $datastore->render('title') }}
			{{ $datastore->render('content') }}


			<?php if($children):?>
			<nav>
				<div class="nav nav-tabs" id="asset-tab" role="tablist">
					<?php foreach($children as $idx => $child): ?>
						<a class="nav-item nav-link<?php echo $idx === 0 ?' active' : '';?>" id="child-{{ $idx }}-tab" data-toggle="tab" href="#asset-{{ $idx }}" role="tab" aria-controls="asset-{{ $idx }}" aria-selected="true">{{ $child->prop('title') }}</a>
					<?php endforeach; ?>

				</div>
			</nav>
				  <div class="tab-content" id="asset-tabContent">
						<?php foreach($children as $idx => $child): ?>
						<div class="tab-pane fade<?php echo $idx === 0 ? ' show active' : ''; ?>" id="asset-{{ $idx }}" role="tabpanel" aria-labelledby="asset-{{ $idx }}-tab">
							{{ $child->prop('content')}}
						</div>
						<?php endforeach; ?>
				  </div>
				<?php endif; ?>
        </div><!-- row -->
    </div><!-- row -->
@endsection
