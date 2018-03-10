<?php

//use \Exception;

class UsersController extends \BaseController
{
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'layouts.dashboard';

    public function __construct()
    {
        // Only allow admin global to access this controller
        // $this->beforeFilter('adminGlobal');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $XUserMode = isXUser('read', 'staffs');
        $users = User::all();

        if (Request::ajax() || Request::has('datatable')) {

            if(!Request::has('datatable'))
                return $users;

            return $this->getDatatable($XUserMode);
        }
        // debugVar($XUserMode);
        return View::make('users.index', array(
            'users' => $users,
            'XUserMode' => $XUserMode
            )
        );
    }

    /**
     * Display form to create new user
     * @return Response
     */
    public function create()
    {
        if (!isXUser('create', 'users')) {
            return Redirect::route('users.index')->withErrors('You are not authorized');
        }
        $myDepth    = Auth::user()->rolegroup->rolegroup_depth;
        $rolegroups = Rolegroup::where('rolegroup_depth', '>', $myDepth)->get();
        $profiles   = Staff::whereNull('user_id')->get();

        return View::make('users.form',[
            'rolegroups'    => $rolegroups,
            'profiles'      => $profiles,
            'owned'         => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @TODO confirmation password ketika bikin user
     * @return Response
     */
    public function store()
    {
        $rules = [
            'email'         => 'required|email|unique:users,email|max:255',
            'password'      => 'required|confirmed',
            'is_active'     => 'required|boolean',
            'rolegroup_id'  => 'required|exists:rolegroups,id',
            'profile_id'    => 'required_without_all:profile_name, sex_id, profile_address, profile_phone|exists:staffs,id',
            'profile_name'  =>  'required_without:profile_id|max:255',
            'profile_email' =>  'required_without:profile_id|max:255|email',
            'sex_id'        =>  'required_without:profile_id|in:1,2'
        ];
        $requests   = Request::all();
        $valid      = Validator::make($requests, $rules);

        if($valid->fails()){
            return Redirect::back()->withInput()->withErrors($valid);
        }

        DB::beginTransaction();

        try {

            $userItems = [
                'email'         => $requests['email'],
                'password'      => Hash::make($requests['password']),
                'is_active'     => $requests['is_active'],
                'rolegroup_id'  => $requests['rolegroup_id']
            ];
//            dd(Request::has('profile_id'));
            $user = User::create($userItems);

            if(!empty(Request::get('profile_id'))) {
                /*$staff = Staff::find($requests['profile_id']);
                $staff->user_id = $user->id;
                $staff->email   = $user->email;
                $staff->save();*/

                Staff::where('id', $requests['profile_id'])->update(['user_id' => $user->id]);

            } else {

                $profileItems = [
                    'name'      => $requests['profile_name'],
                    'email'     => $requests['profile_email'],
                    'sex_id'    => $requests['sex_id'],
                    'address'   => $requests['profile_address'],
                    'phone'     => $requests['profile_phone'],
                    'user_id'   => $user->id
                ];

                Staff::create($profileItems);

            }

            DB::commit();
        }catch(Exception $e)
        {
            DB::rollback();
//            return  Redirect::back()->with('error-message', $e->getMessage());
            return  Redirect::back()->with('error-message', $e->getFile() . " Line: " . $e->getLine() . " Message: " . $e->getMessage());
        }

        return Redirect::route('users.index')->with('success-message', trans('user.created', ['name' => $requests['email']]));
    }

    /**
     * Display the specified resource.
     *
     * @param  User      $users
     * @return Response
     */
    public function show(User $users)
    {

        // dd($users->profile->id);
        return Response::view('errors.notfound', array(), 404);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function edit(User $users)
    {
        if (!isXUser('update', 'users')) {
            return Redirect::route('users.index')->withErrors('You are not authorized');
        }

        $myDepth    = Auth::user()->rolegroup->rolegroup_depth;
        $profiles   = Staff::with([
            'user' => function($query) use ($myDepth)
            {
                return $query->with([
                    'rolegroup' => function($query) use ($myDepth)
                    {
                        return $query->where('rolegroup_depth', '>', $myDepth);
                    }
                ]);
            }
        ])->get();

        return View::make('users.form', [
            'user' => $users,
            'owned'=> (Auth::user()->id === $users->id),
            'rolegroups' => Rolegroup::where('rolegroup_depth', '>', $myDepth)->get(),
            'profiles'  => $profiles,
            'profile'   => Staff::where('user_id', $users->id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     * TODO: handle email change, also change related staff model
     * @param  User $users
     * @return Response
     */
    public function update(User $users)
    {
        $isOwned = Auth::user()->id === $users->id;

        if($isOwned)
            return $this->updateMyAccount();

        $rules = [
            'email'         => 'required|email|unique:users,email,' .$users->id. '|max:255',
            'password'      => 'confirmed',
            'is_active'     => 'required|boolean',
            'rolegroup_id'  => 'required|exists:rolegroups,id',
            'profile_id'    => 'required_without_all:profile_name, sex_id, profile_address, profile_phone',
            'profile_name'  =>  'required_without:profile_id|max:255',
            'profile_email' =>  'required_without:profile_id|max:255|email',
            'sex_id'        =>  'required_without:profile_id|in:1,2'
        ];
        $requests   = Request::all();
        $valid      = Validator::make($requests, $rules);

        if($valid->fails()){
            return Redirect::back()->withInput()->withErrors($valid);
        }

        DB::beginTransaction();

        try {

            Role::isRoleDepthViolation($users->rolegroup);

            $usersItems = [
                'email'     => Request::get('email'),
                'is_active' => Request::get('is_active'),
                'rolegroup_id' => Request::get('rolegroup_id')
            ];

            if(!empty(Request::get('password')))
                $usersItems['password'] = Hash::make(Request::get('password'));

            $users->update(
                $usersItems
            );

            if(!empty(Request::get('profile_id'))) {

                $currentProfile = !empty($users->profile->id) ? $users->profile->id : 0;

                if($currentProfile != Request::get('profile_id')) {

                    $staff = Staff::find(Request::get('profile_id'));

                    if(!empty($staff->user_id) && ($staff->user_id != $users->id))
                        throw new Exception(trans('user.profile_taken'));

                    Staff::where('user_id', $users->id)->update([
                        'user_id' => null
                    ]);

                    $staff->user_id = $users->id;
                    $staff->save();

                }

            } else {


                Staff::where('user_id', $users->id)->update([
                    'user_id' => null
                ]);

                $profileItems = [
                    'name'      => $requests['profile_name'],
                    'email'     => $requests['profile_email'],
                    'sex_id'    => $requests['sex_id'],
                    'address'   => $requests['profile_address'],
                    'phone'     => $requests['profile_phone'],
                    'user_id'   => $users->id
                ];

                Staff::create($profileItems);

            }

            DB::commit();
        } catch(Exception $e)
        {
            DB::rollback();
            return Redirect::back()->with('error-message', $e->getMessage())->withInput();
        }

        return Redirect::route('users.index')->with('success-message', trans('user.updated', ['name' => Request::get('email')]));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $users
     * @return Response
     */
    public function destroy(User $users)
    {
        if (!isXUser('delete', 'users')) {
            return Redirect::route('users.index')->withErrors('You are not authorized');
        }

        DB::beginTransaction();

        try {

            Role::isRoleDepthViolation($users->rolegroup);
            $users->delete();
            DB::commit();
        } catch(Exception $e)
        {
            DB::rollback();
            return Redirect::back()->with('error-message', $e->getMessage());
        }

        return Redirect::route('users.index')->with('success-message', trans('user.deleted', ['name' => $users->email]));
    }

    /**
     * Get specified data from database for datatable
     * Filter:
     * - Local Admin
     * - Staff not banned
     * @return Datatable collection/query
     */
    public function getDatatable($XUserMode)
    {
        // Get admin local id
        /*$groupAdminLocal = Sentry::findGroupByName('admin-local');

        // Get all  admin local who is not staff or staff which not banned
        $admins = Sentry::findAllUsersInGroup($groupAdminLocal);

        $admins_id = [];
        foreach ($admins as $admin) {
            // filter not banned staff
            if ($admin->meta) {
                // staff
                $throttle = Sentry::findThrottlerByUserId($admin->id);
                if ($throttle->isBanned() === false) {
                    // not banned
                    array_push($admins_id, $admin->id);
                }
                // banned, don't insert

            } else {
                // not staff, insert all
                array_push($admins_id, $admin->id);
            }
        }

        // if no local admin is registered, give false ID so it returns null;
        if (count($admins_id) < 1) {
            array_push($admins_id, 0);
        }

        $admins = User::whereIn('id', $admins_id)->get();*/
        $users = User::with('profile')->get();

        $returned = Datatable::collection($users)
            ->showColumns('id','email')
            ->addColumn('rolegroup', function($model) {
                return $model->rolegroup->rolegroup_name;
            })
            ->addColumn('name', function($model) {
                return isset($model->profile->name) ? $model->profile->name : null;
            })
            ->addColumn('active', function($model) {
                return $model->is_active == 0 ? "<em>Innactive</em>" : "<em>Active</em>";
            })
            ->searchColumns('email','name')
            ->orderColumns('id','email', 'rolegroup','name', 'active');
            /*->addColumn('last_login', function ($model) {
                if (get_class($model->last_login) == 'Carbon\Carbon') {
                    return $model->last_login->toDateTimeString();
                } else {
                    return '';
                }
            })*/
            if($XUserMode){
				$returned->addColumn('action', function ($model) {
                    $html = '';
                    if(isset($model->profile))
                        $html .= '<a href='.route('staffs.show', ['staffs'=>$model->profile->id, 'fromUser'=> 1]).'><i class="fa fa-eye fa-hover" data-toggle="tooltip" data-placement="top" title="View"></i></a>';

                    $html .= '<a href='.route('users.edit', ['users'=>$model->id]).' class="m-l-sm"><i class="fa fa-edit fa-hover" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>';

                    if ($model->meta) {
                        $html .= '<a href="'.route('staffs.ban', ['staffs'=>$model->meta->id]).'" data-confirm="Removing admin access will make this user cannot login but KEEP his personal data (in staff), continue?" class="m-l-sm js-delete-confirm-link"><i class="fa fa-unlink fa-hover" data-toggle="tooltip" data-placement="top" title="Remove Admin Access"></i></a>';
                        $html .= Form::open(array('url' => "users/$model->id", 'role' => 'form', 'method'=>'delete','class'=>'form-inline','style="display:inline;"'));
                        $html .=   Form::submit('Delete', array('class' => 'hidden'));
                        $html .= '<a href="#" data-confirm="Deleting will remove this admin and DELETE all his personal data (in staff), continue?" class="m-l-sm js-delete-confirm"><i class="fa fa-times fa-hover" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
                    } else {
                        $html .= Form::open(array('url' => "users/$model->id", 'role' => 'form', 'method'=>'delete','class'=>'form-inline','style="display:inline;"'));
                        $html .=   Form::submit('Delete', array('class' => 'hidden'));
                        $html .= '<a href="#" data-confirm="Are you sure to delete this admin?" class="m-l-sm js-delete-confirm"><i class="fa fa-times fa-hover" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>';
                    }

                    $html .= Form::close();

                    return $html;
                });
            }
            return $returned->make();
    }

    /**
     * Display form to change local admin password (within global admin)
     *
     * @return response
     */
    public function changePassword($id)
    {
        $user = Sentry::findUserById($id);
        $this->layout->content = View::make('users.changepassword', array(
            'user' => $user,
            'previousUrl'=>$this->getPreviousUrl(route('users.index'))
        ));
    }

    /**
     * Store a newly created password.
     *
     * @return Response
     */
    /*public function storePassword($id)
    {
        // Validate inputed password
        $passwordValidator = Validator::make(
            array(
                'password' => Input::get('password')
            ),
            array(
                'password' => 'required|min:5'
            )
        );

        if ($passwordValidator->fails()) {
            $messages = json_decode($passwordValidator->messages(), true); // convert json to array
            $message = '<ul>';
            foreach ($messages as $key => $value) {
                $message .= '<li>' . $value[0] . '</li>'; // append
            }

            $message .= '</ul>';

            return Redirect::back()->with('error-message', $message)->withInput( Input::all() );
        }

        try {
            // Find the user using the user id
            $user = Sentry::findUserById($id);
            // Change password
            $user->password = Input::get('password');
            $user->save();
            // return Redirect::route('users.edit', ['users'=>$id])->with('success-message', 'Password Changed.');
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            echo 'User was not found.';
        }
        // Redirect to previous page
        $targetUrl = Session::get('prevUrl');

        $redirect = Redirect::back(301)->setTargetUrl($targetUrl)->with('success-message', "Password Changed.");

        return $redirect;
    }*/

    private function updateMyAccount()
    {
        $user   = Auth::user();
        $rules  = [
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'confirmed'
        ];

        $valid = Validator::make(Request::all(), $rules);

        if($valid->fails())
            return Redirect::back()->withErrors($valid);

        DB::beginTransaction();
        try {

            $user->email    = Request::get('email');
            $user->password = Hash::make(Request::get('password'));

            $user->save();

            DB::commit();
        } catch(Exception $e)
        {
            DB::rollback();
            return Redirect::back()->with('error-message', $e->getMessage());
        }

        return Redirect::back()->with('success-message', trans('user.updated' , ['name' => $user->email]));
    }
}
