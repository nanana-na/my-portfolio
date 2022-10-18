## README

#### デプロイ
<a target="_blank" href="https://sadaikeiziban.xyz/">https://sadaikeiziban.xyz/</a>
テストユーザー: 学籍番号　`20000000` パスワード`password`

### 開発環境
- Windows(OS)
- PHP 8.0.19
- laravel 6
- Node.js 16.13.1
- MarinaDB(SQL)

### 環境準備
`git clone`した後

`composer install`

で補足部分を補う。(PHPのversionが違うとエラーが起きる)

**.env.sample**をコピーし新たに**.env**ファイルを作成
`php artisan key:generate`

mysqlのportやパスワードを各自の設定で変更する。


`php artisan storage:link`

でシンボリックを通し画像を表示できるようにする。

**storage/app/public/images/** 配下に **circle/,friend/,job/,user/,yet/,yetuser/** フォルダが存在するか確認する(なければ作成)。

`php artisan migrate`

でDBを製作。

`php artisan db:seed`

で初期データを挿入。

マスターユーザー:
<br> 学籍番号　`20000000` パスワード`password`

### 工夫した点
1. **画像はサーバに保存**し、**DBに画像のリンクを保存**。画像を編集(update)した際は、**元の画像をサーバから削除** する。
1. **バリデーション**を設定し入力項目を確認する。
1. 管理者の学籍番号の時に管理者画面を表示する(ユーザ一覧、作成、バイト作成、サークル作成、等が可能)。
1. ヘッダーなどのアイコンにはサイズの小さいSVGを使用。
1. アンケートではユーザが投票してるか確認し一回のみ投票可能。最初の項目に王冠を付ける。
1. 新規会員登録された場合は、API連携させ**gmailで管理者に通知**。
1. **パスワードはハッシュ化**。
1. プロフィール機能を追加。
1. デプロイするサーバで**SSH**化した。sadaikeiziban.xyzだけでも表示できるようにした(chromでは確認)。
1. httpで接続しても**自動でhttps**に変換する。
