@section('title', app_name() . ' | ' . $page->title)

@section('meta_description', $page->datastore->meta_description)
@if($page->datastore->meta_keywords )
@section('meta')
<meta name="keywords" content="{{ $page->datastore->meta_keywords }} ">
@endsection
@endif

@if($page->datastore->page_css)
@push('after-styles')
<style>
{{ $page->datastore->page_css }}
</style>
@endpush
@endif

@if($page->datastore->page_js)
@push('after-scripts')
<script>
{!! $page->datastore->page_js !!}
</script>
@endpush
@endif