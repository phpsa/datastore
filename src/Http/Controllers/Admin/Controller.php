<?php

namespace Phpsa\Datastore\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Phpsa\Datastore\Datastore;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\Helpers;
use Phpsa\Datastore\DatastoreException;
use Phpsa\Datastore\Repositories\DatastoreRepository;
use Phpsa\Datastore\Models\Datastore as DatastoreModel;
use Phpsa\Datastore\Models\DatastorePages;
use Phpsa\Datastore\Models\DatastoreDatastore;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

Class Controller extends BaseController {


	/**
	 * @var DatastoreRepository
	 */
    protected $datastoreRepository;

    /**
     * UserController constructor.
     *
     * @param DatastoreRepository $datastoreRepository
     */
    public function __construct(DatastoreRepository $datastoreRepository)
    {
        $this->datastoreRepository = $datastoreRepository;
    }



	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function list($asset,Request $request){

		$assetData = Helpers::parseAssetType($asset);

		return view('phpsa-datastore::backend.ams.list')
		->withAsset($assetData)
		->withAssetType($asset)
		->withContent($this->datastoreRepository->paginateAssets($assetData, 25, 'page' ));

	}

	public function inject(Request $request){
		$assetData = Helpers::parseAssetType($request->input('asset'), true);
		$newAsset = Datastore::getAsset($assetData);
		return response($newAsset->injectForm($request->input('idx')));
	}


	protected function _getKids($asset){
		$children = [];
		if ( ! empty($asset->children))
		{
			$children				 = Helpers::assetInfo($asset->children);
			$children['classname']	 = $asset->children;
			$children['path']        = Helpers::getPath($asset->children);

			$kids = $asset->children();

			if (!$kids)
			{
				$kids[] = Datastore::getAsset($children['classname']);
			}

			$children['kids'] = $kids;
		}
		return $children;
	}

	protected function _getParents($asset){

		$type = str_replace('\\', "\\\\", $asset->type);
		$parentAssets = $this->datastoreRepository->where('accept', '%' . $type . '%', 'like')->distinct()->get(['type', 'name', 'accept_limit'])->toArray();

		if ($parentAssets)
		{
			// get these parents
			foreach ($parentAssets as $k => $parent)
			{
				$parentAssets[$k]['key'] = Str::slug($parent['type']);
				$parents = $this->datastoreRepository->where('type', $parent['type'])->get()->toArray();
				if ($parents)
				{
					foreach ($parents as $key => $p)
					{

						$found = DatastoreDatastore::where('datastore_id', $asset->getId())
						->where('datastore2_id', $p['id'])->exists();
						if ($found)
						{
							$parents[$key]['assigned_and_found'] = true;
						}
					}
					$parentAssets[$k]['related'] = $parents;
				}
				else
				{
					$parentAssets[$k]['related'] = false;
				}
			}
		}
		return $parentAssets;
	}

	protected function _getPageData($asset){
		return ($asset->is_child || !$asset->id )? null : DatastorePages::where('asset', $asset->id)->first();
	}

	public function create($assetType, Request $request){


		$assetData = Helpers::parseAssetType($assetType, true);
		$newAsset = Datastore::getAsset($assetData);

		if($newAsset->max_instances > 0){
			$count = DatastoreModel::where('type', $assetData)->count();
			if($count >= $newAsset->max_instances){
				return redirect()->route('admin.ams.content.list', $assetType)->withFlashDanger("You can only create " . $newAsset->max_instances . " instances of this Asset");
			}
		}


		$children = $this->_getKids($newAsset);
		$parentAssets = $this->_getParents($newAsset);
		$pageData = $this->_getPageData($newAsset);

		return view('phpsa-datastore::backend.ams.create')
		->withAsset($newAsset)
		->withParents($parentAssets)
		->withChildren($children)
		->withPageData($pageData);
	}

	public function edit($assetType, $id){

		$asset = Datastore::getAssetById($id);


		$children = $this->_getKids($asset);
		$parentAssets = $this->_getParents($asset);
		$pageData = $this->_getPageData($asset);

		return view('phpsa-datastore::backend.ams.create')
		->withAsset($asset)
		->withParents($parentAssets)
		->withChildren($children)
		->withPageData($pageData);
	}

	public function save($assetType , Request $request){


		$assetData = Helpers::parseAssetType($assetType, true);
		$newAsset = Datastore::getAsset($assetData, $request->input('id'));

		$form = $request->all();

		$to_delete = (isset($form['assetRemove'])) ? $form['assetRemove'] : false;
		unset($form['assetRemove']);


		$form_valid	 = $newAsset->validate($form);
		if(!$form_valid){
			die("@TODO STILL");
		}

		$newAsset->populateAsset($form);

		if ($to_delete)
		{
			foreach ($to_delete as $kill_asset => $del)
			{
				foreach ($del as $uniq => $a)
				{
					if ($a['id'])
					{
						$nnasset = Datastore::getAssetById($id);
						if ($nnasset->ownDatastore())
						{
							foreach ($nnasset->ownDatastore() as $e)
							{
								Datastore::getAssetById($e->id);
							}
						}
						$nnasset->delete();
					}
					unset($form['assetInjectionform'][$kill_asset][$uniq]);
				}
			}
		}
		$kill_list = array();
		// check what we are to remove
		if (!empty($form['related_id']))
		{
			foreach ($form['related_id'] as $remove)
			{
				if (!in_array($remove, $form['related_assets']))
				{
					$kill_list[] = $remove;
				}
			}
		}

		$id = $newAsset->store();

		// check for multiforms
		if (isset($form['assetInjectionform']))
		{
			foreach ($form['assetInjectionform'] as $masset => $mdata)
			{
				foreach ($mdata as $childform)
				{
					$childformId = !empty($childform['id']) ? $childform['id'] : null;
					$childasset = Datastore::getAsset($masset, $childformId);
					$childasset->populateAsset($childform);
					$nid = $childasset->store($id);

					$newAsset->ownDatastore[] = $childasset;

					//$this->db->query('update datastore set datastore_id = ? where id = ?', array($id, $nid));
					// now we fikken cheat coz this isn't working the way it should
					// now add them as children to the asset
					//zp('debug')->pre($childasset);
				}
			}
		}

		if (!empty($form['related_assets']))
		{
			foreach ($form['related_assets'] as $asset)
			{
				DatastoreDatastore::firstOrCreate(['datastore_id' => $id, 'datastore2_id' => $asset]);
				// $found = $this->db->query('select id from datastore_datastore where datastore_id = ? and datastore2_id = ?', array($id, $asset))->row();

				// // not found, we need to add it
				// if (!$found)
				// {
				// 	$this->db->query('insert into datastore_datastore (datastore_id, datastore2_id) values (?, ?)', array($id, $asset));
				// }
			}
		}

		if ($kill_list)
		{
			foreach ($kill_list as $r)
			{
				$deletedRows = DatastoreDatastore::where('datastore_id', $id)->where('datastore2_id', $r)->delete();
				// $found = $this->db->query('select id from datastore_datastore where datastore_id = ? and datastore2_id = ?', array($id, $r))->row();

				// //if found, remove it
				// if ($found)
				// {
				// 	$this->db->query('delete from datastore_datastore where id =?', array($found));
				// }
			}
		}

		if(!empty($form['page_title']) && !empty($form['page_slug'])){
			$pageData = DatastorePages::firstOrNew(['asset' => $id]);
			$pageData->title = $form['page_title'];
			$pageData->slug = $form['page_slug'];
			$pageData->asset = $id;
			$pageData->save();
		}

		return redirect()->route('admin.ams.content.list', $assetType)->withFlashSuccess(__('phpsa-datastore::backend.labels.content.created'));


	}


	public function destroy($id, Request $request){
		$asset = DatastoreModel::findOrFail($id);
		if(!$asset || $asset->namespace !== 'asset'){
			throw new GeneralException("NOT FOUND");
		}
		$path = $asset->content_path;
		$asset->delete();

		return redirect()->route('admin.ams.content.list', $asset->content_path)->withFlashSuccess(__('phpsa-datastore::backend.labels.content.deleted'));


	}

	public function slug(Request $request)
	{
		DB::enableQueryLog();

		$slug	 = $request->input('page_slug');
		$id		 = (int)$request->input('id');
		$generate = $request->input('generate');


		if($generate){
			$slug = Str::slug($slug);
		}

		$exists = DatastorePages::where('slug', $slug)->where('asset', '!=', $id)->exists();

		if($generate){
			return response()->json([
				'slug' => $slug,
				'available' => $exists ? 0 : 1
			]);
		}
		return response()->json($exists ? "Slug already in use": true);


	}

	public function file(Request $request) {


		$path = Storage::putFile('public', $request->file('file'), 'public');
		//$path = $request->file('file')->store('ams');
		return response()->json(["file" => $path]);
	}

}