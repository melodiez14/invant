<?php

function build_dashboard_nav()
{
    $html = '';
    $roles= Auth::user()->rolegroup->roles;

    $menu = array(

        // Dashboard
        array(
          'icon' => 'fa fa-dashboard icon',
          'icon-bg' => 'bg-info stc',
          'title' => trans('dashboard.dashboard'),
          'small' => true,
          'url' => URL::to('dashboard')
        ),
        // BEGIN Account
        array(
          'icon' => 'fa fa-group icon',
          'icon-bg' => 'bg-info stc',
          'title' => trans('user.user_man'),
          'url' => '#',
          'authorized'  => (Role::isAuthorized($roles, 'users.index')
                            || Role::isAuthorized($roles, 'rolegroups.index'))
                            || Role::isAuthorized($roles, 'modules.index'),
          'subnav' => array(
              array(
                'url' => route('users.index'),
                'title' => trans('user.users'),
                'authorized'  => Role::isAuthorized($roles, 'users.index')
              ),
              array(
                'url' => route('rolegroups.index'),
                'title' => trans('user.rolegroups'),
                'authorized'  => Role::isAuthorized($roles, 'rolegroups.index')
              ),
              array(
                'url'     => route('modules.index'),
                'authorized'  => Role::isAuthorized($roles, 'modules.index'),
                'title'   => trans('module.modules'),
            ),
          )
        ),
        // END Account
    );

    foreach($menu as $key => $nav)
    {

        $hasSubnav  = (isset($nav['subnav']));
        $isSmall    = (isset($nav['small'])) ? ($nav['small'] === true) : false;

        if(isset($nav['authorized']) && !$nav['authorized'])
            continue;

        $list       = "<li>" .
                        "<a href=\"".$nav['url']."\">" .
                            "<i class=\"".$nav['icon']."\">" .
                                "<b class=\"".$nav['icon-bg']."\"></b>" .
                            "</i>";
        $list       .= (($isSmall) ? "<span class=\"text-xss\">" : "<span>") . $nav['title'] . "</span>";

        if($hasSubnav) {
            $list   .= "<span class=\"pull-right\">" .
                            "<i class=\"fa fa-angle-down text\"></i>" .
                            "<i class=\"fa fa-angle-up text-active\"></i>" .
                       "</span></a>";
            $list   .= "<ul class=\"nav lt\">";

            foreach ($nav['subnav'] as $subnav)
            {
                if(isset($subnav['authorized']) && !$subnav['authorized'])
                    continue;

                $list .= "<li><a href=\"" .$subnav['url']. "\"><i class=\"fa fa-angle-right\"></i>" .
                            "<span>" . $subnav['title'] . "</span>" .
                         "</a></li>";
            }

            $list   .= "</ul></li>";

        } else {
            $list   .= "</a></li>";
        }

        $html .= $list;

    }

    return $html;
}

function delete_btn($action)
{
    return '<form action="' .$action. '" method="POST" class="form-inline" style="display: inline">' .
                csrf_field() .
                method_field('delete') .
                '<button type="submit" class="hidden"></button>' .
                '<a href="#" data-confirm="' .trans('action.delete_confirmation'). '" class="m-l-sm js-delete-confirm">' .
                    '<i class="fa fa-times fa-hover" data-toggle="tooltip" data-placement="top" title="' .trans('action.delete_tooltip'). '"></i>' .
                '</a>' .
            '</form>';
}

function csrf_field()
{
    return "<input type=\"hidden\" name=\"_token\" value=\"".csrf_token()."\"/>";
}

function method_field($method)
{
    return "<input type=\"hidden\" name=\"_method\" value=\"".$method."\"/>";
}

function readable_date($date, $dateFormat = "id", $short = false, $showClock = true, $clockFormat = 24)
{
    $months = [
        '01'    => 'jan',
        '02'    => 'feb',
        '03'    => 'mar',
        '04'    => 'apr',
        '05'    => 'may',
        '06'    => 'jun',
        '07'    => 'jul',
        '08'    => 'aug',
        '09'    => 'sep',
        '10'    => 'oct',
        '11'    => 'nov',
        '12'    => 'dec'
    ];

    $tstmpParse = explode(" ", $date->toDateTimeString());
    $dateParse  = explode("-", $tstmpParse[0]);

    if (count($tstmpParse) < 2)
        $showClock = false;

    $monthIndex = 'date.' . $months[$dateParse[1]];

    if ($short === true)
        $monthIndex .= "_short";

    if ($dateFormat === 'id') {
        $dateDisplay = intval($dateParse[2]) . " " . trans($monthIndex) . " " . $dateParse[0];
    } else {
        $dateDisplay = trans($monthIndex) . ", " . intval($dateParse[2]) . " " . $dateParse[0];
    }

    if ($showClock === true) {

        $tstmp = strtotime($date);

        if ($clockFormat === 12) {
            $dateDisplay .= " " . date("h:i:s A", $tstmp);
        } else {
            $dateDisplay .= " " . date("H:i:s", $tstmp);
        }

    }

    return $dateDisplay;

}

function adv_snake_case($words)
{
    $nosymbols = preg_replace_callback("/[-!$%^&*()_+|~=`{}\[\]:\";'<>?,.\/]/", function ($str) {
        return null;
    }, strtolower($words));

    $result = preg_replace_callback('/\s+/', function ($str) {
        return "_";
    }, $nosymbols);

    if(empty(trim($result)))
        return strtolower($words);

    return $result;
}

