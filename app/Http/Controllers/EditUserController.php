<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class EditUserController
{
    public function showSetting(){
        return view('setting');
    }

    public function edit(Request $request)
    {
        $output = ["fail", "fail", "fail", "password"];
        if ($request->fName !== null){
            $output[0] = "pass";
            $this->changeFName($request);
        }
        if ($request->lName !== null){
            $output[1] = "pass";
            $this->changeLName($request);
        }
        if ($request->birthDate !== null){
            $output[2] = "pass";
            $this->changeBD($request);
        }
        if ($request->old_password !== null && $request->new_password !== null && $request->confirm_password !== null){
            $output[3] = $this->changePass($request);
        }
        if ($output === ["fail", "fail", "fail", "password"])
            return Redirect::back()->withErrors(['error' => 'Nothing to change']);
        else if ($output === ["fail", "fail", "fail", "fail"])
            return Redirect::back()->withErrors(['error' => 'Wrong Pass']);
    }
    public function changeFName(Request $request)
    {
        $request->validate([
            'fName'=>'required',
        ]);
        $current_user = auth()->user();
        $current_user->update([
            'fName' => $request->fName
        ]);
    }
    public function changeLName(Request $request)
    {
        $request->validate([
            'lName'=>'required',
        ]);
        $current_user = auth()->user();
        $current_user->update([
            'lName' => $request->lName
        ]);
    }
    public function changeBD(Request $request)
    {
        $request->validate([
            'birthDate'=>'required',
        ]);
        $current_user = auth()->user();
        $current_user->update([
            'birthDate' => $request->birthDate
        ]);
    }
    public function changePass(Request $request): string
    {
        $request->validate([
            'old_password'=>'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password'
        ]);
        $current_user = auth()->user();
        if (Hash::check($request->old_password, $current_user->password)){
            $current_user->update([
                'password' => bcrypt($request->new_password)
            ]);
            return "pass";
        } else{
            return "fail";
        }
    }
}
