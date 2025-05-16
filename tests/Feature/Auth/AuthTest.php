<?php

use App\Models\User;
use Illuminate\Validation\ValidationException;

uses()->group('Feature','Auth');

it("Login Request Validation Test",function(){
    $this->postJson(route('api.login'),[]);
})->throws(ValidationException::class);

it("Attempt With Wrong Credentials",function(){
    $response = $this->postJson(route('api.login'),[
        "email" => "wrong@gmail.com",
        "password" => "wrongpassword"
    ]);

    $response->assertUnauthorized();
    $response->assertJsonStructure([
        "message"
    ]);
});

it("Attempt With Correct Credentials",function(){
    $user = User::first();
    $response = $this->postJson(route('api.login'),[
        "email" => $user->email,
        "password" => "password"
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        "token"
    ]);
});