<?php

namespace PacificDev\BlogAi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Social extends Model
{
    use HasFactory;


    protected $fillable = ['token', 'user_id', 'name', 'share_id'];

    /**
     * ### Shared a given text and a link on linkedin
     *
     * The params below can be retrived using laravel Socialite package or similar
     *
     * $user = Socialite::driver('linkedin')->user()->token;
     * $user = Socialite::driver('linkedin')->user()->id;
     *
     * @param $userUrn the linkedin user id retrived after authenication
     * @param $token the linkedin auth token retrived after authenication
     * @param $shareText the text you want to share in the linkedin text share post
     * @param $shareLink the link you want to share in your post
     * @return void
     */
    public static function shareOnLinkedin($userUrn, $token, $shareText, $shareLink)
    {

        $post = [
            'author' => 'urn:li:person:' . $userUrn,
            'lifecycleState' => 'PUBLISHED',
            'specificContent' => [
                'com.linkedin.ugc.ShareContent' => [
                    'shareCommentary' => [
                        'text' => $shareText,
                    ],
                    'shareMediaCategory' => 'ARTICLE',
                    'media' => [
                        [
                            'status' => 'READY',
                            'originalUrl' => $shareLink,
                        ],
                    ],
                ],
            ],
            'visibility' => [
                'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
            ],
        ];

        $response = Http::withToken($token)->withHeaders(['X-Restli-Protocol-Version: 2.0.0'])->post('https://api.linkedin.com/v2/ugcPosts', $post);

        /* TODO: Refactor with early return */
        if ($response->successful()) {
            // The post was shared on linkedin
            //dd($response);
            Log::info('Linkedin Post shared successfully');
            // we could email ourself to inform that a new post was shared on linkedin
        } else {
            // There was an error
            //dd($response);
            Log::error($response->body());
            // we can inform the user that something happened and was unable to share that post
            // we could inform the site admin that something is not working
        }
    }
}
