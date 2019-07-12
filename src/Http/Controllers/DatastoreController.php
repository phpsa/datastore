<?php

namespace Phpsa\Datastore\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


use Phpsa\Datastore\Datastore;
use Phpsa\Datastore\Asset;
use Phpsa\Datastore\Helpers;
use Phpsa\Datastore\DatastoreException;
use Phpsa\Datastore\Repositories\DatastoreRepository;

use Phpsa\Datastore\Models\Datastore as DatastoreModel;
use Phpsa\Datastore\Models\DatastorePages;

Class DatastoreController extends Controller {

	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/**
	 * @var DatastoreRepository
	 */
    protected $datastoreRepository;

    /**
     * UserController constructor.
     *
     * @param DatastoreRepository $datastoreRepository
	 * @todo get rid of these datastoreRepository and make use of the mode only
     */
    public function __construct(DatastoreRepository $datastoreRepository)
    {
        $this->datastoreRepository = $datastoreRepository;
	}

	/**
	 * Checks if the current user can manage else set status column
	 * this allows us to filter out unpublished items to public but allow admins to
	 * view unpublished items
	 *
	 * @param string $permission
	 *
	 * @return string|null
	 */
	protected function canViewAll($status = 'published')
	{
		$user = auth()->user();
		return $user && $user->can('manage datastore') ? null : $status;
	}

	/**
	 * gets page based on the slug
	 *
	 * @param string $slug
	 *
	 * @return DatastorePages
	 */
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

	/**
	 * Gets the page view based off of the slug passed in
	 *
	 * @param string $slug
	 *
	 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
	 */
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

	public function articleByAuthor($author_id, $slug){
		$this->datastoreRepository->paginateSearchProp('author', $author_id, Phpsa\Datastore\Ams\Article\ItemAsset::class, $this->canViewAll('published'));
	}

}