<?php

Route::get('calculator', function(){
	echo 'Hello from the calculator package!';
});

Route::get('add/{a}/{b}', 'Equal\Account\CalculatorController@add');
Route::get('subtract/{a}/{b}', 'Equal\Account\CalculatorController@subtract');


// For JournalBook Entry
Route::get('jvlist', 'Equal\Account\jvmstController@show');
Route::get('addjv', 'Equal\Account\jvmstController@addjv')->middleware('checkuser:jvlist,add');
Route::post('addjv', 'Equal\Account\jvmstController@createjv');
Route::post('addjvmst', 'Equal\Account\jvmstController@createjvmst'); //new
Route::post('addjvdet', 'Equal\Account\jvmstController@createjvdet'); //new
Route::get('editjv/{id}', 'Equal\Account\jvmstController@editjv')->middleware('checkuser:jvlist,edit');
Route::get('deletejv/{id}', 'Equal\Account\jvmstController@deletejv')->middleware('checkuser:jvlist,del');
Route::post('checkjvserial', 'Equal\Account\jvmstController@checkjvserial');
Route::post('getjvserial', 'Equal\Account\jvmstController@getjvserial');
Route::get('printjv/{id}', 'Equal\Account\jvmstController@printjv');
Route::get('viewjv/{id}', 'Equal\Account\jvmstController@viewjv')->middleware('checkuser:jvlist,view');
