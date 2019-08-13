<?php


namespace App\Http\Controllers\Auth;


use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class ApiRegisterController extends RegisterController
{
    /**
     * Handle a registration request for the application.
     *
     * @override
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $errors = $this->validator($request->all())->errors();

        if(count($errors))
        {
            return response(['errors' => $errors], 401);
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return response(['user' => $user]);
    }
}