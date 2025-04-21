<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
use RefreshDatabase; // テスト実行前にデータベースをリセット
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 商品購入機能
    public function test_item_is_purchase()
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

        // テスト用プロフィールの作成
        $profile = Profile::create([
            'user_id' => $user->id,
            'post' => '123-4567',
            'address' => 'Tokyo, Japan',
            'building' => 'Building Name',
            'image' => 'default_image.jpg',
        ]);

        // 商品を購入する
        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'profile_id' => $profile->id,
            'purchase_method' => 'カード払い',
        ]);

        // 購入した後、商品を売却済みに更新
        $item->update(['is_sold' => true]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'is_sold' => true,
        ]);
    }

    // 購入済みの商品はトップページで「sold」と表示
    public function test_item_is_sold_display_on_topPage()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        // 商品作成
        $item = Item::create([
            'name' => 'test item',
            'price' => 100,
            'description' => 'test description',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $seller->id,
        ]);

        // プロフィール作成
        $profile = Profile::create([
            'user_id' => $buyer->id,
            'post' => '123-4567',
            'address' => 'Tokyo, Japan',
            'building' => 'Building Name',
            'image' => 'default_image.jpg',
        ]);

        // 商品を購入
        $purchase = Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'profile_id' => $profile->id,
            'purchase_method' => 'カード払い',
        ]);

        // 商品を売却済みに更新
        $item->update(['is_sold' => true]);

        // 購入者としてログイン
        $this->actingAs($buyer);

        // 商品一覧ページにアクセス
        $response = $this->get(route('topPage'));

        // 商品が「SOLD」と表示されていることを確認
        $response->assertSeeText('SOLD');
    }

    // 購入した商品が購入商品一覧ページに表示される
    public function test_purchased_items_display_on_purchaseList()
    {
        // ユーザー作成（購入者と出品者）
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        // 商品作成
        $item = Item::create([
            'name' => 'test item',
            'price' => 100,
            'description' => 'test description',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $seller->id,
        ]);

        // プロフィール作成
        $profile = Profile::create([
            'user_id' => $buyer->id,
            'post' => '123-4567',
            'address' => 'Tokyo, Japan',
            'building' => 'Building Name',
            'image' => 'default_image.jpg',
        ]);

        // 購入履歴を作成
        $purchase = Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'profile_id' => $profile->id,
            'purchase_method' => 'カード払い',
        ]);


        // 商品を売却済みに更新
        $item->update(['is_sold' => true]);

        // 購入者としてログイン
        $this->actingAs($buyer);

        // 購入商品一覧ページにアクセス
        $response = $this->get(route('purchaseList'));

        // 購入した商品が表示されていることを確認
        $response->assertSeeText($item->name);
    }


    // 住所変更後に購入画面で変更内容が反映されている
    public function test_address_change_are_reflected()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $profile = Profile::create([
            'image' => 'test.jpg',
            'post' => '111111',
            'address' => 'test 1-12-123',
            'building' => 'testBuilding',
            'user_id' => $user->id,
        ]);

        $item = Item::create([
            'name' => 'test',
            'price' => '100',
            'description' => 'test data',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        // 住所変更ページに遷移
        $this->get(route('address', ['item_id' => $item->id]))->assertStatus(200);

        // 新しい住所に変更
        $newAddress = '大阪市テスト1-12-3';

        $response = $this->post(route('updateAddress', ['item_id' => $item->id]), [
            'post' => '111111',
            'address' => $newAddress,
            'building' => 'testBuilding',
            'user_id' => $user->id,
        ]);

        // purchaseにリダイレクト
        $response->assertRedirect(route('purchase', ['item_id' => $item->id]));

        // エラーがないことを確認
        $response->assertSessionHasNoErrors();

        // 購入画面に遷移して住所が更新されていることを確認
        $this->get(route('purchase', ['item_id' => $item->id]))
            ->assertStatus(200)
            ->assertSee($newAddress);
    }

    // 購入後購入データに住所データが紐づいているか確認
    public function test_item_purchased_link_address_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::create([
            'name' => 'test',
            'price' => '100',
            'description' => 'test data',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        $profile = Profile::create([
            'image' => 'test.jpg',
            'post' => '111111',
            'address' => 'テスト１−２−３',
            'building' => 'testBuilding',
            'user_id' => $user->id,
        ]);

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'profile_id' => $profile->id,
            'item_id' => $item->id,
            'purchase_method' => 'カード払い',
        ]);

        // 購入データに正しいプロフィールIDが登録されているか確認
        $this->assertEquals($profile->id, $purchase->profile_id);
        //addressが保存されているかチェック
        $this->assertEquals('テスト１−２−３', $purchase->profile->address);
        // buildingが保存されているかチェック
        $this->assertEquals('testBuilding', $purchase->profile->building);
        // postが保存されているかチェック
        $this->assertEquals('111111', $purchase->profile->post);

    }
}
