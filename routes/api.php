<?php

use Illuminate\Http\Request;


/**
 * 测试接口
 */

Route::get('/xiafa','Api\XiafaController@xiafa');
Route::post('/remitSubmit','Api\XiafaController@remitSubmit');

/**
 * 回调函数
 */
Route::any('/notify_url','Api\NotifyController@notify_url');

