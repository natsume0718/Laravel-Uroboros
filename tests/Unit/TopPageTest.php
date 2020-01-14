<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TopPageTest extends TestCase
{
    /**
     * @test
     */
    public function トップ画面を表示できる()
    {
        $response = $this->get(route('top'));
        $response->assertViewIs('top');
        $response->assertStatus(200);
    }
}
