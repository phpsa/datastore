@extends('frontend.layouts.app')

@include('phpsa-datastore::frontend.ams.includes.asset-common', $page)

@section('content')
    <div class="row mb-4">
        <div class="col">
			{{ $datastore->render('title') }}
			{{ $datastore->render('content') }}

			@include('phpsa-datastore::frontend.ams.includes.comments', ['datastore' => $datastore])


        </div><!-- row -->
    </div><!-- row -->
@endsection
