<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use App\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller{
    
    use Helpers;

    public function __construct(){

    }

    public function index(){
    	$currentUser = JWTAuth::parseToken()->authenticate();

        return $currentUser->users()->toArray();
    }
}