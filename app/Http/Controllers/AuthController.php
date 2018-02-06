<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class AuthController extends Controller
{

  public function signup(Request $request){

    //validacao
    $this->validate($request, [
      'name' => 'required',
      'email' => 'email|required|unique:users',
      'password' => 'required'
    ]);
    //pega os dados e cria o usuario
    $usuario = new User;
    $usuario -> name = $request->name;
    $usuario -> email = $request->email;
    $usuario -> password = bcrypt($request->password);
    // salva o usuario
    $usuario -> save();

    return response()->json(['msg' => 'Usuario criado com sucesso!']);

  }

  public function signin(Request $request){

    $this->validate($request, [
      'email' => 'email|required',
      'password' => 'required'
    ]);

    $credentials = $request->only('email', 'password');
    try {
      if(!$token = JWTAuth::attempt($credentials)){
        return response()->json(['error' => 'Campos invalidos']);
      }
    } catch(JWTException $e) {
      return response()->json(['Nao foi possivel gerar o token']);
    }
    return response()->json(['token' => $token]);

  }

}
