<?php
class TotalReach
{
    /**
     * Get Total beneficiary by theme
     * @return Array
     */
    public static function getTotalBeneficiariesByTheme($themeid, $year)
    {
        // check if year/themeid is empty
        if (!$year || !$themeid) {
            return App::abort('404');
        }

        $theme = Theme::where('id','=',$themeid)->with('subthemes.projects.activities.beneficiaries.activities.project')->get(['id','title'])->first();
        // return $theme;
        $result['id'] = $theme->id;
        $result['theme'] = $theme->title;
        $result['subtheme'] = [];
        $result['adjustment'] = [];

        // Counter total beneficiary in theme
        $theme_total_girls = 0;
        $theme_total_boys = 0;
        $theme_total_women = 0;
        $theme_total_men = 0;

        // Counter adjustment in theme
        $theme_adjustment_girls = 0;
        $theme_adjustment_boys = 0;
        $theme_adjustment_women = 0;
        $theme_adjustment_men = 0;
        $beneficiaryInTheme = [];

        // Log beneficiary id in theme
        $boysInTheme = [];
        $girlsInTheme = [];
        $womenInTheme = [];
        $menInTheme = [];

        foreach ($theme->subthemes as $subtheme) {
            // counter total beneficiary in subtheme
            $subtheme_total_girls = 0;
            $subtheme_total_boys = 0;
            $subtheme_total_women = 0;
            $subtheme_total_men = 0;

            // counter adjustment beneficiary in subtheme
            $subtheme_adjustment_boys = 0;
            $subtheme_adjustment_girls = 0;
            $subtheme_adjustment_women = 0;
            $subtheme_adjustment_men = 0;
            $beneficiaryInSubtheme = [];

            // Get subtheme
            $subthemeResult['id'] = $subtheme->id;
            $subthemeResult['title'] = $subtheme->title;
            $subthemeResult['project'] = [];
            $subthemeResult['crossSubthemesIdCounter'] = [$subtheme->id];
            $subthemeResult['crossSubthemes'] = '';

            // Get Project
            foreach ($subtheme->projects as $project) {
                // filter only project with start-finish date within $year
                if ($project->startDate->year <= $year && $project->finishDate->year >= $year) {
                    $projectResult['id'] = $project->id;
                    $projectResult['title'] = $project->title;
                    $projectResult['locations'] = '';
                    foreach ($project->districts as $district) {
                        $projectResult['locations'] .= $district->province->title . ' - ' . $district->title . ', ';
                    }
                    $projectResult['sofCodes'] = $project->sofCodes;
                    $projectResult['scMembers'] = $project->scMembers;
                    $projectResult['sourcesOfData'] = $project->sourcesOfData;
                    $projectResult['assumption'] = $project->assumption;
                    $projectResult['crossSubthemes'] = '';
                    // Log project for cross project
                    $projectResult['crossProjectByBeneficiaries'] = [$project->id];
                    $projectResult['crossSofCodesByBeneficiaries'] = '';
                    $projectResult['beneficiaries'] = [];

                    // Get project subtheme which is not current subtheme and set cross subtheme
                    foreach ($project->subthemes()->wherePivot('subtheme_id', '!=', $subtheme->id)->get() as $projectSubtheme) {
                        $projectResult['crossSubthemes'] .= $projectSubtheme->theme->title . ' - ' . $projectSubtheme->title . ', ';
                    }
                    // Filter project subtheme by subtheme currently and subtheme id already counted
                    // pass subtheme result by reference, so we can change its value inside filter closure
                    $projectSubthemes = $project->subthemes->filter(function ($projectSubtheme) use (&$subthemeResult, &$projectResult) {
                        // Check if id not exist in counter
                        if (!in_array($projectSubtheme->id, $subthemeResult['crossSubthemesIdCounter'])) {
                            // push this ID to counter
                            array_push($subthemeResult['crossSubthemesIdCounter'], $projectSubtheme->id);
                            // Add to cross subthemes text
                            $subthemeResult['crossSubthemes'] .= $projectSubtheme->theme->title . ' - ' . $projectSubtheme->title . ', ';

                            return $projectSubtheme;
                        }
                    });

                    // Get project activity, so we can count project beneficiaries by age and sex
                    $project_boys = 0;
                    $project_girls = 0;
                    $project_women = 0;
                    $project_men = 0;

                    // Get all activities in this project within this subtheme
                    foreach ($project->activities as $activity) {
                        // filter only activity with date within $year and contain subthme
                        // we are checking (check in pivot table)
                        if ($activity->activityDate->year == $year && $activity->subthemes->contains($subtheme->id)) {
                            // count beneficiary for this activity
                            foreach ($activity->beneficiaries as $beneficiary) {
                                // Check if this beneficiary not counted yet in project (double counting/activity)
                                if (!in_array($beneficiary->id, $projectResult['beneficiaries'])) {

                                    // Log this beneficiary id
                                    array_push($projectResult['beneficiaries'], $beneficiary->id);

                                    // Get this beneficiary activity, and get its project.
                                    // Compare its project id with logged crossproject.
                                    // Then, get project sofcodes, add to project->crossproject->sofcodes
                                    foreach ($beneficiary->activities as $activity) {
                                        if (!in_array($activity->project->id, $projectResult['crossProjectByBeneficiaries'])) {
                                            array_push($projectResult['crossProjectByBeneficiaries'], $activity->project->id);
                                            // TODO: do we need to Check if this sofCodes not same with current project sofCodes?
                                            // DEBUG: get cross project title
                                            // $projectResult['crossSofCodesByBeneficiaries'] .= $activity->project->sofCodes . '; ' . $activity->project->title;
                                            $projectResult['crossSofCodesByBeneficiaries'] .= $activity->project->sofCodes . '; ';
                                        }
                                    }

                                    // Add beneficiary to counter (based on sex+age)
                                    if ($beneficiary->isBoy()) $project_boys++;
                                    if ($beneficiary->isGirl()) $project_girls++;
                                    if ($beneficiary->isWoman()) $project_women++;
                                    if ($beneficiary->isMan()) $project_men++;

                                    // Add beneficiary to subtheme adjustment
                                    // Check if this id already added to beneficiary in subtheme, if yes increase
                                    // subtheme_adjustment counter
                                    if (in_array($beneficiary->id, $beneficiaryInSubtheme)) {
                                        if ($beneficiary->isBoy()) $subtheme_adjustment_boys++;
                                        if ($beneficiary->isGirl()) $subtheme_adjustment_girls++;
                                        if ($beneficiary->isWoman()) $subtheme_adjustment_women++;
                                        if ($beneficiary->isMan()) $subtheme_adjustment_men++;
                                    } else {
                                        // if not in theme adjustment, just add it
                                        array_push($beneficiaryInSubtheme, $beneficiary->id);
                                    }

                                    // Add beneficiary to theme adjustment (use for substract from total theme)
                                    // Check if this id already added to beneficiary in theme, if yes increase
                                    // theme_adjustment counter
                                    if (in_array($beneficiary->id, $beneficiaryInTheme)) {
                                        if ($beneficiary->isBoy()) $theme_adjustment_boys++;
                                        if ($beneficiary->isGirl()) $theme_adjustment_girls++;
                                        if ($beneficiary->isWoman()) $theme_adjustment_women++;
                                        if ($beneficiary->isMan()) $theme_adjustment_men++;
                                    } else {
                                        // if not in theme adjustment, just add it
                                        array_push($beneficiaryInTheme, $beneficiary->id);
                                        // Log its id
                                        if ($beneficiary->isBoy()) array_push($boysInTheme, $beneficiary->id);
                                        if ($beneficiary->isGirl()) array_push($girlsInTheme, $beneficiary->id);
                                        if ($beneficiary->isWoman()) array_push($womenInTheme, $beneficiary->id);
                                        if ($beneficiary->isMan()) array_push($menInTheme, $beneficiary->id);

                                    }
                                }
                            }
                        }
                    }

                    // Set total beneficiary/age+sex
                    $projectResult['boys'] = $project_boys;
                    $projectResult['girls'] = $project_girls;
                    $projectResult['kids'] = $projectResult['girls'] + $projectResult['boys'];
                    $projectResult['women'] = $project_women;
                    $projectResult['men'] = $project_men;
                    $projectResult['adults'] = $projectResult['women'] + $projectResult['men'];
                    $projectResult['peoples'] = $projectResult['kids'] + $projectResult['adults'];

                    // Add total subtheme beneficiaries
                    $subtheme_total_girls += $projectResult['girls'];
                    $subtheme_total_boys += $projectResult['boys'];
                    $subtheme_total_women += $projectResult['women'];
                    $subtheme_total_men += $projectResult['men'];

                    // Push project to subtheme result
                    array_push($subthemeResult['project'], $projectResult);
                }
            }

            // push subtheme total
            $subthemeResult['total']['girls'] = $subtheme_total_girls;
            $subthemeResult['total']['boys'] = $subtheme_total_boys;
            $subthemeResult['total']['kids'] = $subthemeResult['total']['girls'] + $subthemeResult['total']['boys'];
            $subthemeResult['total']['women'] = $subtheme_total_women;
            $subthemeResult['total']['men'] = $subtheme_total_men;
            $subthemeResult['total']['adults'] = $subthemeResult['total']['women'] + $subthemeResult['total']['men'];
            $subthemeResult['total']['peoples'] = $subthemeResult['total']['kids'] + $subthemeResult['total']['adults'];

            // push Subtheme Adjustment
            $subthemeResult['adjustment']['girls'] = $subtheme_adjustment_girls;
            $subthemeResult['adjustment']['boys'] = $subtheme_adjustment_boys;
            $subthemeResult['adjustment']['kids'] = $subthemeResult['adjustment']['girls'] + $subthemeResult['adjustment']['boys'];
            $subthemeResult['adjustment']['women'] = $subtheme_adjustment_women;
            $subthemeResult['adjustment']['men'] = $subtheme_adjustment_men;
            $subthemeResult['adjustment']['adults'] = $subthemeResult['adjustment']['women'] + $subthemeResult['adjustment']['men'];
            $subthemeResult['adjustment']['peoples'] = $subthemeResult['adjustment']['kids'] + $subthemeResult['adjustment']['adults'];

            // push subtheme total after adjustment
            $subthemeResult['total_after']['girls'] = $subthemeResult['total']['girls'] - $subthemeResult['adjustment']['girls'];
            $subthemeResult['total_after']['boys'] = $subthemeResult['total']['boys'] - $subthemeResult['adjustment']['boys'];
            $subthemeResult['total_after']['kids'] = $subthemeResult['total_after']['girls'] + $subthemeResult['total_after']['boys'];
            $subthemeResult['total_after']['women'] = $subthemeResult['total']['women'] - $subthemeResult['adjustment']['women'];
            $subthemeResult['total_after']['men'] = $subthemeResult['total']['men'] - $subthemeResult['adjustment']['men'];
            $subthemeResult['total_after']['adults'] = $subthemeResult['total_after']['women'] + $subthemeResult['total_after']['men'];
            $subthemeResult['total_after']['peoples'] = $subthemeResult['total_after']['kids'] + $subthemeResult['total_after']['adults'];

            // Add theme total beneficiary
            $theme_total_girls += $subthemeResult['total_after']['girls'];
            $theme_total_boys += $subthemeResult['total_after']['boys'];
            $theme_total_women += $subthemeResult['total_after']['women'];
            $theme_total_men += $subthemeResult['total_after']['men'];

            // Substract theme_adjustment counter by subtheme_adjustment counter
            $theme_adjustment_boys -= $subtheme_adjustment_boys;
            $theme_adjustment_girls -= $subtheme_adjustment_girls;
            $theme_adjustment_women -= $subtheme_adjustment_women;
            $theme_adjustment_men -= $subtheme_adjustment_men;

            // Push subtheme to result
            array_push($result['subtheme'], $subthemeResult );
        }

        // Push Theme Total to $result
        $result['total']['girls'] = $theme_total_girls;
        $result['total']['boys'] = $theme_total_boys;
        $result['total']['kids'] = $result['total']['girls'] + $result['total']['boys'];
        $result['total']['women'] = $theme_total_women;
        $result['total']['men'] = $theme_total_men;
        $result['total']['adults'] = $result['total']['women'] + $result['total']['men'];
        $result['total']['peoples'] = $result['total']['kids'] + $result['total']['adults'];

        // Push Theme Adjustment to $result
        $result['adjustment']['girls'] = $theme_adjustment_girls;
        $result['adjustment']['boys'] = $theme_adjustment_boys;
        $result['adjustment']['kids'] = $result['adjustment']['girls'] + $result['adjustment']['boys'];
        $result['adjustment']['women'] = $theme_adjustment_women;
        $result['adjustment']['men'] = $theme_adjustment_men;
        $result['adjustment']['adults'] = $result['adjustment']['women'] + $result['adjustment']['men'];
        $result['adjustment']['peoples'] = $result['adjustment']['kids'] + $result['adjustment']['adults'];

        // Push Theme Total after adjusment to $result
        $result['total_after']['girls'] = $result['total']['girls'] - $result['adjustment']['girls'];
        $result['total_after']['boys'] = $result['total']['boys'] - $result['adjustment']['boys'];
        $result['total_after']['kids'] = $result['total_after']['girls'] + $result['total_after']['boys'];
        $result['total_after']['women'] = $result['total']['women'] - $result['adjustment']['women'];
        $result['total_after']['men'] = $result['total']['men'] - $result['adjustment']['men'];
        $result['total_after']['adults'] = $result['total_after']['women'] + $result['total_after']['men'];
        $result['total_after']['peoples'] = $result['total_after']['kids'] + $result['total_after']['adults'];

        // Push beneficiary id
        $result['beneficiaries']['all'] = $beneficiaryInTheme;
        $result['beneficiaries']['girls'] = $girlsInTheme;
        $result['beneficiaries']['boys'] = $boysInTheme;
        $result['beneficiaries']['women'] = $womenInTheme;
        $result['beneficiaries']['men'] = $menInTheme;

        return $result;
    }

