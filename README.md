[![Build Status](https://travis-ci.org/pieni-php/pieni-dev.svg?branch=master)](https://travis-ci.org/pieni-php/pieni-dev)

# システム要件
- Ubuntu 18.04LTS
- Appache 2.4 (mod_rewriteが必要)
- MySQL 5.7
- PHP 7.2

# リクエストの流れ

## Apache HTTPサーバ分散設定ファイル(./.htaccess)
- 全てのリクエストをフロントコントローラへリダイレクトします。

## フロントコントローラ(./index.php)
- ディスパッチャに処理を渡します。

## ディスパッチャ(super_dispatcher.php)
- 設定ファイルをロードします。
- リクエストを解釈します。
- ページリクエストではビューに、APIリクエストではモデルに処理を渡します。

## ビュー(views/super_view.php)
- 例外ハンドラを初期化します。
- リクエストを検証します。
- 静的コンテンツをHTML5で返します。
- コントローラに処理を渡します。

## コントローラ(controllers/super_controller.js)
- ターゲットコントローラに処理を渡します。

## モデル(models/super_model.php)
- 例外ハンドラを初期化します。
- リクエストを検証します。
- ターゲットモデルに処理を渡します。
- 動的コンテンツをJSONで返します。

# 例外の処理
## 例外ハンドラ(super_exception_handler.php)
ページリクエストではビューに、APIリクエストではモデルに例外情報を提供します。

# 0.1.1から引き継げていない機能
- examples/auth
- examples/crud
