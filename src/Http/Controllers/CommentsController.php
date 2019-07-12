<?php

namespace Phpsa\Datastore\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Phpsa\Datastore\Models\DatastoreComments;


class CommentsController extends Controller
{
	use  ValidatesRequests;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$request->validate([
			'body'=>'required',
			'datastore_id' => 'required'
        ]);
        $input = $request->all();
        $input['user_id'] = auth()->user()->id;
        DatastoreComments::create($input);
        return back();
    }

}