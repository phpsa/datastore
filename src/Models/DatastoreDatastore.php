<?php

namespace Phpsa\Datastore\Models;

use Illuminate\Database\Eloquent\Model;

class DatastoreDatastore extends Model
{
	//
	protected $table = 'datastore_datastore';

	public $timestamps = false;


	protected $fillable = [
		'datastore_id',
		'datastore2_id'
	];
}
