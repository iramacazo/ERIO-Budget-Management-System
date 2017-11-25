<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getAllUsers(){
        $listOfUsers = User::all()->sortBy('usertype');
        return view('listOfUsers', ['users' => $listOfUsers]);
    }

    public function createUser(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'usertype' => 'required'
        ]);
        $user = new User;
        $user->fill(['name' => $request->name, 'email' => $request->email,
                    'password' => Hash::make($request->password), 'usertype' => $request->usertype]);
        $user->save();
        return redirect('all-users')->with('newuser', $request->email);
    }
}
