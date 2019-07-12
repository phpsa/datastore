<?php

namespace Phpsa\Datastore\Models;

use Illuminate\Database\Eloquent\Model;
use Phpsa\Datastore\Models\Datastore as DatastoreModel;
/**
  * @property Object $item
  */

class DatastoreDatastore extends Model
{
	//
	protected $table = 'datastore_datastore';

	public $timestamps = false;


	protected $fillable = [
		'datastore_id',
		'datastore2_id'
	];


	public function parent()
    {
        return $this->belongsTo(DatastoreModel::class, 'datastore2_id');
	}

	public function page()
	{
		return $this->hasOne(DatastorePages::class, 'asset', 'datastore_id');
	}


	public function datastore(){
		return $this->belongsTo(DatastoreModel::class, 'datastore_id');
	}

	public function getItemAttribute(){
		return $this->datastore->datastore;
	}

	public function prop($key)
	{
		return $this->item->prop($key);
	}

}
