<?php

Route::get('calculator', function(){
	echo 'Hello from the calculator package!';
});

Route::get('add/{a}/{b}', 'Equal\Account\CalculatorController@add');
Route::get('subtract/{a}/{b}', 'Equal\Account\CalculatorController@subtract');