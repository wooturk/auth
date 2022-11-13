<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tulparstudyo\Response;

class AuthController extends Controller
{
	public function create(Request $request){
		$exception = '';
		try {
			return  Response::success('Kullanıcı oluşturmak için user servisini kullanınız');

			/*
			$fields = $request->validate( [
				'name'=>'required|string',
				'email'=>'required|string',
				'password'=>'required|string',
				'token'=>'required|string'
			]);
			if($fields['token'] && $fields['token']==env('AUTH_TOKEN','')){
				if(!empty($fields['token'])){
					$user = create_user($fields);
					if($user){
						return Response::success('Kullanıcı Oluşturuldu', $user);
					}
				}
			}
			*/
		} catch(\Illuminate\Database\QueryException $ex){
			$exception = $ex->getMessage();
		} catch (\Exception $ex){
			$exception = $ex->getMessage();
		}
		return Response::failure($exception);
	}
	public function login(Request $request){
		$exception = '';
		try {
			$fields = $request->validate( [
				'name'=>'required|string',
				'email'=>'required|string',
				'password'=>'required|string'
			]);
			$user = find_user($fields);
			if($user){
				$token = $user->createToken('myAppToken')->plainTextToken;
				return Response::success("Token Oluşturuldu", ['token'=>$token]);
			}
			return  Response::failure('Kulllanıcı bulunamadı');
		} catch(\Illuminate\Database\QueryException $ex){
			$exception = $ex->getMessage();
		} catch (\Exception $ex){
			$exception = $ex->getMessage();
		}
		return Response::exception( $exception);
	}
	public function logout(Request $request){
		$exception = '';
		try {
			auth()->user()->tokens()->delete();
			return  Response::success('Oturum Sonlandırıldı');
		} catch (\Exception $ex){
			print_r($ex);
			$exception = $ex->getMessage();
		}
		return Response::exception($exception);
	}
}
