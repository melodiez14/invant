<?php

class RolegroupsController extends \BaseController {


	public function __construct()
	{
		$this->beforeFilter('csrf', ['only' => ['store', 'update', 'delete']]);
	}
	/**
	 * Display a listing of the resource.
	 * GET /rolegroups
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Request::ajax())
			return $this->getDatatableResources();

		return View::make('rolegroups.index', [
			'rolegroups'=> Rolegroup::where('rolegroup_depth', '>', 0)->get(),
			'user' 		=> Auth::user()
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /rolegroups/create
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('rolegroups.form', [
			'user' 		=> Auth::user()
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /rolegroups
	 *
	 * @return Response
	 */
	public function store()
	{
		$items			= Request::only('rolegroup_name', 'rolegroup_depth');
		$items['roles'] = Request::get('roles');


		$validator = Validator::make($items, [
			'rolegroup_name'	=> 'required|unique:rolegroups,rolegroup_name|max:255',
			'rolegroup_depth'	=> 'required|numeric|min:1',
			'roles'				=> 'required|json'
		]);

		if($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		}


		DB::beginTransaction();
		try {

			$rolegroup = Rolegroup::create([
				'rolegroup_name'	=> $items['rolegroup_name'],
				'rolegroup_depth'	=> $items['rolegroup_depth']
			]);

			$rgId 		= $rolegroup->id;
			$rolesObj 	= json_decode($items['roles']);
			$readyRoles = [];

			if( empty($rolesObj) ) {
				return Redirect::back()->withInput()->withErrors('Bad Parameteres');
			}

			$currentTimestamp = date('Y-m-d H:i:s');

			foreach($rolesObj as $key => $value)
			{
				foreach($value->abilities as $idx => $abl)
				{
					array_push($readyRoles, [
						'rolegroup_id'	=> $rgId,
						'module_id'	=> $value->module_id,
						'role_ability'	=> $abl,
						'created_at'	=> $currentTimestamp,
						'updated_at'	=> $currentTimestamp
					]);
				}
			}

			DB::table('roles')->insert($readyRoles);
			DB::commit();

			return Redirect::route('rolegroups.index')->with('success-message', trans('rolegroup.created_success'));
		} catch(\Exception $e)
		{
			DB::rollback();
			return Redirect::back()->withInput()->with('error-message', $e->getMessage());
		}

	}

	/**
	 * Display the specified resource.
	 * GET /rolegroups/{id}
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
	 * GET /rolegroups/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Rolegroup $rolegroups)
	{
		$roles = $rolegroups->roles;

		try {
			Role::isRoleDepthViolation($rolegroups);
			$roleServe = $rolegroups->getRolesStructure($rolegroups->id);

		} catch (\Exception $e)
		{
			return Redirect::back()->with('error-message', $e->getMessage());
		}

		return View::make('rolegroups.form', [
			'rolegroup'	=> $rolegroups,
			'mods_json'	=> Module::all()->toJson(),
			'modules'	=> Module::all(),
			'sequence'	=> ((count($roleServe) < 1) ? 1 : count($roleServe)),
			'roles'		=> $roleServe,
			'user' 		=> Auth::user()
		]);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /rolegroups/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Rolegroup $rolegroups)
	{
		$items			= Request::only('rolegroup_name', 'rolegroup_depth');
		$items['roles'] = Request::get('roles');

		$validator = Validator::make($items, [
			'rolegroup_name'	=> 'required|max:255',
			'rolegroup_depth'	=> 'required|numeric|min:1',
			'roles'				=> 'required|json'
		]);

		if($validator->fails()) {
			return Redirect::back()->withInput()->withErrors($validator);
		}
		DB::beginTransaction();
		try {

			Role::isRoleDepthViolation($rolegroups);

			$rolesObj 	= json_decode($items['roles']);
			$readyRoles = [];
			$currRoles	= $rolegroups->roles;
			$rolesTable = DB::table('roles');

			if( empty($rolesObj) )
				return Redirect::back()->withInput()->withErrors("Bad parameters!");

			$rolesTable->where('rolegroup_id', $rolegroups->id)->delete();
			$currentTimestamp = date('Y-m-d H:i:s');

			foreach($rolesObj as $key => $value)
			{
				foreach($value->abilities as $idx => $abl)
				{
					array_push($readyRoles, [
						'rolegroup_id'	=> $rolegroups->id,
						'module_id'	=> $value->module_id,
						'role_ability'	=> $abl,
						'created_at'	=> $currentTimestamp,
						'updated_at'	=> $currentTimestamp
					]);
				}
			}

			$rolesTable->insert($readyRoles);
			$rolegroups->update($items);
			DB::commit();

			return Redirect::route('rolegroups.index')->with('success-message', trans('rolegroup.update_success'));
		} catch(\Exception $e)
		{
			DB::rollback();
			return Redirect::back()->withInpu()->with('error-message', $e->getMessage());
		}

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /rolegroups/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Rolegroup $rolegroups)
	{
		DB::beginTransaction();
		try {
			$rgId	= $rolegroups->id;
			$users 	= User::where('rolegroup_id', $rgId)->count();

			Role::isRoleDepthViolation($rolegroups);

			if ($users > 0)
				throw new Exception(trans('rolegroup.dependencies_issue'));

			$rolegroups->delete();

			DB::commit();
		} catch (\Exception $e)
		{
			DB::rollback();
			return Redirect::back()->with('error-message', $e->getMessage());
		}

		return Redirect::route('rolegroups.index')->with('success-message', trans('rolegroup.delete_success'));

	}

	private function getDatatableResources()
	{

		return Datatable::collection(Rolegroup::orderBy('rolegroup_depth', 'ASC')->where('rolegroup_depth', '>', 0)->get())
            ->showColumns('id')
            ->addColumn('rolegroup_name',function ($model) {

					$html = "<a href=\"" .route('rolegroups.edit', ['rolegroups' => $model->id]). "\">" . $model->rolegroup_name . "</a>";
					return $html;

                }
            )
			->addColumn('rolegroup_depth', function ($model) {
				return $model->rolegroup_depth;
			})
			->addColumn('created_at', function ($model) {
				return readable_date($model->created_at);
			})
            ->searchColumns('id', 'rolegroup_name', 'rolegroup_depth', 'created_at')
            ->orderColumns('id', 'rolegroup_name','rolegroup_depth', 'created_at')
            ->addColumn('action', function ($model) {

                $html = '<a href='.route('rolegroups.edit', ['rolegroups'=>$model->id]).' class="m-l-sm"><i class="fa fa-edit fa-hover" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';
                $html .= delete_btn(route('rolegroups.destroy', ['rolegroups' => $model->id]));

                return $html;
            })
            ->make();
	}

}
