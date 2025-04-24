<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase; // テスト実行前にデータベースをリセット

    /**
     * @return void
     */



//  トップページ表示
    public function test_topPage_items_get()
    {
        // テスト用ユーザーを作成し、login状態にする
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user); //（和訳）＄userとして行動する

        // ItemTableSeederを実行して商品データを挿入
        Artisan::call('db:seed', ['--class' => 'ItemsTableSeeder']);

        // トップページへアクセス
        $response = $this->get('/');

        // ステータスコードが２００（OK）であることを確認
        $response->assertStatus(200);

        // 各商品名がページに表示されているかを確認
        $items = \App\Models\Item::paginate(8); //商品データを8件取得
        foreach ($items as $item)
        {
            $response->assertSeeText($item->name);
        }
    }

    // 購入済みの商品は「sold」と表示
    public function test_purchased_item_sold_label()
    {
        // テスト用ユーザーと商品を作成
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        // テスト用商品を作成
        $item = Item::create([
            'name' => 'テスト',
            'price' => 1000,
            'description' => 'テスト用の商品',
            'condition' => '良好',
            'image' => 'test.jpg',
            'is_sold' => true,
            'user_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        // トップページにアクセスして確認
        $response = $this->get('/');

        // 「sold」ラベルが表示されているか確認
        $response->assertSee('sold');
    }


    // 出品した商品は商品一覧に表示されない
    public function test_sell_item_not_display()
    {
        // ログインユーザーの作成
        $user = User::factory()->create();
        $this->actingAs($user); // （和訳）$userとして行動する

        // ログインユーザーが出品したテスト用商品
        $item = Item::create([
            'name' => '自分の商品',
            'price' => 1000,
            'description' => 'テスト用の商品',
            'condition' => '良好',
            'image' => 'test.jpg',
            'user_id' => $user->id,
        ]);

        // 他のユーザーが出品した商品
        $otherUser = User::factory()->create();
        $otherItem = Item::create([
            'name' => '他人の商品',
            'price' => 1000,
            'description' => '他ユーザーが出品したテスト用の商品',
            'condition' => '良好',
            'image' => 'test.jpg',
            'user_id' => $otherUser->id,
        ]);

        // トップページにアクセス
        $response = $this->get('/');

        // 自分の商品は表示されないことを確認
        $response->assertDontSee('自分の商品');

        // 他人の商品は表示されていることを確認
        $response->assertSee('他人の商品');
    }




    // myListにいいねした商品が表示される
    public function test_myList_display_good_items()
    {
        // テスト用ユーザーを作成し「$user」として行動する
        $user = User::factory()->create();
        $this->actingAs($user);

        // test用商品データを作成
        $item = collect([
            Item::create([
                'name' => 'apple',
                'price' => 100,
                'description' => 'apple is yummy',
                'condition' => '良好',
                'image' => 'apple.jpg',
                'user_id' => $user->id,
            ]),
            Item::create([
                'name' => 'orange',
                'price' => 300,
                'description' => 'orange is yummy',
                'condition' => '良好',
                'image' => 'orange.jpg',
                'user_id' => $user->id,
            ]),
            Item::create([
                'name' => 'banana',
                'price' => 400,
                'description' => 'banana is yummy',
                'condition' => '良好',
                'image' => 'banana.jpg',
                'user_id' => $user->id,
            ]),
        ]);

        // テストデータを一つ「いいね」する
        $likedItem = $item->first();
        $user->likedItems()->attach($likedItem->id);

        // myListにアクセス
        $response = $this->get(route('myList'));

        // ステーダスコード２００を確認
        $response->assertStatus(200);

        // いいねした商品がマイリストに表示されているか確認
        $response->assertSee($likedItem->name);

        // いいねしていない商品は表示されていないか確認
        $notLikedItems = $item->slice(1); //最初以外のアイテム
        foreach($notLikedItems as $item)
        {
            $response->assertDontSee($item->name);
        }
    }

    // myListに購入済みの商品は「SOLD」と表示される
    public function test_myList_item_sold_label()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $this->actingAs($user);

        $item = Item::create([
            'name' => 'test',
            'price' => '1200',
            'description' => 'test data',
            'condition' => 'good',
            'image' => 'test.jpeg',
            'user_id' => $user->id,
            'seller_id' => $seller->id,
            'is_sold' => false, // 最初は未購入として登録
        ]);

        // sold 状態に更新
        $item->update(['is_sold' => true]);

        // sold のアイテムをいいね
        $user->likedItems()->attach($item->id);

        $response = $this->get(route('myList'));
        $response->assertStatus(200);
        $response->assertSee('sold');
    }

    // ログインユーザーはマイリストページへ遷移する
    public function test_login_user_myList_display()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('myList'));

        $response->assertStatus(200);
        $response->assertSee('マイリスト');
    }
    // 未ログインユーザーはnotingページにリダイレクトする
    public function test_not_login_user_myList_not_display()
    {
        $response = $this->get(route('myList'));

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }




    // 商品名で部分一致検索
    public function test_search_items_by_partial_name()
    {
        // userを作成しログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();

        // 商品を３つ作成
        Item::create([
            'name' => 'りんご',
            'price' => '100',
            'description' => 'りんごの説明',
            'condition' => '良好',
            'image' => 'apple.jpg',
            'user_id' => $otherUser->id,
        ]);
        Item::create([
            'name' => 'みかん',
            'price' => '200',
            'description' => 'みかんの説明',
            'condition' => '良好',
            'image' => 'orange.jpg',
            'user_id' => $otherUser->id,
        ]);
        Item::create([
            'name' => 'ばなな',
            'price' => '400',
            'description' => 'ばななの説明',
            'condition' => '良好',
            'image' => 'banana.jpg',
            'user_id' => $otherUser->id,
        ]);

        // 「り」で検索して一覧ページにアクセス
        $response = $this->get('/search?name=り');

        // 「り」が含まれる商品が表示されるか確認
        $response->assertSee('りんご');

        // 検索結果なし
        $response->assertDontSee('検索結果がありません');

        // 含まれていない商品が表示されていないことを確認
        $response->assertDontSee('みかん');
        $response->assertDontSee('ばなな');
    }

    // マイページに遷移しても検索状態が保持されているか確認
    public function test_search_query_is_retained_when_navigating_to_myList()
    {
        // userとitemを準備
        $user = User::factory()->create();
        $item1 = Item::create([
            'name' => 'orange_fruit',
            'price' => '100',
            'description' => 'orange_is_yummy',
            'condition' => 'good',
            'image' => 'orange.jpg',
            'user_id' => $user->id,
        ]);
        $item2 = Item::create([
            'name' => 'apple',
            'price' => '200',
            'description' => 'apple_is_yummy',
            'condition' => 'good',
            'image' => 'apple.jpg',
            'user_id' => $user->id,
        ]);

        // いいねした商品
        $user->likedItems()->attach($item1->id);
        $user->likedItems()->attach($item2->id);

        // loginして検索クエリ付きでマイリストページへアクセス
        $response = $this
            ->actingAs($user)
            ->get('/search?name=fruit');

        // 検索ワード「fruit」に一致する商品だけが表示されているかを確認
        $response->assertStatus(200);
        $response->assertSee('orange_fruit');
        $response->assertDontSee('apple');
    }
}