    /**
     * Get summary form value for all theme
     * @return array
     */
    public static function getSummaryForm($year)
    {
        $result = array();
        $result['themes'] = [];

        // Counter for adjustment within summary
        $summary_adjustment_girls = 0;
        $summary_adjustment_boys = 0;
        $summary_adjustment_women = 0;
        $summary_adjustment_men = 0;

        // Counter to log total beneficiary in summary
        $summary_total_girls = 0;
        $summary_total_boys = 0;
        $summary_total_women = 0;
        $summary_total_men = 0;

        // Variable to log beneficiary which has been counted
        $beneficiaryInSummary = [];
        $girlsInSummary = [];
        $boysInSummary = [];
        $womenInSummary = [];
        $menInSummary = [];

        foreach (Theme::all() as $theme) {
            // Get beneficiary by theme and year
            $themeTotal = self::getTotalBeneficiariesByTheme($theme->id, $year);

            $themeResult = array(
                'id'=>$theme->id,
                'title'=>$theme->title
            );
            $themeResult['total'] = [];

            $theme_girls = 0;
            $theme_boys = 0;
            $theme_women = 0;
            $theme_men = 0;

            // Loop theme>subtheme to get total beneficiary by subtheme project
            foreach ($themeTotal['subtheme'] as $subtheme) {
                $subtheme_girls = 0;
                $subtheme_boys = 0;
                $subtheme_women = 0;
                $subtheme_men = 0;
//
                // Get total girl, boy, women & men for every project
                foreach ($subtheme['project'] as $project) {
                    $subtheme_girls += $project['girls'];
                    $subtheme_boys += $project['boys'];
                    $subtheme_women += $project['women'];
                    $subtheme_men += $project['men'];
                }
//
                // substract by subtheme adjustment and add to theme girl, boy, women, man counter
                // (substract total beneficiary by subtheme adjusment)
                $theme_girls += $subtheme_girls - $subtheme['adjustment']['girls'];
                $theme_boys  += $subtheme_boys - $subtheme['adjustment']['boys'];
                $theme_women += $subtheme_women - $subtheme['adjustment']['women'];
                $theme_men += $subtheme_men - $subtheme['adjustment']['men'];
            }
//
            // Get total beneficiary within theme (substract total beneficiary by theme adjusment)
            $theme_total_girls = $theme_girls - $themeTotal['adjustment']['girls'];
            $theme_total_boys = $theme_boys - $themeTotal['adjustment']['boys'];
            $theme_total_women = $theme_women - $themeTotal['adjustment']['women'];
            $theme_total_men = $theme_men - $themeTotal['adjustment']['men'];
//
            // push total beneficiary to themeResult with key 'total'
            $themeResult['total']['girls'] = $theme_total_girls;
            $themeResult['total']['boys'] = $theme_total_boys;
            $themeResult['total']['kids'] = $themeResult['total']['girls'] + $themeResult['total']['boys'];
            $themeResult['total']['women'] = $theme_total_women;
            $themeResult['total']['men'] = $theme_total_men;
            $themeResult['total']['adults'] = $themeResult['total']['women'] + $themeResult['total']['men'];
            $themeResult['total']['peoples'] = $themeResult['total']['kids'] + $themeResult['total']['adults'];
//
            // Increment total beneficiary in summary
            $summary_total_girls += $theme_total_girls;
            $summary_total_boys += $theme_total_boys;
            $summary_total_women += $theme_total_women;
            $summary_total_men += $theme_total_men;
//
            // Push themeResult to $result with key 'themes'
            array_push($result['themes'], $themeResult);
//
            // Get beneficiary id not exist in summary, use function in backendHelpers
            $diffBeneficiary = flip_isset_diff($themeTotal['beneficiaries']['all'], $beneficiaryInSummary);
            $diffGirls = flip_isset_diff($themeTotal['beneficiaries']['girls'], $girlsInSummary);
            $diffBoys = flip_isset_diff($themeTotal['beneficiaries']['boys'], $boysInSummary);
            $diffWomen = flip_isset_diff($themeTotal['beneficiaries']['women'], $womenInSummary);
            $diffMen = flip_isset_diff($themeTotal['beneficiaries']['men'], $menInSummary);
//
            // Add adjustment to summary (substact total by total beneficiary not in girlsSummary/or other)
            $summary_adjustment_girls += count($themeTotal['beneficiaries']['girls']) - count($diffGirls);
            $summary_adjustment_boys += count($themeTotal['beneficiaries']['boys']) - count($diffBoys);
            $summary_adjustment_women += count($themeTotal['beneficiaries']['women']) - count($diffWomen);
            $summary_adjustment_men += count($themeTotal['beneficiaries']['men']) - count($diffMen);

            // push beneficiary id to beneficiaryInSummary
            foreach ($diffBeneficiary as $diff) {
                array_push($beneficiaryInSummary, $diff);
            }
            // push beneficiary to girl in summary
            foreach ($diffGirls as $diff) {
                array_push($girlsInSummary, $diff);
            }
            // push beneficiary to boy in summary
            foreach ($diffBoys as $diff) {
                array_push($boysInSummary, $diff);
            }
            // push beneficiary to women in summary
            foreach ($diffWomen as $diff) {
                array_push($womenInSummary, $diff);
            }
            // push beneficiary to men in summary
            foreach ($diffMen as $diff) {
                array_push($menInSummary, $diff);
            }
        }
        // Push all beneficiary id to $result
        $result['beneficiaries']['all'] = $beneficiaryInSummary;
        $result['beneficiaries']['girls'] = $girlsInSummary;
        $result['beneficiaries']['boys'] = $boysInSummary;
        $result['beneficiaries']['women'] = $womenInSummary;
        $result['beneficiaries']['men'] = $menInSummary;

        // Push total count benficiary in summary to $result
        $result['total']['girls'] = $summary_total_girls;
        $result['total']['boys'] = $summary_total_boys;
        $result['total']['kids'] = $result['total']['girls'] + $result['total']['boys'];
        $result['total']['women'] = $summary_total_women;
        $result['total']['men'] = $summary_total_men;
        $result['total']['adults'] = $result['total']['women'] + $result['total']['men'];
        $result['total']['peoples'] = $result['total']['kids'] + $result['total']['adults'];

        // push summary adjustment count
        $result['adjustment']['girls'] = $summary_adjustment_girls;
        $result['adjustment']['boys'] = $summary_adjustment_boys;
        $result['adjustment']['kids'] = $result['adjustment']['girls'] + $result['adjustment']['boys'];
        $result['adjustment']['women'] = $summary_adjustment_women;
        $result['adjustment']['men'] = $summary_adjustment_men;
        $result['adjustment']['adults'] = $result['adjustment']['women'] + $result['adjustment']['men'];
        $result['adjustment']['peoples'] = $result['adjustment']['kids'] + $result['adjustment']['adults'];

        // Push total beneficiary count after adjustment in summary to $result
        $result['total_after']['girls'] = $summary_total_girls - $summary_adjustment_girls;
        $result['total_after']['boys'] = $summary_total_boys - $summary_adjustment_boys;
        $result['total_after']['kids'] = $result['total_after']['girls'] + $result['total_after']['boys'];
        $result['total_after']['women'] = $summary_total_women - $summary_adjustment_women;
        $result['total_after']['men'] = $summary_total_men - $summary_adjustment_men;
        $result['total_after']['adults'] = $result['total_after']['women'] + $result['total_after']['men'];
        $result['total_after']['peoples'] = $result['total_after']['kids'] + $result['total_after']['adults'];

        return $result;
    }

