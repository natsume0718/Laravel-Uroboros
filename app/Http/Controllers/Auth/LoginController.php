<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\{Auth, DB, Log};
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 認証ページヘユーザーをリダイレクト
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('twitter')->redirect();
    }

    /**
     * ユーザーのアクセストークンを取得、ログインをする
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        // twitterからアカウント情報取得
        try {
            $account_info = Socialite::driver('twitter')->user();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route('top')->with('error', 'Twitterアカウント取得に失敗しました');
        }
        $auth_user = DB::transaction(function () use ($account_info) {
            $user = User::updateOrCreate(['id' => $account_info->id], [
                'name' => $account_info->name,
                'nickname' => $account_info->nickname,
                'avatar' => $account_info->avatar,
                'token' => $account_info->token,
                'token_secret' => $account_info->tokenSecret
            ]);
            Auth::login($user, true);
            return $user;
        });

        return redirect()->route('activity.index', $auth_user->nickname)->with('success', 'ログインしました');
    }

    /**
     * ログアウト
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        Log::info($user->nickname . ' :logout');

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->route('top')->with('success', 'ログアウトしました');
    }
}
