<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class DetailTest extends TestCase
{
    // テスト実行前にデータベースをリセット
    use RefreshDatabase;

    /**
     * @return void
     */

    //  商品詳細ページの表示
    public function test_display_the_detail_page()
    {
        // test用ユーザーとtest用商品の作成
        $user = User::factory()->create();
        $item = Item::create([
            'name' => 'test',
            'price' => 120,
            'description' => 'It is test data',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        // ログインして詳細ページにアクセス
        $responseAsUser = $this->actingAs($user)->get(route('detail', ['item_id' => $item->id]));
        $responseAsUser->assertStatus(200);
        $responseAsUser->assertSee($item->name);

        // ログアウト状態で詳細ページにアクセス
        $responseAsGuest = $this->get(route('detail', ['item_id' => $item->id]));
        $responseAsGuest->assertStatus(200);
        $responseAsGuest->assertSee($item->name);
    }

    // 商品詳細ページにカテゴリーが複数表示されているかテスト
    public function test_multiple_categories_are_display_on_detail_page()
    {
         //user作成
        $user = User::factory()->create();

        // 商品作成
        $item = Item::create([
            'name' => 'test',
            'price' => 200,
            'description' => 'It is test data',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        // カテゴリー作成
        $categories = collect([
            Category::create(['category' => 'red']),
            Category::create(['category' => 'black']),
            Category::create(['category' => 'blue']),
        ]);
        // 商品カテゴリーに紐づける
        $item->categories()->attach($categories->pluck('id'));

        // detailページへアクセス
        $response = $this->get(route('detail', ['item_id' => $item->id]));

        // 各カテゴリー名が表示されているかテスト
        foreach ($categories as $category)
        {
            $response->assertSee($category->category);
        }

        $response->assertStatus(200);

    }

    // いいね登録機能test
    public function test_tap_like_register()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 商品を作成
        $item = Item::create([
            'name' =>'test',
            'price' =>'2000',
            'description' =>'It is test data',
            'condition' =>'good',
            'image' =>'test.jpg',
            'user_id' => $user->id,
        ]);

        // いいねを押す
        $response = $this->post(route('toggleLike', ['item_id' => $item->id]));

        // データベースにレコードが存在することを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // JSONレスポンスを確認
        $response->assertJson([
            'liked' => true,
        ]);
    }

    // いいね欄の変色機能test
    public function test_user_can_like_color_change()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::create([
            'name' =>'test',
            'price' =>'2000',
            'description' =>'It is test data',
            'condition' =>'good',
            'image' =>'test.jpg',
            'user_id' => $user->id,
        ]);

        // リクエストを送信
        $response = $this->postJson(route('toggleLike', ['item_id' => $item->id]));

        // レスポンスのステータスとJSON構造を検証
        $response->assertStatus(200)->assertJsonStructure(['liked', 'likeCount']);

        // likeテーブルに保存されたことを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // いいね機能解除
    public function test_user_can_unlike_item()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::create([
            'name' => 'test',
            'price' => '2000',
            'description' => 'It is test data',
            'condition' => 'good',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        // 1回目のタップ：いいね登録
        $this->postJson(route('toggleLike', ['item_id' => $item->id]));

        // データベースに「いいね」が存在することを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 2回目のタップ：いいね解除
        $response = $this->postJson(route('toggleLike', ['item_id' => $item->id]));

        // JSONの中身が「liked: false」で返ってくることを確認
        $response->assertStatus(200)->assertJson([
            'liked' => false,
        ]);

        // データベースに「いいね」が削除されたことを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

   // ログイン済みのユーザーはコメントを送信できる
    public function login_user_is_can_sent_comment()
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

        // コメント投稿リクエスト送信
        $response = $this->post(route('item.comment.store'), [
            'item_id' => $item->id,
            'comment' => 'テキストコメント',
        ]);

        // コメントがデータベースに存在するか確認
        $this->assertDatabaseHas('comments', [
            'user_id' =>$user->id,
            'item_id' =>$item->id,
            'comment' =>'テキストコメント',
        ]);
    }

    // 未ログインユーザーがコメント送信するとnothingに遷移
    public function test_guest_user_is_redirected_to_nothing_when_comment_sent()
    {
        // 商品を作成
        $item = Item::create([
            'name' => 'テスト商品',
            'price' => 1000,
            'description' => 'テスト用の商品です',
            'condition' => '新品',
            'image' => 'test.jpg',
        ]);

        // 未ログイン状態でコメント送信（POST）
        $response = $this->post(route('item.comment.store', ['item_id' => $item->id]), [
            'item_id' => $item->id,
            'comment' => 'ゲストのコメントです',
        ]);

        // /nothing にリダイレクトされることを確認
        $response->assertRedirect(route('nothing'));

        // データベースにコメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'comment' => 'ゲストのコメントです',
            'item_id' => $item->id,
        ]);
    }

    // コメント送信時のエラーメッセージ
    public function test_login_user_cannot_post_empty_comment()
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

        // 空コメントを送信
        $response = $this->post(route('item.comment.store', ['item_id' => $item->id]), [
            'comment' => '',
        ]);

        // sessionにエラーメッセージがあるか確認
        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);

        // コメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }

    // コメント送信時のエラーメッセージ（２５５時以内）
    public function test_login_user_cannot_post_text_over_comment()
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

        // 256字のコメントを作成
        $longComment = str_repeat('a', 256);
        $response = $this->post(route('item.comment.store', ['item_id' => $item->id]), [
            'comment' => $longComment,
        ]);

        // sessionにエラーメッセージがあるか確認
        $response->assertSessionHasErrors(['comment' => 'コメントは255字以内で入力してください']);

        // コメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }
}
