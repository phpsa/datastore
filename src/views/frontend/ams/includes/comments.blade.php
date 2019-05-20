@include('phpsa-datastore::frontend.ams.includes.comment', ['comments' => $datastore->comments, 'datastore_id' => $datastore->id, 'level' => 0])

<hr />


			{{ html()->form('POST', route('frontend.ams.comments.store'))->class('form-horizontal')->open() }}
			<div class="row form-group">
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