<?php

class ModuleController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('csrf', ['only' => array('store', 'update', 'delete')]);
	}

	/**
	 * Display a listing of the resource.
	 * GET /module
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax() || Request::has('datatable')) {

			if( Request::has('datatable') )
				return $this->getDatatableResources();

			return Module::all();
		}

		return View::make('modules.index', [
			'user'		=> Auth::user(),
			'modules'	=> Module::all()
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /module/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('modules.form', [
			'user'		=> Auth::user()
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /module
	 *
	 * @return Response
	 */
	public function store()
	{
		$modules['module_name'] = trim(Request::get('module_name'));
		$modules['module_core']	= Request::has('module_core');
		$modules['module_alias']= adv_snake_case(Request::get('module_alias'));

		$validator = Validator::make($modules, [
			'module_alias'	=> 'required|unique:modules,module_alias|max:32|alpha_dash',
			'module_name'	=> 'required|max:255',
			'module_core'	=> 'required|boolean'
		]);

		if($validator->fails())
			return Redirect::back()->withErrors($validator);
		DB::beginTransaction();

		try {
			Module::create($modules);
			DB::commit();
		} catch(\Exception $e)
		{
			DB::rollback();
			return Redirect::back()->with('error-message', $e->getMessage());
		}

		return Redirect::route('modules.index')->with('success-message', trans('module.create_success'));

	}

	/**
	 * Display the specified resource.
	 * GET /module/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Module $module)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /module/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Module $modules)
	{
		return View::make('modules.form', [
			'module'	=> $modules,
			'user'		=> Auth::user()
		]);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /module/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Module $modules)
	{
		$module_name= Request::get('module_name');
		$module_core= Request::has('module_core');

		$validator	= Validator::make([
			'module_name'	=> $module_name
		], [
			'module_name'	=> 'required|max:255'
		]);

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator);
		}

		$modules->module_name = $module_name;
		$modules->module_core = $module_core;

		$modules->save();

		return Redirect::route('modules.index')->with('success-message', trans('module.update_success'));

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /module/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Module $modules)
	{
		$user = Auth::user();

		if($modules->module_core == 1)
			return Redirect::back()->with('error-message', trans('module.core_module_violation'));

		$modules->delete();

		return Redirect::route('modules.index')->with('success-message', trans('module.delete_success'));


	}

	private function getDatatableResources()
	{
		return Datatable::collection(Module::all())
            ->showColumns('id','module_name')
            ->addColumn('module_core',function ($model) {
                    return '<em>' . (($model->module_core == 1) ? 'core' : 'optional') . '</em>';
                }
            )
			->addColumn('created_at', function ($model) {
				return readable_date($model->created_at);
			})
            ->searchColumns('id', 'module_name', 'module_core', 'created_at')
            ->orderColumns('id', 'module_name','module_core', 'created_at')
            ->addColumn('action', function ($model) {

                $html = '<a href='.route('modules.edit', ['modules'=>$model->id]).' class="m-l-sm"><i class="fa fa-edit fa-hover" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
                $html .= delete_btn(route('modules.destroy', ['modules' => $model->id]));

                return $html;
            })
            ->make();
	}

}
