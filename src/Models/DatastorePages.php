<?php

namespace Phpsa\Datastore\Models;

use Phpsa\Datastore\Datastore;

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


	public function getDatastoreAttribute(){
		return $this->id ? Datastore::get($this->asset) : null;
	}

}
