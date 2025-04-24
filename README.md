# Flea-Market
- フリマアプリです。
- 会員登録無しで、商品一覧と商品詳細を見ることが出来ます。
- 会員登録すると、商品の出品・購入・コメント・お気に入りなどが出来ます。

## アプリケーションURL
Http://localhost/

## 機能一覧
- 会員登録・ログイン
- メール認証（mailhog）
- お気に入り追加・削除
- コメント投稿
- 検索
- 出品
- 購入
- 決済(stripe)

## 使用技術
- Laravel Framework 8.x
- PHP7.4.9
- MySQL8.0.26
- JavaScript
- stripe
- mailhog

## ER図
![image](https://github.com/user-attachments/assets/02a964aa-f8da-49a6-9642-181747e89815)


## 環境構築
### Dockerビルド

1. クローン作成
> git clone git@github.com:wadaiko0428aryo/frea-market.git

2. DockerDesktopアプリを立ち上げる

3. コンテナをビルドして起動
> docker-compose up -d --build

### Laravel環境構築
1. 実行中の PHP コンテナの中に入る
> docker-compose exec php bash

2.Composer を使用した依存関係のインストール
> composer install

3.「.env.example」ファイルをコピーして「.env」ファイルを作成
> cp .env.example .env

4..envに以下の環境変数を追加
> - DB_CONNECTION=mysql
> - DB_HOST=mysql
> - DB_PORT=3306
> - DB_DATABASE=laravel_db
> - DB_USERNAME=laravel_user
> - DB_PASSWORD=laravel_pass

5.アプリケーションキーの作成
> php artisan key:generate

6.マイグレーションの実行
> php artisan migrate

7.シーディングの実行
> php artisan db::seed  

※メール、stripe等の設定は必要に応じて行ってください。



URL
開発環境：http://localhost/
phpMyAdmin:：http://localhost:8080/
