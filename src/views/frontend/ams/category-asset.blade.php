@extends('frontend.layouts.app')

@include('phpsa-datastore::frontend.ams.includes.asset-common', $page)

@section('content')
    <div class="row mb-4">
        <div class="col blog-main">
			<h2>{{ $datastore->prop('category') }}</h2>
@foreach($accepted as $article)

<div class="blog-post">
	<h2 class="blog-post-title">{{ $article->item->prop('title') }}</h2>
	<p class="blog-post-meta">{{ $article->datastore->created_at->format("M d, Y") }} by <a href="#">{{ $article->item->render('author') }}</a></p>

	<p>{{ $article->item->prop('intro') }}</p>
	<p class="text-right"><a href="{{$article->datastore->routeChild($page->slug)}}">Read</a></p>

  </div>

@endforeach;

			{{ $accepted->render()}}
        </div><!-- row -->
    </div><!-- row -->
@endsection
