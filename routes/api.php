<?php

use Illuminate\Http\Request;


/**
 * 测试接口
 */
// Route::get('/xiafa','Api\XiafaController@xiafa');
// Route::post('/remitSubmit','Api\XiafaController@remitSubmit');

/**
 * 回调函数
 */
Route::any('/notify_url','Api\AppController@notify_url');


/**
 * 账户余额 && 下发接口
 */
Route::any('/balance/query/{merid?}','Api\AppController@BalanceQuery');
Route::post('/remitSubmit','Api\AppController@remitSubmit');

Route::any('/mail/send','Api\MailController@send');