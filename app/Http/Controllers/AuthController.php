<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\User;


class AuthController extends BaseController{
    public function signup(Request $request){
        $validator = Validator::make( $request->all(), [
            "name"=>"required",
            "email"=>"required",
            "password"=>"required",
            "confirmed_password"=>"required|same:password",
        ]);
        if ($validator->fails()){
            return $this->sendError("Validálási hiba",$validator->errors());
        }
         $input = $request->all();
         $input["password"] = bcrypt($input["password"]); //titkosítás
         $user =User::create($input);
         $success["name"]=$user->name;
         return $this->sendResponse($success, "Sikeres regisztráció!");
    }
    public function signin(Request $request){

        if(Auth::attempt(["email"=>$request->email,"password"=>$request->password ]) ){
            $authUser = Auth::user(); //requestből kiolvassa az adatokat
            $success["token"]=$authUser->createToken("myapitoken")->plainTextToken;//létrehozunk egy elépési tokent
            $success["name"]= $authUser->name;
            return $this->sendResponse($success,"Sikeres bejelentkezés!");  
        }else{
            return $this->sendError("Unathorized", ["error"=>"Hibás adat"]);

        }
    }
    public function logout(Request $request){
        auth( "sanctum" ) -> user()->currentAccessToken()->delete();
        return response()->json("Sikeres kijelentkezés");
    }
}
