<?php

namespace Phpsa\Datastore\Models;

use Illuminate\Database\Eloquent\Model;

class DatastorePages extends Model
{
	protected $table = 'datastore_pages';
	//

	public $timestamps = false;

	protected $fillable = [
		'title',
		'slug',
		'asset'
	];
}
