@extends('frontend.layouts.app')

@include('phpsa-datastore::frontend.ams.includes.asset-common', $page)

@section('content')
    <div class="row mb-4">
        <div class="col">
			<div class="blog-post">
				<h2 class="blog-post-title">{{ $datastore->prop('title') }}</h2>
				<p class="blog-post-meta">{{ $datastore->created_at->format("M d, Y") }} by <a href="{{ route('frontend.ams.article.byauthor', ['id' => $datastore->prop('author'), 'slug' => Str::slug( $datastore->render('author') ) ]) }}">{{ $datastore->render('author') }}</a></p>

				<p>{{ $datastore->prop('intro') }}</p>
				<hr>
				{{ $datastore->prop('content')}}
				<hr>
				<p class="blog-post-meta">Categoriesed in: {{$datastore->id}}
@foreach($datastore->parents as $articleCategory)

			<a href="{{ $articleCategory->datastore->route }}">{{$articleCategory->datastore->prop('category')}}</a>

@endforeach;</p>
			  </div>


			@include('phpsa-datastore::frontend.ams.includes.comments', ['datastore' => $datastore])


        </div><!-- row -->
    </div><!-- row -->
@endsection
