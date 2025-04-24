Flea-Market
フリマアプリです。
会員登録無しで、商品一覧と商品詳細を見ることが出来ます。
会員登録すると、出品・購入・コメント・お気に入りなどが出来ます。
管理者は、ユーザーの削除・コメントの削除・ユーザーへのメール送信が出来ます。

アプリトップページ

作成目的
Laravel学習のまとめとして作成しました。

アプリケーションURL
http://ec2-13-231-223-163.ap-northeast-1.compute.amazonaws.com/
※テストデプロイのため保護されていない通信です。（現在停止中）

機能一覧
会員登録・ログイン、お気に入り追加・削除、コメント投稿・削除、検索、出品、購入、決済(stripe)

管理者権限での、ユーザー削除、コメント削除、メール送信

使用技術
Laravel Framework 8.x、PHP7.4.9、MySQL8.0.26、JavaScript、stripe

テーブル設計
テーブル設計修正

ER図
table_ER drawio

環境構築
Dockerビルド
1.クローン作成

git clone git@github.com:ishikawashinnya/Flea-Market.git
2.DockerDesktopアプリを立ち上げる

3.コンテナをビルドして起動

docker-compose up -d --build
Laravel環境構築
1.実行中の PHP コンテナの中に入る

docker-compose exec php bash
2.Composer を使用した依存関係のインストール

composer install
3.「.env.example」ファイルをコピーして「.env」ファイルを作成

cp .env.example .env
4..envに以下の環境変数を追加

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass  
5.アプリケーションキーの作成

php artisan key:generate
6.マイグレーションの実行

php artisan migrate
7.シーディングの実行

php artisan db::seed  
※メール、stripe等の設定は必要に応じて行ってください。
※ローカルでテストを行う場合はphpunit.xmlのDB_CONNECTIONをmysql_local_testと変更してください。

ダミーデータ説明
ユーザー一覧
1.管理者　email : admin@example.com　　password : testadmin
2.テストユーザー　email : testuser@example.com　　password : testuser

※テスト出品データ1と2はテストユーザーの出品として作成されています。

URL
開発環境：http://localhost/
phpMyAdmin:：http://localhost:8080/
