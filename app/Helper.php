<?php
namespace FB;
use FB\Models\User;
class Helper {

    public static function findOrCreateUser($data) {
        $user =  User::where('fb_user_id','=',$data['fb_user_id'])->first();
        if(is_null($user)) {
            $user = new User;
            if($user->save()) {
                $user->fb_user_id = $data['fb_user_id'];
                $user->name = $data['name'];
                $user->token = $data['token'];
                $user->expires_at = $data['expires_at'];
                $user->is_active = $data['is_active'];
                return $user;
            }
            return false;
        } else {
            $user->fb_user_id = $data['fb_user_id'];
            $user->name = $data['name'];
            $user->token = $data['token'];
            $user->expires_at = $data['expires_at'];
            $user->is_active = $data['is_active'];
            $user->save();
            return $user;
        }
    }
}