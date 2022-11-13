<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
	public function create(Request $request){
		$fields = $request->validate( [
			'name'=>'required|string',
			'email'=>'required|string',
			'password'=>'required|string',
			'token'=>'required|string'
		]);
		if($fields['token'] && $fields['token']==env('AUTH_TOKEN','')){
			if(!empty($fields['token'])){
				$user = User::create([
					'name'=>$fields['name'],
					'email'=>$fields['email'],
					'password'=>bcrypt($fields['password']),
				]);

				$token = $user->createToken('myAppToken')->plainTextToken;
				return [
					'user'=>	$user,
					'token'=> $token
				];
			}
		}
		return [
			'error token',
		];
	}
	public function login(Request $request){

		$fields = $request->validate( [
			'name'=>'required|string',
			'email'=>'required|string',
			'password'=>'required|string'
		]);
		$user = User::where('email', $fields['email'])->first();
		if($user){
			if(Hash::check($fields['password'], $user->password)) {
				$token = $user->createToken('myAppToken')->plainTextToken;
				return [
					'user'=>	$user,
					'token'=> $token
				];
			}
		}
		return [
			'error account: '.bcrypt($fields['password'])
		];
	}
	public function logout(Request $request){
		auth()->user()->tokens()->delete();
		return [
			'status'=>1,
			'message'=>'Logout'
		];
	}
}
