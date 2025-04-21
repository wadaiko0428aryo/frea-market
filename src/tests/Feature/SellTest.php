<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class SellTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_display_on_sell_page()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('sell'));
        $response->assertStatus(200);

        $response->assertSee('name');
        $response->assertSee('image');
        $response->assertSee('brand');
        $response->assertSee('price');
        $response->assertSee('description');
        $response->assertSee('condition');



        $response = $this->post(route('storeSell'), [
            'name' => 'test',
            'image' =>'test.jpg',
            'brand' => 'made in test',
            'price' => 1000,
            'description' => 'test data',
            'condition' => 'good',
        ]);

        $response->assertRedirect(route('sell'));

    }
}
