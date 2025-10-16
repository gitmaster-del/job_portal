<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'asc')->paginate(10);
        return view('admin.users.list',[
            'users' => $users
        ]);
    }


    public function edit($id){

        $user = User::findOrFail($id);
        return view('admin.users.edit',[
            'user' => $user
        ]);
    }

   public function update(Request $request, $id){
    $user = User::findOrFail($id);
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'designation' => 'nullable|string|max:255',
        'mobile' => 'nullable|string|max:20',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }

    $user->name = trim($request->name);
    $user->email = trim($request->email);
    $user->designation = trim($request->designation);
    $user->mobile = trim($request->mobile);
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'User Information updated successfully.',
    ]);
}
public function destroy(Request $request){
    $id = $request->id;

    $user = User::find($id);
    if($user == null){
        return response()->json([
            'status' => false,
            'message' => 'User not found'
        ]);
    }
    
    $user->delete();
    
    return response()->json([
        'status' => true,
        'message' => 'User Deleted Successfully'
    ]);
}
}
