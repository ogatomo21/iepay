# いえPay

いえPayは家や小さなグループで使えるOSSの決済システムです。

## 推奨環境
- 動作環境 HTML5&PHP
- 推奨利用端末 カメラ搭載のiOS11以降またはAndroid6以降のデフォルトブラウザ
- 推奨環境 XAMPP(最新版)

## インストール方法
Xamppの場合Windowsだとデフォルトで「C:\XAMPP」にインストールされるので「htdocs」や「www」のフォルダに入れてください。
サーバー起動後「 http://localhost/setup 」にアクセスし画面の指示に従い操作してください。
ローカルサーバーでカメラ機能を利用する場合はlocalhostかhttpsが必須なのでカメラ付きのノートPCを使うかngrokなどでSSL化しスマートフォンからアクセスしてください。

## 仕組み
基本的には残高や履歴のデータはすべてテキストファイルで保管しています。パスワード以外は平文なのでセキュリティ的にはまだまだ改善の余地はあると思います。

## なぜ作ろうと思ったか
夏休みの時とかに風呂洗い1回50円でお手伝いをしていたのですが、小銭がないことが多かったので「ペ○ペイとかでもいいけど作ったら面白そう！」と思い今年ようやく制作に移せ1ヶ月くらい(ちまちまやってたから実質1週間位かも)で作り上げました。
このシステムは特に変化もしないバーコードで利用者を識別しているためスマホがないお子さんでもバーコードを印刷してダンボールに貼り付けたりすればそれっぽい感じのカードで使えます。

## フォントについて

金額の表示にDSEGというフォントを利用しております。

Font "DSEG" by けしかん