    /**
     * Get total beneficiary after double counting for project
     * The logic in here is different from above. In this function, we will not
     * log total double counting. We only need total after double counting.
     * @param  int $projectId Id of project
     * @return array
     */
    public static function getTotalReachByProject($projectId)
    {
        $result = array();
        $project = Project::find($projectId);
        // Lazy Load activities for performance
        $project->load('activities');
        $beneficiaryInProject = [];
        $menInProject = 0;
        $womenInProject = 0;
        $boysInProject = 0;
        $girlsInProject = 0;

        foreach ($project->activities as $activity) {
            $activity->load('beneficiaries');
            foreach ($activity->beneficiaries as $beneficiary) {
                if (!in_array($beneficiary->id, $beneficiaryInProject)) {
                    // if not in theme adjustment, just add it
                    array_push($beneficiaryInProject, $beneficiary->id);
                    // Log its id
                    if ($beneficiary->isBoy()) $boysInProject++;
                    if ($beneficiary->isGirl()) $girlsInProject++;
                    if ($beneficiary->isWoman()) $womenInProject++;
                    if ($beneficiary->isMan()) $menInProject++;
                }
            }
        }


        // loop all activity
        // get all beneficiary
        // distract double counting by activity
        $result['men'] = $menInProject;
        $result['women'] = $womenInProject;
        $result['boys'] = $boysInProject;
        $result['girls'] = $girlsInProject;
        return $result;
    }

