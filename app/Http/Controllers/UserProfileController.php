<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function showProfile()
    {
        return view('user.profile.index');
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ];

        // Xử lý upload ảnh đại diện
        if ($request->hasFile('profile_picture')) {
            // Xóa ảnh cũ nếu có
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $data['profile_picture'] = $imagePath;
        }

        $user->update($data);

        return redirect()->route('user.profile')->with('success', 'Cập nhật thông tin thành công!');
    }

    public function showResetPassword()
    {
        return view('user.profile.reset-password');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không chính xác.'
            ]);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('user.profile')->with('success', 'Đổi mật khẩu thành công!');
    }
}
