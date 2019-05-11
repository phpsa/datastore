@can('manage datastore')
<li class="nav-item nav-dropdown {{
	active_class(Active::checkUriPattern('admin/ams/*'), 'open')
}}">
	<a class="nav-link nav-dropdown-toggle {{
		active_class(Active::checkUriPattern('admin/ams/*'))
	}}" href="#">
		<i class="nav-icon fas fa-list"></i>
		@lang('phpsa-datastore::backend.menus.sidebar.main')
	</a>
	<ul class="nav-dropdown-items">
			@forDatastores()
				@if($datastoreKey)
				<li class="nav-item nav-dropdown {{
					active_class(Active::checkUriPattern('admin/ams/' . $datastoreKey . '*'), 'open')
				}}">
					<a class="nav-link nav-dropdown-toggle {{
						active_class(Active::checkUriPattern('admin/ams/' . $datastoreKey . '*'))
					}}" href="#">
						<i class="nav-icon fas fa-list"></i>
						<?php echo ucwords($datastoreKey); ?>
					</a>
					<ul class="nav-dropdown-items">
				@endif

				@foreach ($datastoreData as $ksk => $dsa)
				<?php
					$link = strtolower($datastoreKey ? $datastoreKey .'.' . $ksk : $ksk);
					$route = 'admin.ams.content.list';

					?>
					<li class="nav-item">
						<a class="nav-link
							{{active_class(Active::checkUriPattern('admin/ams/content*'))
						}}" href="{{ route($route, $link) }}">
							{{ $dsa['name'] }}
						</a>
					</li>
				@endforeach

				@if($datastoreKey)
					</ul>
				@endif


			@endforDatastores
	</ul>
</li>
@endcan