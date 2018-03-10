<?php

class DashboardController extends BaseController {

    public function getDashboard()
    {
        return View::make('dashboard.index');
    }

    /**
     * Get summary of monthly report by year
     * @return json
     */
    public function getSummaryMonthlyReport()
    {
        $year = Input::get('year');
        $XUser= isXUser('READ', 'projects');
        // $year = date('Y');
        $result = [];
        $user   = Auth::user()->id;/*->with(['projects' => function($query) {
            return $query->whereNull('realFinishDate')
                ->where('realFinishDate', '<', date('Y-m-d'))
                ->where('status_id', 1);
        }])->first();*/
        $collection = $XUser ? Project::with('reports')->get() : Project::with(['reports', 'users' => function($query) use($user) {
            return $query->where('id', $user);
        }])->whereNull('realFinishDate')
            ->where('realFinishDate', '<', date('Y-m-d'))
            ->where('status_id', 1)->get();

        foreach ($collection as $project) {
            // check if project startDate and finishDate within requested year
            $project->load(['activities']);
            if ($project->startDate->year <= $year && $project->finishDate->year >= $year) {
                $projectUrl = route('projects.show', ['projects'=>$project->id]);
                $projectResult = [
                    'id'=>$project->id,
                    'title'=>$project->title,
                    'sofcodes'=>$project->sofCodes,
                    'projectUrl'=>$projectUrl
                ];

                // check activity per project
                $month = array();
                for ($i=1; $i <= 12; $i++) {
                    $month[$i] = $project->activities->filter(function ($query) use ($i){
                        return $query->activityDate->format('n') == $i;
                    })->count();
                }
                $projectResult['monthly_activities'] = $month;
                array_push($result, $projectResult);
            }

        }
        return json_encode($result);
    }

    private function getOwnerCollection(){
        $owners = array();
        foreach (Owner::all() as $owner)
            $owners[$owner->id] = $owner->name;
        return $owners;
    }

    private function getProjectCollection(){
        $projects = array();
        foreach (Project::all() as $project)
            $projects[$project->id] = $project->title;
        return $projects;
    }
}
