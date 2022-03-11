# fixed-layout-EPUB3-generator-PHP
フィックス型EPUB3作成ツール（PHP製）

このツールを使って、ブラウザから簡単にEPUBファイルを作成できる

## 前提条件
* OS: Windows
* Apache, 参考: https://www.apachefriends.org/ja/index.html
* ZIP command, 参考: https://qiita.com/Shi-nakaya/items/83d2b2e2b34b897d3df8

## 設定
Apacheサーバのhtdocsにフォルダ ”[このフォルダをコピーして、名前をISBNか識別番号に変更する]” を入れる

## 使い方
### Step. 0
* フォルダ名に書いてある通りに、フォルダをコピーして、名前をISBNか識別番号(半角英数字のみ)に変更する
* 画像データを用意する。カバー画像は「img-0000.jpg」に、本文画像は「img-0001.jpg, img-0002.jpg... (img-とページ[4桁])」に命名。すべての画像は同じサイズでなければならない
* ”目次作成用_テンプレート.xlsx” を参照し、あらかじめ目次・構成を用意する

### Step. 1
* Apacheサーバを立ち上げて、http://localhost/[path-to-the-folder]/[ISBNか識別番号]/EPUB.html にアクセス
* 指示通りに書誌情報を入力する
* submitボタンを押す

### Step. 2
* ”目次作成用_テンプレート.xlsx” を見ながら入力する
* 「&」や「<」などの記号はエラーを発生させる可能性もあるので、章節名に記号を含む場合、記号を全角文字にしてください
* submitボタンを押す

### Step. 3
* 事前に用意した画像データを ”item/image” に入れる
* ”item2epub.bat” を実行し、EPUBファイルを生成
* EPUB-CheckerとKindle Previewerで作成したEPUBファイルを確認
* EPUB-Checker, 参考: https://www.pagina.gmbh/produkte/epub-checker/
* Kindle Previewer, 参考: https://kdp.amazon.co.jp/ja_JP/help/topic/G202131170

## ライセンス
©lamblabo 2022

Apache License, Version 2.0

https://licenses.opensource.jp/Apache-2.0/Apache-2.0.html
