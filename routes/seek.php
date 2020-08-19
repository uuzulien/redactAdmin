<?php


Route::group(['prefix' => 'api','namespace' => 'SeekData','middleware' => 'seek'], function () {
    // 获取小说的账户配置
    Route::get('book/get_account', 'Api\AccountController@getAccountConfigs')->name('api.book.get_account');
    Route::get('novel/get_pay_trend', 'Api\AccountController@getPayTrendData');
    // 获取订单明细url
    Route::get('book/create_url', 'Api\AccountController@getChannelId')->name('api.book.get_book');
    // 获取定时任务列表
    Route::get('task/list', 'Api\TaskController@getList')->name('api.task.get_list');
    // 获取冲突的主键
    Route::get('book/get_keys', 'Api\AccountController@getKeyExist')->name('api.book.get_keys');
//    Route::get('config/index', 'ConfigController@index')->name('api.config.index');
    // 对上传数据进行存储
    Route::put('store/datas', 'Api\StoreDataController@center');

});

