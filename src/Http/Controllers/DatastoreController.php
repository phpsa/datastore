<?php

namespace Phpsa\Datastore\Http\Controllers;

use App\Http\Controllers\Controller;
use Phpsa\Datastore\Datastore;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\Helpers;
use Phpsa\Datastore\DatastoreException;
use Phpsa\Datastore\Repositories\DatastoreRepository;

use Phpsa\Datastore\Models\Datastore as DatastoreModel;
use Phpsa\Datastore\Models\DatastorePages;

Class DatastoreController extends Controller {

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

	protected function iCan($permission, $then, $else){
		$user = auth()->user();
		return $user && $user->can($permission) ? $then : $else;
	}

	protected function canViewAll($status = 'published'){
		return $this->iCan('manage datastore', null , $status);
	}

	protected function getPageBySlug($slug){
		$page = DatastorePages::where('slug', $slug)->first();
		if(!$page){
			abort(404);
		}

		$datastore = $page->datastore;
		$user = auth()->user();

		if(!$datastore->statusIsActive() && (!$user || !$user->can('manage datastore'))) {
			abort(404);
		}
		return $page;
	}

	public function page($slug){

		$page = $this->getPageBySlug($slug);

		$acceptedAssets = $page->datastore->accept ? $this->datastoreRepository->paginateAccepted($page->asset, $this->canViewAll(Helpers::getStatusEquals($page->datastore->accept))) : false;

		return view($page->datastore->getViewName())
		->withDatastore($page->datastore)
		->withChildren($page->datastore->children())
		->withAccepted($acceptedAssets)
		->withPage($page);

	}

	public function childPage($parent_slug, $slug){
		return $this->page($slug);
	}

    public function ___tests()
    {

		//ok we need a list of objects we can usse::

		// $assets = Helpers::getAssetList(true);

		// echo '<pre>';
		// print_r($assets);
		// exit;
		$tester = Datastore::getAsset(ContentAsset::class);

		$tester->prop('title', 'Moe Title');
        $tester->prop('content', '<p>Rhoncus hac aliquam aliquam! Et mauris, et quis platea ut elementum natoque sit natoque lectus augue integer aliquam porta rhoncus nec, cursus diam a parturient augue ut! Tincidunt eros urna lacus lorem, sit scelerisque. Proin duis auctor ut. Turpis? Sed, diam elit sed velit dapibus phasellus, pulvinar mattis! Sociis augue in parturient sed ultricies et.</p>');
		$tester->prop('status', 'published');

		//echo '<pre>$tester->val(): '; print_r($tester->val()); echo '</pre>'; die();


		$tester->store();


		echo '<pre>$tester->export(): '; print_r($tester->export()); echo '</pre>'; die();


		/*$tester = Datastore::getAssetById(11);
		echo $tester->render('title');

		echo $tester->form('title');

		echo $tester->getMetadataForm();

		echo $tester->getForm();


		print_r($tester->getFieldValues());*/

		/*
		$tester = Datastore::getAsset(ContentAsset::class);

        $tester->prop('content', '<p>Rhoncus hac aliquam aliquam! Et mauris, et quis platea ut elementum natoque sit natoque lectus augue integer aliquam porta rhoncus nec, cursus diam a parturient augue ut! Tincidunt eros urna lacus lorem, sit scelerisque. Proin duis auctor ut. Turpis? Sed, diam elit sed velit dapibus phasellus, pulvinar mattis! Sociis augue in parturient sed ultricies et.</p>');
		$tester->prop('status', 'published');

		//$tester->prop('title', 'Moe Title');

		try {
			$tester->validate($tester->getFieldValues());
		}catch(\Exception $e){
			echo $e->getMessage();
			print_r($e->errors());
		}

		$tester->prop('title', 'Moe Title');
		try {
			$tester->validate($tester->getFieldValues());
			echo "Form is valid";
		}catch(\Exception $e){
			echo $e->getMessage();
			print_r($e->errors());
		}
		(*/

		/*
		if( $this->input->post('page_title') && $this->input->post('page_slug'))
		{
			$this->load->model('database/datastore_pages_model');

			$page = $this->datastore_pages_model->get_where(array('asset' => $newasset->id), null, 1);

			if( ! empty($page))
			{
				$this->datastore_pages_model->update(array(
					'title'	 => $this->input->post('page_title'),
					'slug' 	 => $this->input->post('page_slug'),
					'asset'	 => $newasset->id
				), $page->id);
			}
			else
			{
				$this->datastore_pages_model->insert(array(
					'title'	 => $this->input->post('page_title'),
					'slug' 	 => $this->input->post('page_slug'),
					'asset'	 => $newasset->id
				));
			}
		}

		*/

	}



}