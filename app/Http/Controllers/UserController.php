<?php

namespace App\Http\Controllers;

use App\Jobs\async_function;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    function getUsers(){

        async_function::dispatch('probando xdaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');

        $users = User::all();
        return response()->json([
            'users' => $users
        ]);
    }

    // funcion de prueba //
    public function callback()
    {
        // echo 'funcionaaaa';
        return 'goooooooooooooooooood';
        
    }
}