/**
 * Function to check browser type is chrome
 * @return boolean
 */
function isChrome()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * For debugging sometihng, readable version
 * @param  mixed $var accept all
 * @return void
 */
function debugVar($var)
{
    echo "<pre style='margin-top: 50px'>";
    print_r($var);
    echo "</pre>";
    die();
}

/**
 * Memeriksa apakah user yang telah login memiliki kemampuan X di modul tertentu
 * @param string $ability isinya 'read', 'create', 'update', atau 'delete'
 * @param string $module_alias isinya alias di database untuk masing-masing modul
 * @return boolean
 */
function isXUser($ability, $module_alias)
{
    if(!Auth::check())
        return false;

    $user= Auth::user();
    $rgid= $user->rolegroup_id;

    $abl = "X" . strtoupper($ability);

    $role = Role::where('rolegroup_id', $rgid)->where('role_ability', $abl)
    ->whereHas('module', function($query) use($module_alias) {
        $query->where('module_alias', $module_alias);
    })->first();

    return isset($role->module->id);
}

/**
 * Custom array diff function for very large array (compare b to a)
 * This is the simple algorithm:
 * 1. Flip 2nd array. Values will become keys. So repeated values will be discarded.
 * 2. Check for each element in 1st array if it exists in 2nd array.
 * http://stackoverflow.com/questions/8826908/best-way-to-find-differences-between-two-large-arrays-in-php
 * @param  array $b
 * @param  array $a
 * @return array    array not exist in $a but exist in $b
 */
function flip_isset_diff($b, $a)
{
    $at = array_flip($a);
    $d = array();
    foreach ($b as $i)
        if (!isset($at[$i]))
            $d[] = $i;

    return $d;
}

/**
 * Push value to array if not exist in given array
 * @param  array  $array
 * @return reference
 */
function array_push_if_not_exist(array &$array = array(), $val)
{
    if (!in_array($val, $array)) {
        array_push($array, $val);
    }
}

/**
 * save PHPExcel object to disk as .xls or .xlsx
 * @param  PHPExcel $excel
 * @param  string   $filepath path file with filename
 * @return boolean  file saved
 */
function saveExcel(PHPExcel $excel, $filepath)
{
    $ext = pathinfo($filepath, PATHINFO_EXTENSION);

    switch ($ext) {

        case 'xlsx':
            $writerType = 'Excel2007';

            break;

        case 'xls':
        default:
            $writerType = 'Excel5';

            break;

    }


    try {
        $objWriter = \PHPExcel_IOFactory::createWriter($excel, $writerType);
        $objWriter->save($filepath);
    } catch (Exception $e) {
        App::abort('500', "Error writing file ".pathinfo($filepath,PATHINFO_BASENAME).": ".$e->getMessage());
    }

    return true;
}

/**
 * save PHPExcel object as direct download to user
 * @param  PHPExcel $excel
 * @param  string   $filepath path file with filename
 * @return boolean  file saved
 */
function downloadExcel(PHPExcel $excel, $filename)
{

    $ext = pathinfo($filename, PATHINFO_EXTENSION);

    switch ($ext) {

        case 'xlsx':
            $writerType = 'Excel2007';
            $contentType= 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            break;

        case 'xls':
        default:
            $writerType = 'Excel5';
            $contentType= 'application/vnd.ms-excel';
            break;

    }


    $objWriter = \PHPExcel_IOFactory::createWriter($excel, $writerType);

    header("Content-Type: ".$contentType);
    header("Content-Disposition: attachment; filename=".$filename);
    header("Expires: 0");
    header("Cache-Control: max-age=0, must-revalidate, post-check=0, pre-check=0");
    header("Pragma: public");
    // We'll be outputting an excel file
    // header('Content-type: application/vnd.ms-excel');

    // It will be called file.xls
    // header('Content-Disposition: attachment; filename="'.$filename);

    // Write file to the browser
    return $objWriter->save('php://output');

}

function getForex()
{
    return array(
        array('id' => 'AUD','name' => "Austrailia Dollar"),
        array('id' => 'DKK','name' => "Danish Krone"),
        array('id' => 'EUR','name' => "EURO"),
        array('id' => 'GBP','name' => "Great Britain Pounds"),
        array('id' => 'HKD','name' => "Hong Kong Dollar"),
        array('id' => 'IDR','name' => "Indonesian Rupiah"),
        array('id' => 'JPY','name' => "Japan Yen"),
        array('id' => 'KRW','name' => "South Korean Won"),
        array('id' => 'NZD','name' => "New Zealand Dollar"),
        array('id' => 'SEK','name' => "Sweden Krona"),
        array('id' => 'USD','name' => "United States Dollar")
    );
}

function toPercent($number)
{
  return number_format($number, 2)*100;
}

function getInitials($name)
{
  $words = explode(" ", $name);
  $acronym = "";

  foreach ($words as $w) {
    $acronym .= $w[0];
  }
  return $acronym;
}

/**
* Convert BR tags to nl
*
* @param string The string to convert
* @return string The converted string
*/
function strip_br($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "", $string);
}
