<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Mockery;

class TwitterAuthTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->providerName = 'twitter';
    }
    /**
     * @test
     * @return void
     */
    public function twitterログイン()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(302);
        $this->assertContains('api.twitter.com/oauth/authenticate', $response->getTargetUrl());
        $abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
    }
}
