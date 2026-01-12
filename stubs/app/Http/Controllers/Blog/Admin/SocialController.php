<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use PacificDev\BlogAi\Models\Social;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{


    public function handleLinkedinAuthentication()
    {
        // New OpenID implementation
        //dd(Socialite::driver('linkedin-openid')->scopes(['openid', 'profile', 'email', 'w_member_social']));


        //dd(Socialite::driver('linkedin-openid')->scopes(['w_member_social']));

        // Use OpenID Connect scopes for linkedin-openid driver
        return Socialite::driver('linkedin-openid')->scopes(['openid', 'profile', 'email', 'w_member_social'])->redirect();
    }

    public function handleLinkedinCallback()
    {
        // $user contains the linkedin user dedails
        // id, nickname, name, email, avatar, token and more..
        $user = Socialite::driver('linkedin-openid')->user();
        $token = $user->token;
        $userUrn = $user->id;
        //dd($user, $token, $userUrn);

        Social::updateOrCreate([
            'token' => $token
        ], [
            'user_id' => auth()->user()->id,
            'name' => 'linkedin',
            'share_id' => $userUrn,
            'token' => $token
        ]);

        return to_route('admin.posts.index')->with('message', 'Connection with linkedin established - you can now put your blog on autopilot');
    }
}
