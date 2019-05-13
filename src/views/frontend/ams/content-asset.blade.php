@extends('frontend.layouts.app')

@include('phpsa-datastore::frontend.ams.includes.asset-common', $page)

@section('content')
    <div class="row mb-4">
        <div class="col">
			{{ $datastore->render('title') }}
			{{ $datastore->render('content') }}

			@include('phpsa-datastore::frontend.ams.includes.comments', ['comments' => $datastore->comments, 'datastore_id' => $datastore->id])
			<hr />


			{{ html()->form('POST', route('frontend.ams.comments.store'))->class('form-horizontal')->open() }}
			<div class="row form_group mb-3">
				{{ html()->label('Add Comment')->class('col-md-12 form-control-label')->for('commentBody') }}
				<input type="hidden" name="datastore_id" value="{{ $datastore->id }}" />
				<div class="col-md-12">

					{{ html()
					->textarea('body')
					->placeholder('Enter your comment here')
					->attribute('id', 'commentBody')
					->class('form-control')
					->required()
					}}
				</div>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-success" value="Add Comment" />
			</div>
			{{ html()->form()->close() }}



        </div><!-- row -->
    </div><!-- row -->
@endsection
