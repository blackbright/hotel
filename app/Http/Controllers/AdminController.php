<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('admin.index');
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    }

    public function AdminLogin()
    {
        return view('admin.admin_login');
    }

    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_profile_view', compact('profileData'));
    }

    public function AdminProfileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();

        $notification = [
            'message' => 'Admin Profile Updated แล้ว',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function AdminChangePass()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);

        return view('admin.admin_change_pass', compact('profileData'));
    }

    public function AdminPassUpdate(Request $request)
    {

        $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required',
            'confirm_pass' => 'required',
        ]);

        if ($request->new_pass != $request->confirm_pass) {
            $notification = [
                'message' => 'รหัสผ่านใหม่ไม่ตรงกัน',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification);
        }

        if (! Hash::check($request->old_pass, auth::user()->password)) {
            $notification = [
                'message' => 'รหัสผ่านเดิมไม่ถูกต้อง',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification);
        }

        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_pass),
        ]);
        $notification = [
            'message' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว',
            'alert-type' => 'success',
        ];

        return back()->with($notification);
    }
}
