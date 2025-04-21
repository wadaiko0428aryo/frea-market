<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase; // テスト実行前にデータベースをリセット

    // 会員登録機能テスト

    /**
     * 名前が未入力の場合、バリデーションエラーが発生することをテスト
     *
     * @test
     */
    public function test_register_required_name()
    {
        $response = $this->post('/register', [
            'name' => '', //名前を空にする
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // セッションにエラーメッセージが含まれているか確認
        $response->assertSessionHasErrors(['name']);

        // リダイレクトされている（通常は元のフォームに）
        $response->assertStatus(302);
    }

    // メールアドレスが未入力の場合、バリデーションエラーが発生することをテスト
    // @test
    // //
    public function test_register_required_email()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    // @test
    public function test_register_required_password()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // @test
    public function test_register_required_password_short_7()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'short7', //７文字以下
            'password_confirmation' => 'short7',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // @test
    public function test_register_required_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password_different', //不一致
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // @test
    public function test_register_success()
    {
        $response = $this->post('/register', [
            'name' => 'テスト',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // ユーザーがデータベースに存在しているか確認
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // 認証しているか確認
        $this->assertAuthenticated();

        // 登録後のリダイレクト先を確認
        $response->assertRedirect('/mailCheck');
    }


    // ログイン機能テスト
    //
    //メアド空欄でエラーメッセージ表示 //
    public function test_login_required_email()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_required_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

        // emailが間違っている
    public function test_login_different_email()
    {
        // 新しくテスト用ユーザーを作成
        \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'), //passwordをハッシュ化
        ]);

        // 間違ったパスワードでloginする
        $response = $this->post('/login', [
            'email' => 'test@different.com',
            'password' => 'password',
        ]);

        // error表示
        $response->assertSessionHasErrors();

        // 認証されていないことを確認
        $this->assertGuest();
    }

    // passwordが間違っている
    public function test_login_different_password()
    {
        // 新しくテスト用ユーザーを作成
        \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'), //passwordをハッシュ化
        ]);

        // 間違ったパスワードでloginする
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password_different',
        ]);

        // error表示
        $response->assertSessionHasErrors();

        // 認証されていないことを確認
        $this->assertGuest();
    }

    public function test_login_success()
    {
        // テスト用ユーザー作成
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 正しくloginを試行
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // 認証を確認
        $this->assertAuthenticatedAs($user);

        // login後のリダイレクト先の確認
        $response->assertRedirect('/');
    }


    // logout機能テスト -- logout成功
    public function test_logout_success(){

        // テスト用ユーザーを作成し、login状態にする
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user); // （和訳）$userとして行動する

        // logout処理を実行
        $response = $this->post('/logout');

        // logout後、認証されていないか確認
        $this->assertGuest();

        // logout後のリダイレクト先の確認
        $response->assertRedirect('/');
    }

}