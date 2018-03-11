<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showWelcome');
Route::get('login', 'HomeController@getLogin');
Route::post('login', 'HomeController@postLogin');
Route::get('logout', 'HomeController@getLogout');

// /* Model Binding */
Route::model('modules', 'Module');
Route::model('rolegroups', 'Rolegroup');
Route::model('uploads', 'Upload');
Route::model('users', 'User');
Route::model('customers', 'Customer');

/* Outside Resource Controller*/
Route::post('/language', array(
    'as' => 'language_chooser', 'uses' => 'LanguagesController@chooser'
));


Route::group(['before' => 'authorize'], function() {
    Route::get('dashboard', ['as' => 'dashboard.index', 'uses' => "DashboardController@getDashboard"]);
    
    /* Resource Controllers */
    Route::resource('libraries', 'LibrariesController');
    Route::resource('modules', 'ModuleController');
    Route::resource('rolegroups', 'RolegroupsController');
    Route::resource('uploads', 'UploadController');
    Route::resource('users', 'UsersController');
    Route::resource('currencies', 'CurrenciesController');
    
    Route::resource('customers', 'CustomersController');
    Route::get('api/customers',
        array('as'=>'customers.index', 'uses'=>'CustomersController@getDatatable'));
});

// Route::controller('locations', 'LocationsController');
// Route::controller('file', 'FileController');

// // API for datatable
// Route::get('api/users',
//     array('as'=>'api.users', 'uses'=>'UsersController@getDatatable'));
// Route::get('api/staffdetails',
//     array('as'=>'api.staffdetails', 'uses'=>'StaffController@getStaffDetails'));
// Route::get('api/projects',
//     array('as'=>'api.projects', 'uses'=>'ProjectsController@getDatatable'));
// Route::get('api/projectdetails',
//     array('as'=>'api.projectdetails', 'uses'=>'ProjectsController@getProjectDetails'));
// Route::get('api/activitylists',
//     array('as'=>'api.activitylists', 'uses'=>'ActivitiesController@getActivityLists'));
// Route::get('api/beneficiary/unregisteredactivities',
//     array('as'=>'api.unregisteredactivities', 'uses'=>'BeneficiariesController@getUnregisteredActivities'));
// Route::get('api/themes',
//     array('as'=>'api.themes', 'uses'=>'ThemesController@getDatatable'));
// Route::get('api/libraries/{type}',
//     array('as'=>'api.libraries', 'uses'=>'LibrariesController@getDatatable'));
// Route::get('api/beneficiaries',
//     array('as'=>'api.beneficiaries', 'uses'=>'BeneficiariesController@getDatatable'));
// Route::get('api/activities',
//     array('as'=>'api.activities', 'uses'=>'ActivitiesController@getDatatable'));
// Route::get('api/activitytypelists',
//     array('as'=>'api.activitytypelists', 'uses'=>'ActivitytypesController@getLists'));
// Route::get('api/subthemelists',
//     array('as'=>'api.subthemelists', 'uses'=>'SubthemesController@getLists'));
// Route::get('api/projectsubdistrictlists',
//     array('as'=>'api.projectsubdistrictlists', 'uses'=>'ProjectsController@getSubdistrictLists'));
// Route::get('api/beneficiaries/{activities}',
//     array('as'=>'api.beneficiaryfromactivity', 'uses'=>'ActivitiesController@getDatatableBeneficiary'));
// Route::get('api/reports/{type}',
//     array('as'=>'api.reports', 'uses'=>'ReportsController@getDatatable'));