<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Modules\ReportsKsnb\Http\Middleware\Authorization;
//use Illuminate\Support\Facades\Route;
use Modules\ViewCpanel\Http\Middleware\KsnbValid;
use Modules\ViewCpanel\Http\Middleware\TokenIsValid;


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

Route::prefix('/reportsksnb')->group(function() {

    Route::get('/', function () {
        echo("Wellcome to module KSNB");
    });



    Route::get('/listReports', 'ReportsKsnbController@listReport');
    Route::post('/saveReport', 'ReportsKsnbController@saveReport');
    Route::post('/updateReport/{id}', 'ReportsKsnbController@updateReport');
    Route::post('/updateProcess/{id}', 'ReportsKsnbController@updateProcess');
    Route::post('/updateEmailNotConfrim/{id}', 'ReportsKsnbController@updateEmailNotConfrim');
    Route::post('/updateEmailReConfrim/{id}', 'ReportsKsnbController@updateEmailReConfrim');
    Route::post('/updateInfer/{id}', 'ReportsKsnbController@updateInfer');
    Route::post('/sendfeedback/{id}', 'ReportsKsnbController@sendfeedback');
    Route::post('/getEmailCHT', 'ReportsKsnbController@getEmailCht');
    Route::post('/detail_report/{id}', 'ReportsKsnbController@detailReport');
    Route::post('/getemail/','ReportsKsnbController@getUserByEmail');
    Route::post('/getCodeByType','KsnbCodeErrorsController@getCodeByType');
    Route::post('/getPunishmentByCode','KsnbCodeErrorsController@getPunishmentByCode');
    Route::post('/getDisciplineByCode','KsnbCodeErrorsController@getDisciplineByCode');
    Route::post('/getDescription','KsnbCodeErrorsController@getDescription');
    Route::post('/getNameByEmail','KsnbCodeErrorsController@getNameByEmail');
    Route::post('/ksnbFeedback/{id}','ReportsKsnbController@ksnbFeedback');
    Route::post('/waitInfer/{id}','ReportsKsnbController@waitInfer');

    Route::post('/getEmployeesByStoreId','KsnbCodeErrorsController@getEmployeesByStoreId');

    Route::post('/getEmailCHTByStoreId','KsnbCodeErrorsController@getEmailCHTByStoreId');
    Route::post('/updateEmailWaitConfrim/{id}','ReportsKsnbController@getEmailWaitConfrim');




    Route::post('/role_ksnb','ReportsKsnbController@ksnb_validate_two');
    Route::post('/role_kd_user/{id}','ReportsKsnbController@ksnb_validate_three');
    Route::post('/role_kd_user_one/{id}','ReportsKsnbController@ksnb_validate_four');
    Route::post('/all_user_ksnb/{id}','ReportsKsnbController@all_user_ksnb')->middleware(TokenIsValid::class,\Modules\ViewCpanel\Http\Middleware\Ksnb_Tbp_valid::class);
    Route::post('/get_list_ksnb','ReportsKsnbController@get_list_ksnb');

    Route::post('/allMailRoll','ReportsKsnbController@allMailRoll');
    Route::post('/getAllRoom','ReportsKsnbController@getAllRoom');
    Route::post('/getByEmailCaptionHo','ReportsKsnbController@getByEmailCaptionHo');
    Route::post('/getEmailCHTByStoreId','ReportsKsnbController@getEmailCHTByStoreId');

    Route::post('/getErrorCodeInfo','KsnbCodeErrorsController@getErrorCodeInfo');
    Route::post('/cancelRpNv/{id}','ReportsKsnbController@cancelRp');
    Route::post('/cancelRpTbp/{id}','ReportsKsnbController@cancelRpTbp');
    Route::post('/endTime','ReportsKsnbController@endTime');
    Route::post('/getEMailEndTime','ReportsKsnbController@getEMailEndTime');


});

Route::prefix('ksnb_errors')->group(function (){

    Route::get('/', function () {
        echo("Wellcome to module KSNB");
    });
    Route::post('/list_errors','KsnbCodeErrorsController@list');
    Route::post('/create_errors','KsnbCodeErrorsController@create');
    Route::post('/update_errors/{id}','KsnbCodeErrorsController@update');
    Route::post('/show_errors/{id}', 'KsnbCodeErrorsController@show');
    Route::post('/status/{id}','KsnbCodeErrorsController@status');
    Route::post('/ksnb','KsnbCodeErrorsController@getEmailCHTByStoreId');
});
