@extends('frontend.layouts.app')

@include('phpsa-datastore::frontend.ams.includes.asset-common', $page)

@section('content')
    <div class="row mb-4">
        <div class="col">
			{{ $page->datastore->render('title') }}
			{{ $page->datastore->render('content') }}
        </div><!-- row -->
    </div><!-- row -->
@endsection
