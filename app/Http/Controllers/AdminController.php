<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getAllUsers(){
        $listOfUsers = User::all()->sortBy('usertype');
        return view('listOfUsers', ['users' => $listOfUsers]);
    }

    public function saveOtherAccount(Request $request, User $user){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,' . $user->id,
            'password' => 'required|string|min:6|confirmed',
            'usertype' => 'required'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->usertype = $request->usertype;
        $user->save();

        return redirect('all-users')->with('edited', $request->email);
    }

    public function saveChangesToAccount(EditUser $request){
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('edit_account')->with('edited', true);
    }

    public function editAccount(){
        if(Auth::guest())
            return redirect('unauthorized_access');
        elseif (Auth::user()->status == "inactive"){
            return redirect()->route('invalid_user');
        }else
            return view('editAccount');
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
                    'password' => Hash::make($request->password), 'usertype' => $request->usertype,
                    'status' => 'active']);
        $user->save();
        return redirect('all-users')->with('newuser', $request->email);
    }
}
