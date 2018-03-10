<?php

class LocationsController extends BaseController
{
    public function __construct()
    {
        if ( ! Request::ajax()) {
            return App::abort(403);
        }
    }

    public function getProvinces()
    {
        return Province::all()->toArray();
    }

    public function getDistricts()
    {
        return District::where('province_id', Input::get('province_id'))->get();
    }

    public function getSubdistricts()
    {
        if (Input::has('project_id')) {
            $project = Project::find(Input::get('project_id'));
            $districts = $project->districts->toArray();
            $district_id = array();
            foreach ($districts as $district) {
                $district_id[] = $district['id'];
            }
        } else {
            $district_id = array(Input::get('district_id'));
        }
        return Subdistrict::whereIn('district_id', $district_id)->get();
    }

    public function getVillages()
    {
        return Village::where('subdistrict_id', Input::get('subdistrict_id'))->get();
    }
}
