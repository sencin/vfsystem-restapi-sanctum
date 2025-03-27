<?php
 namespace App\Service;

use Illuminate\Http\Request;

 class CheckRequestHeaderService{
    public static function checkRequestHeader(Request $request):bool{
     return ($request->header('Accept') === 'application/json') ? true:false;
   }
 }
