<?php

namespace Phpsa\Datastore\Repositories;


use App\Repositories\BaseRepository;
use Phpsa\Datastore\Models\Datastore as DatastoreModel;
use Illuminate\Database\Eloquent\Collection;
use Phpsa\Datastore\Datastore;
/**
 * Class PermissionRepository.
 */
class DatastoreRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return DatastoreModel::class;
	}

	public function paginateAssets($assetData, $limit = 25, $pageName = 'page', $page = null){
		$this->where('type', $assetData['class']);
		return $this->paginate($limit, ['*'], $pageName, $page);


		$models->transform(function ($item, $key) {
			return Datastore::get($item->id);
		});

		return $models;

	}

	    /**
     * @param int    $limit
     * @param array  $columns
     * @param string $pageName
     * @param null   $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
	}



}
