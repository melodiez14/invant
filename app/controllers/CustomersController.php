<?php

class CustomersController extends BaseController {

	protected $layout = 'layouts.dashboard';

    public function __construct()
    {
        // Only allow admin global to access this controller
        // $this->beforeFilter('adminGlobal');
    }

	/**
	 * Display a listing of the resource.
	 * GET /customers
	 *
	 * @return Response
	 */
	public function index()
	{

		// prepare the response
		$data = [
			'customer_access' => getAbilities('customers'),
		];
		
		return View::make('customers.index', $data);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /customers/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /customers
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /customers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /customers/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /customers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /customers/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}