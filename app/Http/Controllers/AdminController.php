<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function list()
    {
        $data['getRecord'] = User::getAdmin();
        $data['header_title'] = "Admin List";

        return view('admin.admin.list', $data);
    }


    public function add()
    {
        $data['header_title'] = "Add New Admin";
        return view('admin.admin.add', $data);
    }


    public function insert(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5'
        ]);


        $user = new User;

        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->user_type = 1;

        if ($request->hasFile('profile_pic')) {
            // Delete old photo if it exists
            if (!empty($user->profile_pic) && file_exists('upload/profile/' . $user->profile_pic)) {
                unlink('upload/profile/' . $user->profile_pic);
            }

            $file = $request->file('profile_pic');
            $extension = $file->getClientOriginalExtension();
            $slugName = Str::slug($request->name);
            $fileName = $slugName . '-' . time() . '.' . $extension;

            $file->move('upload/profile/', $fileName);
            $user->profile_pic = $fileName;
        }

        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin Successfully Created');
    }



    public function edit($id)
    {

        $data['getRecord'] = User::getSingle($id);

        if (!empty($data['getRecord'])) {

            $data['header_title'] = "Edit Admin";

            return view('admin.admin.edit', $data);

        } else {

            abort(404);
        }
    }



    public function update($id, Request $request)
    {

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id
        ]);


        $user = User::getSingle($id);

        $user->name = trim($request->name);
        $user->email = trim($request->email);


        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }


        if ($request->hasFile('profile_pic')) {

            if (!empty($user->profile_pic) && file_exists(storage_path('app/public/' . $user->profile_pic))) {

                unlink(storage_path('app/public/' . $user->profile_pic));
            }


            $file = $request->file('profile_pic');

            $extension = $file->getClientOriginalExtension();

            $slugName = Str::slug($request->name);

            $fileName = $slugName . '-' . time() . '.' . $extension;

            $user->profile_pic = $file->storeAs('profile', $fileName, 'public');
        }


        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin Successfully Updated');
    }



    public function delete($id)
    {

        $user = User::getSingle($id);

        $user->is_delete = 1;

        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin Successfully Deleted');
    }

}