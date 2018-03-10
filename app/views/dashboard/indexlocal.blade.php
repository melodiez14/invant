@section('breadcrumb')
    <li class="active">Dashboard</li>
@stop

@section('content')
    <div class="m-b-md">
        <h3>Dashboard</h3>
        <small>Welcome back, <strong>{{ $staff->fullname}}</strong></small>
    </div>
  <div class="row">

    <!-- Project Manager -->
    <div class="col-md-4">
      <section class="panel panel-default">
        <header class="panel-heading font-bold">
            Project Manager
        </header>
        <div class="panel-body">
          <div class="row">
            <section class="panel m-t-xs">
              <header class="panel-heading m-t-n">
                <div class="clearfix">

                  @if ($project->manager->meta->photo)
                      <img src="{{ route('file.show', ['file'=>$project->manager->meta->photo->file_name] )}}" class="pull-left thumb avatar b-3x m-r img-circle">
                  @else
                      <img src="{{ asset('images/avatar_default.jpg')}}" class="pull-left thumb avatar b-3x m-r img-circle">
                  @endif
                  <div class="clear">
                      <small class="text-muted">Project Manager</small>
                    <div class="h3 m-t-xs m-b-xs text-dark">
                      {{ $project->manager->meta->full_name }}
                    </div>
                  </div>
                </div>
              </header>
              <div class="list-group no-radius alt">
                <a class="list-group-item" href="#">
                  <span class="badge bg-light ">{{ $project->manager->meta->phone }}</span>
                  <i class="fa fa-phone icon-muted"></i>
                  Phone
                </a>
                <a class="list-group-item" href="#">
                  <span class="badge bg-light ">{{ $project->manager->email }}</span>
                  <i class="fa fa-envelope icon-muted"></i>
                  Email
                </a>
                <a class="list-group-item" href="#">
                  <span class="badge bg-light ">{{ $project->startDate->toDateString() }}</span>
                  <i class="fa fa-calendar icon-muted"></i>
                  Start Date
                </a>
                <a class="list-group-item" href="#">
                  <span class="badge bg-light ">{{ $project->finishDate->toDateString() }}</span>
                  <i class="fa fa-calendar icon-muted"></i>
                  Finish Date
                </a>
              </div>
            </section>
          </div>
        </div>
      </section>
    </div>
    <!-- END: Project Manager -->

    <div class="col-md-8">
      <div class="row">

        <!-- Project Locations -->
        <div class="col-md-12">
          <section class="panel panel-default">
            <header class="panel-heading font-bold">
                Project Locations
            </header>
            <div class="panel-body">
              <ul>
              @foreach($project->districts as $district)
                  <li>{{ $district->title }}</li>
              @endforeach
              </ul>
            </div>
          </section>
        </div>
        <!-- END: Project Locations -->

        <!-- Project Reach -->
        <div class="col-md-12">
          <section class="panel panel-default">
            <header class="panel-heading font-bold">
                Project Reach (after double counting)
            </header>
            <div class="row m-l-none m-r-none bg-light lter">
              <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                  <i class="fa fa-circle fa-stack-2x text-info"></i>
                  <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                  <span class="h4 block m-t-xs"><strong id="men">{{ TotalReach::getTotalReachByProject($project->id)['men'] }}</strong></span>
                  <small class="text-muted text-uc">Men</small>
              </div>
              <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                  <i class="fa fa-circle fa-stack-2x text-info"></i>
                  <i class="fa fa-female fa-stack-1x text-white"></i>
                </span>
                  <span class="h4 block m-t-xs"><strong>{{ TotalReach::getTotalReachByProject($project->id)['women'] }}</strong></span>
                  <small class="text-muted text-uc">Women</small>
              </div>
              <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                  <i class="fa fa-circle fa-stack-2x text-success"></i>
                  <i class="fa fa-male fa-stack-1x text-white"></i>
                </span>
                  <span class="h4 block m-t-xs"><strong>{{ TotalReach::getTotalReachByProject($project->id)['boys'] }}</strong></span>
                  <small class="text-muted text-uc">Boys</small>
              </div>
              <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                <span class="fa-stack fa-2x pull-left m-r-sm">
                  <i class="fa fa-circle fa-stack-2x text-success"></i>
                  <i class="fa fa-female fa-stack-1x text-white"></i>
                </span>
                  <span class="h4 block m-t-xs"><strong>{{ TotalReach::getTotalReachByProject($project->id)['girls'] }}</strong></span>
                  <small class="text-muted text-uc">Girls</small>
              </div>
            </div>
          </section>
        </div>
        <!-- END: Project Reach -->

      </div>
    </div>
  </div>

@stop

