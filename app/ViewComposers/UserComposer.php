<?php

namespace App\Http\ViewComposers;

use Illuminate\Auth\AuthManager;
use Illuminate\View\View;

class UserComposer
{
    private $auth;
    public function __construct(AuthManager $authManager)
    {
        $this->auth = $authManager->guard('web');
    }

    public function compose(View $view)
    {
        $user = $this->auth->user();
        $view->with([
            'user' => $user,
        ]);
    }
}
