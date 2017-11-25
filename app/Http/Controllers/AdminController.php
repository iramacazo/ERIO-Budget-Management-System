<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllUsers(){
        $listOfUsers = User::all()->sortByDesc('email');
        return view('listOfUsers', ['users' => $listOfUsers]);
    }

    public function createUser(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'usertype' => 'required'
        ]);
        $password = hash($request->password);
        $user = User::create();
        $user->save();
    }
}
