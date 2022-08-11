<?php

use App\Http\Controllers\BridgeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    //return redirect(route('login.sso'));
    return view('landing');
});

Route::get('/login-vendor', function () {
    return view('auth.login');
});

Route::get('/login-jkr', function() {
    return view('auth.login-jkr');
})->name('login.jkr');

Route::get('/bridge/form', 'BridgeController@view')->name('bridge.form');

Route::get('/road/form', 'PassageController@view')->name('road.form');

Route::get('/lookup/form', 'LookupController@view')->name('lookup.form');

Route::get('/road/list', function () {
    return view('inventory.passage-list');
})->name('road.list')->middleware('auth');

Route::get('/category/list', function () {
    return view('setting.category-list');
})->name('category.list')->middleware('auth');

Route::get('/lookup/list', function () {
    return view('setting.lookup-list');
})->name('lookup.list');

Route::get('/office/list', function () {
    return view('setting.office-list');
})->name('office.list');

Route::get('/category/edit', 'CategoryController@show')->name('category.edit');

Route::get('/lookup/edit', 'LookupController@show')->name('lookup.edit');

Route::get('/road/edit', 'PassageController@show')->name('road.edit');

Route::get('/route/form', 'RouteController@view')->name('route.form');

Route::get('/route/edit', 'RouteController@show')->name('route.edit');

Route::get('/route/list', 'RouteController@list')->name('route.list');

Route::get('/user/form', 'UserController@view')->name('user.form');

Route::get('/user/edit', 'UserController@show')->name('user.edit');

Route::get('/user/profile', 'UserController@profile')->name('user.profile');

Route::get('/category/form', function () {
    return view('setting.category-form');
})->name('category.form');

Route::get('/user/list', function () {
    return view('setting.user-list');
})->name('user.list');

Route::get('/password/change', function () {
    return view('auth.passwords.change');
})->name('password.change');

Auth::routes();

Route::get('/login-sso', function() {
    if (Auth::check()){
        return redirect('/bridge/view?action=list');
    }else{
        return view('auth.login-sso');
    }
})->name('login.sso');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/load/road','PassageController@loadRoad')->name('load.road');
Route::get('/load/user','UserController@loadUsers')->name('load.user');
Route::get('/load/category','CategoryController@loadCategory')->name('load.category');
Route::get('/load/lookup','LookupController@list')->name('load.lookup');
Route::get('/load/office','OfficeController@load')->name('load.office');

Route::get('/lookup/district','LookupController@lookupDistrict')->name('lookup.district');
Route::get('/lookup/office','LookupController@lookupOffice')->name('lookup.office');

Route::post('/save/route','RouteController@saveRoute')->name('save.route');
Route::post('/save/road','PassageController@saveRoad')->name('save.road');
Route::post('/save/bridge','BridgeController@saveBridge')->name('save.bridge');
Route::post('/save/user','UserController@saveUser')->name('save.user');
Route::post('/save/category','CategoryController@save')->name('save.category');
Route::post('/save/lookup','LookupController@save')->name('save.lookup');
Route::post('/save/inspect','InpectionController@save')->name('save.inspect');
Route::post('/save/office','OfficeController@save')->name('save.office');
Route::post('/delete/user','UserController@deleteUser')->name('delete.user');

Route::post('/hold/bridge','BridgeController@save')->name('hold.bridge');

Route::post('/delete/bridge','BridgeController@delete')->name('delete.bridge');
Route::post('/delete/inspect','InpectionController@delete')->name('delete.inspect');

Route::post('/update/route','RouteController@updateRoute')->name('update.route');
Route::post('/update/road','PassageController@updateRoad')->name('update.road');
Route::post('/update/category','CategoryController@update')->name('update.category');
Route::post('/update/user','UserController@update')->name('update.user');
Route::post('/update/lookup','LookupController@update')->name('update.lookup');
Route::post('/update/office','OfficeController@update')->name('update.office');

Route::post('/change/password','Auth\ChangePasswordController@change')->name('change.password');

Route::post('/search/route','RouteController@searchRoute')->name('search.route');
Route::post('/search/road','PassageController@searchRoad')->name('search.road');

Route::get('/bridge/view', 'BridgeController@display')->name('bridge.view');
Route::get('/bridge/edit', 'BridgeController@preview')->name('bridge.edit');
Route::get('/bridge/list', 'BridgeController@list')->name('bridge.list');
Route::get('/bridge/detail', 'BridgeController@detail')->name('bridge.detail');
Route::get('/bridge/year', 'BridgeController@edit')->name('bridge.year');
Route::get('/bridge/task', 'BridgeController@task')->name('bridge.task');
Route::get('/bridge/box','BridgeController@inbox')->name('bridge.box');
Route::post('/bridge/verify','BridgeController@verify')->name('bridge.verify');
Route::post('/bridge/approve','BridgeController@approve')->name('bridge.approve');
Route::post('/bridge/revert','BridgeController@revert')->name('bridge.revert');
Route::post('/bridge/add','BridgeController@add')->name('bridge.add');
Route::get('/bridge/review','BridgeController@review')->name('bridge.review');
Route::post('/alter/bridge','BridgeController@alter')->name('alter.bridge');
Route::post('/apply/bridge','BridgeController@apply')->name('apply.bridge');
Route::post('/bridge/remove','BridgeController@removeBridge')->name('remove.bridge');

Route::get('/inspect/form','InpectionController@show')->name('inspect.form');
Route::get('/inspect/user','InpectionController@fetchUser')->name('inspect.user');
Route::get('/inspect/view','InpectionController@display')->name('inspect.view');
Route::get('/inspect/list','InpectionController@list')->name('inspect.list');
Route::get('/inspect/task','InpectionController@task')->name('inspect.task');
Route::get('/inspect/show','InpectionController@view')->name('inspect.show');
Route::get('/inspect/result','InpectionController@reveal')->name('inspect.result');
Route::post('/inspect/approve','InpectionController@approve')->name('inspect.approve');
Route::get('/inspect/open','InpectionController@open')->name('inspect.open');
Route::get('/inspect/inbox','InpectionController@inbox')->name('inspect.inbox');
Route::post('/inspect/hold','InpectionController@hold')->name('inspect.hold');
Route::post('/inspect/submit','InpectionController@submit')->name('inspect.submit');
Route::post('/inspect/update','InpectionController@update')->name('inspect.update');

Route::post('/modify/user','UserController@modify')->name('modify.user');

Route::get('/office/add','OfficeController@show')->name('office.add');
Route::get('/office/edit','OfficeController@view')->name('office.edit');

Route::get('/test/mail','EmailTesterController@sendMail')->name('test.mail');
Route::get('/set/user/{ic}','EmailTesterController@createUser');
Route::get('/del/user/{ic}','EmailTesterController@deleteUser');
Route::post('/export/bridge','FileExportController@exportBridge')->name('export.bridge');
Route::post('/export/rating','FileExportController@exportRating')->name('export.rating');
Route::get('/export/detail','FileExportController@exportDetailRating')->name('export.detail-rating');
Route::get('/total/bridge','DashboardController@load_total_bridge')->name('total.bridge');
Route::get('/total/material', 'DashboardController@load_total_material')->name('total.material');
Route::get('/total/system', 'DashboardController@load_total_system')->name('total.system');
Route::get('/total/deck', 'DashboardController@load_total_deck')->name('total.deck');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return "Cache is cleared";
});
