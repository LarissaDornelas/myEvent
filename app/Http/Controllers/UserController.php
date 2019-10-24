<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getAll()
    {

        try {
            $users = User::all();

            if (sizeOf($users) > 0) {
                return view('user/users', ['userData' => $users]);
            } else {
                return view('user/users', ['userData' => []]);
            }
        } catch (\Exception $e) {
            return view('user/users', ['userData' => []]);
        }
    }


    public function getOne($id)
    {


        try {
            $user = User::where('id', $id)->first();
            if ($user != null) {
                return view('user/userDetails', ['userData' => $user]);
            } else {
                return redirect('/users')->with('error', 'Houve um erro ao abrir usuário');
            }
        } catch (\Exception $e) { }
    }

    public function create(Request $request)
    {

        $hashedRandomPassword = Hash::make('lari1234');
        $newUser = $request->all();
        if (array_key_exists('active', $newUser)) {
            $newUser['active'] = true;
        } else {
            $newUser['active'] = false;
        }
        if (array_key_exists('admin', $newUser)) {
            $newUser['admin'] = true;
        } else {
            $newUser['admin'] = false;
        }
        $newUser['password'] = $hashedRandomPassword;

        try {

            User::create($newUser);

            return redirect('/users')->with('status', 'Usuário cadastrado com sucesso');
        } catch (\Exception $e) {
            return redirect('/users')->with('error', 'Houve um erro ao cadastrar usuário');
        }
    }

    public function update($id, Request $request)
    {
        $newUser = $request->all();
        if (array_key_exists('active', $newUser)) {
            $newUser['active'] = true;
        } else {
            $newUser['active'] = false;
        }
        if (array_key_exists('admin', $newUser)) {
            $newUser['admin'] = true;
        } else {
            $newUser['admin'] = false;
        }
        try {
            User::where('id', $id)->first()
                ->update($newUser);
            return redirect('/users/' . $id)->with('status', 'Dados atualizados com sucesso');
        } catch (\Exception $e) {
            return redirect('/users/' . $id)->with('error', 'Houve um erro ao atualizar os dados. Tente novamente mais tarde.');
        }
    }

    public function delete($id)
    {
        try {
            $user = User::where('id', $id)->first()->delete();
        } catch (\Exception $e) { }
    }
}
