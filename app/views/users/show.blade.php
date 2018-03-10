@section('breadcrumb')
    <li><a href="#">Account</a></li>
    <li><a href="{{ route('users.index') }}">Local Admin</a></li>
    <li class="active">View {{ $user->first_name . ' ' . $user->last_name }}</li>
@stop

@section('content')
    <section class="panel panel-default">
        <header class="panel-heading font-bold">
            <h3>Showing {{ $user->first_name . ' ' . $user->last_name }}
            <span class="pull-right">
                <a href="{{ route('users.edit', ['users'=>$user->id]) }}" class="btn btn-info btn-s-sm"><i class="fa fa-edit fa-hover"></i> Edit</a>
                {{ Form::open(array('url' => "users/$user->id", 'role' => 'form', 'method'=>'delete','class'=>'form-inline','style="display:inline;"')) }}
                {{ Form::submit('Delete', array('class' => 'hidden')) }}
                <a href="#" data-confirm="Are you sure to delete this admin?" class="btn btn-danger btn-s-sm js-delete-confirm"><i class="fa fa-times-circle-o fa-hover"></i> Delete</a>
                {{ Form::close() }}
            </span>

            </h3>
        </header>
        <div class="panel-body">
            <div class="col-sm-4">
                <div class="row m-t-md">
                    <div class="col-sm-4 text-muted text-right text-left-xs">Name</div>
                    <div class="col-sm-8 text-dark text-left font-bold">{{ $user->first_name . ' ' . $user->last_name }}</div>
                </div>
                <div class="row m-t-md">
                    <div class="col-sm-4 text-muted text-right text-left-xs">Admin of</div>
                    <div class="col-sm-8 text-dark text-left font-bold">
                        @if ($user->projectswithpermission)
                            <ul class="list-unstyled">
                                @foreach($user->projectswithpermission as $project)
                                    <li><a href="{{route('projects.show', ['projects'=>$project->id])}}">
                                    {{$project->title}}</a></li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="row m-t-md">
                    <div class="col-sm-4 text-muted text-right text-left-xs">Last Login</div>
                    <div class="col-sm-8 text-dark text-left font-bold">
                        @if (get_class($user->last_login) == 'Carbon\Carbon')
                            {{ $user->last_login->toDateTimeString() }}
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="row m-t-md">
                    <div class="col-sm-4 text-muted text-right text-left-xs"></div>
                    <div class="col-sm-8 text-dark text-left font-bold">
                        <a href="{{ route('users.changepassword', ['users'=>$user->id])}}" class="btn btn-danger btn-xs m-t-sm">Change Password</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="panel-footer">
            <a href="{{ route('users.index') }}" class="btn btn-link pull-right">Back</a>
        </div>
    </section>
@stop
