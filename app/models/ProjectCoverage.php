<?php
class ProjectCoverage
{
    /**
     * Year of project coverage
     * @var int
     */
    private $year;
    /**
     * Scope of Project Coverage
     * @var string all, province, district, subdistrict / village
     */
    private $scope;
    /**
     * Id of defined scope
     * @var int
     */
    private $id;

    /**
     * Construct a new project coverage with year as required
     * @param int  $year
     * @param string  $scope
     * @param integer $id
     */
    function __construct($year, $scope="all", $id=0) {
        $this->year = $year;
        $this->scope = $scope;
        $this->id = $id;
    }

    /**
     * Get project coverage
     * @return [type] [description]
     */
    public function getCoverage()
    {
        // default by year
        $query = $this->buildQuery();
        // switch scope other than 'all'
        switch ($this->scope) {
            case 'province':
                $query->where('provinces.id',$this->id);
                break;
            case 'district':
                $query->where('districts.id',$this->id);
                break;
            case 'subdistrict':
                $query->where('subdistricts.id', $this->id);
                break;
            case 'village':
                $query->where('villages.id',$this->id);
                break;
            case 'all':
            default:
                break;
        }

        $result['projects'] = $query->get();

        $result['summary'] = [
            'totalProvince'=>$query->distinct('provinces.title')->count('provinces.title'),
            'totalDistrict'=>$query->distinct('districts.title')->count('districts.title'),
            'totalSubdistrict'=>$query->distinct('subdistricts.title')->count('subdistricts.title'),
            'totalVillage'=>$query->distinct('villages.title')->count('villages.title')
        ];
        return $result;
    }

    /**
     * Create query with Query Builder
     * @return Illuminate\Database\Eloquent\Builder builder instance
     */
    public function buildQuery()
    {
        $query = DB::table('district_project')
            ->leftJoin('activities', 'activities.project_id', '=', 'district_project.project_id')
            ->leftJoin('activity_subdistrict', function($join) {
                $join->on('activity_subdistrict.activity_id', '=', 'activities.id')
                    ->on(DB::raw('left(stc_activity_subdistrict.subdistrict_id,4)'),
                         '=', 'district_project.district_id');
            })
            ->leftJoin('activity_village', function($join) {
                $join->on('activities.id', '=', 'activity_village.activity_id')
                    ->on(DB::raw('left(stc_activity_village.village_id,7)'), '=',
                         'activity_subdistrict.subdistrict_id');
            })
            ->leftJoin('projects', 'district_project.project_id', '=', 'projects.id')
            ->leftJoin('staffs', 'projects.manager_id', '=', 'staffs.user_id')
            ->leftJoin('provinces', DB::raw('left(stc_district_project.district_id, 2)'),
                '=', 'provinces.id')
            ->leftJoin('districts', 'districts.id', '=', 'district_project.district_id')
            ->leftJoin('subdistricts', 'activity_subdistrict.subdistrict_id',
                '=', 'subdistricts.id')
            ->leftJoin('villages', 'activity_village.village_id', '=',
                'villages.id')
            ->select('projects.title',
                'staffs.name as manager',
                'provinces.title as province', 'districts.title as district',
                'subdistricts.title as subdistrict', 'villages.title as village',
                'activities.title as activity', DB::raw('date(stc_activities."activityDate") as activityDate'))
            ->whereRaw('date_part(\'year\', stc_activities."activityDate") = ?', [1 => $this->year]);

        return $query;
    }
}
