<?php

class UploadController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /upload
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /upload/create
	 *
	 * @return Response
	 */
	public function create()
	{

	}

	/**
	 * Store a newly created resource in storage.
	 * POST /upload
	 *
	 * @return Response
	 */
	public function store()
	{
		// dd(Input::file('file')->getSize());
		if(Input::hasFile('file') && Input::file('file')->isValid())
		{
			$validator = Validator::make(Input::all(), [
				'file'	=> 'required|image'
			]);

			if($validator->fails())
				App::abort(403);

			return Upload::copy(Input::file('file'), 'usr/avatar');
		}

		App::abort(403);
	}

	/**
	 * Display the specified resource.
	 * GET /upload/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Upload $uploads)
	{
		$directory= Config::get('idms.upload_path');
		$images	= new \Imagine\Gd\Imagine();

		return $images->open($directory . $uploads->file_name)
			->show($uploads->extension);

	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /upload/{id}/edit
	 *
	 * @param  Upload  $uploads
	 * @return Response
	 */
	public function edit(Upload $uploads)
	{

	}

	/**
	 * Update the specified resource in storage.
	 * PUT /upload/{id}
	 *
	 * @param  Upload  $uploads
	 * @return Response
	 */
	public function update(Upload $uploads)
	{
		$cropOptionsJson= Request::get('crop_options');
		$validator 		= Validator::make(
			['crop_options'	=> $cropOptionsJson],
			['crop_options' => 'json']
		);
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /upload/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
