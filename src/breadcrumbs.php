<?php

Breadcrumbs::for('admin.ams.content.list', function ($trail, $asset) {
    $trail->parent('admin.dashboard');
	$trail->push(__('phpsa-datastore::backend.menus.sidebar.main'), route('admin.ams.content.list', $asset));
});

Breadcrumbs::for('admin.ams.content.create', function ($trail, $asset) {
    $trail->parent('admin.dashboard');
	$trail->push(__('phpsa-datastore::backend.menus.sidebar.main'), route('admin.ams.content.list', $asset));
	$trail->push(__('phpsa-datastore::backend.menus.sidebar.create'));
});

Breadcrumbs::for('admin.ams.content.update', function ($trail, $asset) {
    $trail->parent('admin.dashboard');
	$trail->push(__('phpsa-datastore::backend.menus.sidebar.main'), route('admin.ams.content.list', $asset));
	$trail->push(__('phpsa-datastore::backend.menus.sidebar.update'));
});