<?php

namespace Phpsa\Datastore\Models;

use Illuminate\Database\Eloquent\Model;
use Phpsa\Datastore\Datastore as DatastoreFactory;
use Phpsa\Datastore\Models\Traits\Attribute\DatastoreAttribute;
use Phpsa\Datastore\Models\DatastorePages;
use Phpsa\Datastore\Models\DatastoreComments;
use Phpsa\Datastore\Helpers;

class Datastore extends Model
{

	use DatastoreAttribute;

	protected $table = 'datastore';

	/**
     * @var array
     */
    protected $dates = [
        'start_date',
		'end_date',
		'updated_at'
	];


	public function properties()
    {
        return $this->hasMany(Datastore::class);
    }

	public function assets()
	{
		return $this->belongsTo(Datastore::class);
	}

	public function page()
	{
		return $this->hasOne(DatastorePages::class, 'asset');
	}

	/**
     * The has Many Relationship
     *
     * @var array
     */
    public function comments()
    {
        return $this->hasMany(DatastoreComments::class)->whereNull('parent_id');
    }

	public function getDatastoreAttribute(){
		if($this->id){
			return DatastoreFactory::getAsset($this->type, $this->id);
		}
		if($this->type){
			return DatastoreFactory::dispence($this->type);
		}
	}

	public function getRouteAttribute(){
		return $this->type ? Helpers::callStatic($this->type, 'route', [$this]) : null;
	}


}
