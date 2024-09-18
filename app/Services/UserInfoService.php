<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\UploadedFile;
use RealRashid\SweetAlert\Facades\Alert;

class UserInfoService
{

    public function create(array $data)
    {
        if (isset($data['profile_pic']) && $data['profile_pic'] instanceof UploadedFile) {
            $filePath = $data['profile_pic']->store('attachments', 'public');
            $data['profile_pic'] = $filePath;
        }

        $user = User::find(session('user_id'));
        if ($user->email != $data['email']) {
            $emailExists = User::where('email', $data['email'])->exists();

            if ($emailExists) {
                return back()->withErrors(['email' => 'This email is already taken. Please choose another one.']);
            }

            $user->email = $data['email'];
            $user->update();
        }

        Alert::success('Profile Edit Success', 'Your profile has been updated!');

        return UserInfo::create($data);
    }

    public function update(UserInfo $userInfo, array $data)
    {
        if (isset($data['profile_pic']) && $data['profile_pic'] instanceof UploadedFile) {
            $filePath = $data['profile_pic']->store('attachments', 'public');
            $data['profile_pic'] = $filePath;
        }

        $user = User::find(session('user_id'));
        if ($user->email != $data['email']) {
            $emailExists = User::where('email', $data['email'])->exists();

            if ($emailExists) {
                return back()->withErrors(['email' => 'This email is already taken. Please choose another one.']);
            }

            $user->email = $data['email'];
            $user->update();
        }

        Alert::success('Profile Edit Success', 'Your profile has been updated!');

        $userInfo->update($data);
        return $userInfo;
    }
}
