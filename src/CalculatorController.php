<?php

namespace Equal\Account;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CalculatorController extends Controller
{
    //
     //
     public function add($a, $b){
    	//echo $a + $b;

        $result = $a + $b;
        return view('calculator::add', compact('result'));
        
    }

    public function subtract($a, $b){
    	echo $a - $b;
    }
}
