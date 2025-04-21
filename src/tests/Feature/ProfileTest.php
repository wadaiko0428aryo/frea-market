<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class ProfileTest extends TestCase
{
    use RefreshDatabase; // テスト実行前にデータベースをリセット
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //  profile画面を表示するとプロフィール画像、名前、出品した商品一覧が表示される
    public function test_profile_display_in_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::create([
            'name' => 'test',
            'price' => 100,
            'description' => 'test data',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $profile = Profile::create([
            'image' => 'test.jpg',
            'post' => '111111',
            'address' => 'test1-1-1',
            'building' => 'testBuilding',
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('profile'));
        $response->assertStatus(200);

        // ユーザーの画像とユーザーネームを表示
        $response->assertSee([
            'test.jpg',
            $user->name,
        ]);

        // 出品した商品を表示
        $response->assertSee([
            'test',
            'test.jpg',
        ]);
    }

    // profileデータは初期設定時に登録し、プロフィール画面で表示
    public function test_myPage_display_user_profile_info()
    {
        $user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => bcrypt('test1234'),
        ]);

        $profile = Profile::create([
            'address' => 'test1-2-3',
            'post' => '111111',
            'building' => 'testBuilding',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('profile'));
        $response->assertStatus(200);

        $response->assertSee([
            'test.jpg',
            $user->name,
            '111111',
            'test1-2-3',
            'testBuilding',
        ]);
    }
}
