<?php

namespace Phpsa\Datastore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DatastoreComments extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'datastore_id', 'parent_id', 'body'];

    /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user()
    {
		$user = config('auth.providers.users.model');
        return $this->belongsTo($user);
    }

    /**
     * The has Many Relationship
     *
     * @var array
     */
    public function replies()
    {
        return $this->hasMany(DatastoreComments::class, 'parent_id');
    }

}