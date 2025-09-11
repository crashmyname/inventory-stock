<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        if($request->ajax()){
            $user = User::all();
            return DataTables::of($user)
                        ->make(true);
        }
        return view('users.user');
    }

    public function create(Request $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->dept = $request->dept;
        $user->section = $request->section;
        $user->factory = $request->factory;
        $user->role = $request->role;
        $user->password = $request->password;
        // if($request->hasFile('picture')){
        //     $namefile = $request->file('picture')->getClientOriginalName();
        //     Storage::disk('public')->put("profile",$namefile);
        //     $user->picture = $namefile;
        // }
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('profile', $filename, 'public');
            $destination = base_path('../mart/storage/profile');
            if(!file_exists($destination)){
                mkdir($destination,0775,true);
            }
            $file->move($destination,$filename);
            $user->picture = $filename;
        }        
        $user->save();
        return response()->json(['status'=>201,'message'=>'User Created Successfully']);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->dept = $request->dept;
        $user->section = $request->section;
        $user->factory = $request->factory;
        $user->role = $request->role;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        if($request->hasFile('picture')){
            if($user->picture != null){
                Storage::disk('public')->delete("profile/".$user->picture);
            }
            $file = $request->file('picture');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('profile', $filename, 'public');
            $destination = base_path('../mart/storage/profile');
            if(!file_exists($destination)){
                mkdir($destination,0775,true);
            }
            $file->move($destination,$filename);
            $user->picture = $filename;
        }
        $user->save();
        return response()->json(['status'=>201,'message'=>'User Updated Successfully']);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        if($user->picture != null){
            Storage::disk('public')->delete("profile/".$user->picture);
        }
        return response()->json(['status'=>200,'message'=>'User Deleted Successfully']);
    }

    public function profile($id)
    {
        $user = User::find($id);
        return view('users.profile',compact('user'));
    }

    public function updateProfile(Request $request,$id)
    {
        if($id != auth()->user()->user_id){
            return response()->json(['status'=>500,'message'=>'Update Failed']);
        }
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->dept = $request->dept;
        $user->section = $request->section;
        $user->factory = $request->factory;
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        if($request->hasFile('picture')){
            if($user->picture != null){
                Storage::disk('public')->delete("profile/".$user->picture);
            }
            $file = $request->file('picture');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('profile', $filename, 'public');
            $destination = base_path('../mart/storage/profile');
            if(!file_exists($destination)){
                mkdir($destination,0775,true);
            }
            $file->move($destination,$filename);
            $user->picture = $filename;
        }
        $user->save();
        return response()->json(['status'=>201,'message'=>'Profile Updated Successfully']);
    }
}