    /**
     * Get total beneficiaries before double counting
     * @param  int $year
     * @return array
     */
    public static function getTotalBeneficiariesByYear($year)
    {
        $result['men'] = 0;
        $result['women'] = 0;
        $result['boys'] = 0;
        $result['girls'] = 0;

        // eager load beneficiaries for performance
        $activities = Activity::with('beneficiaries')->ofYear($year);
        foreach ($activities as $activity) {
            $beneficiaries = $activity->beneficiaries;
            foreach ($beneficiaries as $beneficiary) {
                if ($beneficiary->isMan()) {
                    $result['men']++;
                } elseif ($beneficiary->isWoman()) {
                    $result['women']++;
                } elseif ($beneficiary->isBoy()) {
                    $result['boys']++;
                } elseif ($beneficiary->isGirl()) {
                    $result['girls']++;
                }
            }
        }

        return $result;
    }

    public static function getProjectCoverageByYear($year)
    {
        // eager load subdistricts,villages for performance
        $activities = Activity::with('subdistricts.district.province','villages')->ofYear($year);
        $result['provinceCount'] = 0;
        $result['districtCount'] = 0;
        $result['subdistrictCount'] = 0;
        $result['villageCount'] = 0;

        $result['activityIds'] = array();
        $result['provinceIds'] = array();
        $result['districtIds'] = array();
        $result['subdistrictIds'] = array();
        $result['villageIds'] = array();

        foreach ($activities as $activity) {
            // count village with double counting
            foreach ($activity->villages as $village) {
                if (!in_array($village->id, $result['villageIds'])) {
                    array_push($result['villageIds'], $village->id);
                    $result['villageCount']++;
                }
            }
            // count subdistrict, district, province with double counting
            foreach ($activity->subdistricts as $subdistrict) {
                if (!in_array($subdistrict->id, $result['subdistrictIds'])) {
                    array_push($result['subdistrictIds'], $subdistrict->id);
                    $result['subdistrictCount']++;

                    // count district with double counting, same subdistrict already skipped
                    $district = $subdistrict->district;
                    if (!in_array($district->id, $result['districtIds'])) {
                        array_push($result['districtIds'], $subdistrict->district->id);
                        $result['districtCount']++;

                        // count province with double counting, same district already skipped
                        $province = $subdistrict->district->province;
                        if (!in_array($province->id, $result['provinceIds'])) {
                            array_push($result['provinceIds'], $province->id);
                            $result['provinceCount']++;
                        }
                    }
                }
            }
            // end: count subdistrict, district, province with double counting
        }
        return $result;
    }

}

