ようこそ StackEdit へ!	{#welcome}
=====================


こんにちは、**StackEdit**[^stackedit]内の最初のマークダウン・ドキュメントです。役立つ情報が記載されているので削除しないでください。もし削除した場合でも、<i class="icon-cog"></i>`Settings`ダイアログの`Utils`タブから再度取得できます。

----------


ドキュメント {#document}
---------

**StackEdit** ではお使いのブラウザで作成されるすべてのドキュメントを自動的にローカルに保存し、**オフライン**でのアクセスを可能にしています！

> **注意:**
> 
> - StackEditは最初にロードされた後もオフラインで利用可能なアプリケーションです。
> - あなたのローカルドキュメントは別のブラウザまたはコンピュータ間では共有されません。
> - お使いのブラウザのデータを消去すると、 **すべてのローカルドキュメントが削除されてしまいます！** あなたの文書が **Google Drive** または **Dropbox** に同期（ [<i class="icon-share"></i> 同期](#synchronization) の項を参照）してバックアップされていることを確認してください。

#### <i class="icon-file"></i> ドキュメントの作成

ドキュメントパネルには、ナビゲーションバーの <i class="icon-folder-open"></i> ボタンを使用してアクセスできます。ドキュメントパネルで <i class="icon-file"></i> **New document** をクリックして新しい文書を作成することができます。

#### <i class="icon-folder-open"></i> ドキュメントの切り替え

すべてのローカルドキュメントは、ドキュメントパネルに一覧表示されます。リスト内のドキュメントをクリックするか、<KBD> Ctrl + [ </ KBD> および <KBD> Ctrl + ] </ KBD> を使用してドキュメントの切り替えを行うことができます。

#### <i class="icon-pencil"></i> ドキュメント名の変更

ナビゲーションバーでドキュメントのタイトルをクリックすると、現在のドキュメントの名前を変更することができます。

#### <i class="icon-trash"></i> ドキュメントの削除

ドキュメントパネルの <i class="icon-trash"></i> **Delete document** をクリックすることで現在のドキュメントを削除することができます。

#### <i class="icon-hdd"></i> ドキュメントのエクスポート

<i class="icon-provider-stackedit"></i> メニューから <i class="icon-hdd"></i> **Export to disk** をクリックすることで現在のドキュメントをファイルとして保存することができます。

> **ヒント:** ファイルの出力形式の詳細については、 [<i class="icon-upload"></i> ドキュメントの公開](#publish-a-document) の項を参照してください。

----------


同期 {#synchronization}
---------------

**StackEdit** では **Google Drive** や **Dropbox** と同期することで、 クラウド上にあなたのドキュメントを保存しておくことができます。これにより、ドキュメントの変更をアップロードしたり、最新バージョンをダウンロードすることが簡単に実現できます。

> **注意:**
> 
> - StackEdit内の任意のドキュメントをインポートするためには、 **Google Drive** または **Dropbox** へのフルアクセスが必要です。
> - インポートされたドキュメントは、お使いのブラウザにダウンロードされ、サーバーへ送信されません。
> - Google Drive に文書をエクスポートする際に問題が発生する場合、ブラウザ拡張機能をチェックして、必要に応じては無効化したり接続解除したりしてください。

#### <i class="icon-refresh"></i> ドキュメントを開く

<i class="icon-provider-gdrive"></i> `Google Drive` または <i class="icon-provider-dropbox"></i> `Dropbox` のサブメニューに行くことによって、およびインポートすると、文書が自動的に **Google Drive** / **Dropbox** のファイルと同期されます `Import from...` をクリックすることにより、クラウド上から文書をインポートすることができます。
<i class="icon-refresh"></i> **Synchronize** のサブメニュー <i class="icon-provider-gdrive"></i> **Google Drive** か <i class="icon-provider-dropbox"></i> **Dropbox** の **Open from...** をクリックすることでドキュメントを開くことができます。開いた後、ドキュメント内のすべての変更が自動的にあなたの **Google Drive** / **Dropbox** アカウントのファイルと同期されます。

#### <i class="icon-refresh"></i> ドキュメントの保存

<i class="icon-refresh"></i> **Synchronize** のサブメニュー **Save on...** をクリックすると任意のドキュメントを保存できます。もしドキュメントがすでに **Google Drive** や **Dropbox** と同期している場合でも、別の場所にエクスポートすることができます。 StackEditでは複数の場所、複数のアカウントが持つ1つのドキュメントを同期させることができます。

#### <i class="icon-refresh"></i> ドキュメントの同期

ドキュメントが <i class="icon-provider-gdrive"></i> **Google Drive** または <i class="icon-provider-dropbox"></i> **Dropbox** のファイルにリンクされると、StackEditは定期的（3分ごと）にダウンロードされ / すべての変更をアップロードすることで同期を行います。必要に応じて、マージが実行され、競合が検出されます。

ドキュメントを変更し、同期を強制したい場合は、ナビゲーションバーの <i class="icon-refresh"></i> ボタンをクリックしてください。

> **注意:** 同期するドキュメントがない場合、 <i class="icon-refresh"></i> ボタンは無効化されています。

#### <i class="icon-refresh"></i> ドキュメント同期の管理

<i class="icon-refresh"></i> **Synchronize** のサブメニュー <i class="icon-refresh"></i> **Manage synchronization** をクリックすると、複数の場所で同期されている1つのドキュメントを管理することができます。ここでは、あなたのドキュメントに関連づけられている同期の場所を削除できます。

> **注意:** もし **Google Drive** や **Dropbox** からファイルを削除すると、そのドキュメントとの同期は解除されます。

----------


公開
-------------

ドキュメントを校了した後、あなたは StackEdit とは異なるウェブサイト上に直接それを公開することができます。現時点での StackEdit は **Blogger** 、 **Dropbox** 、 **Gist** 、 **GitHub** 、 **Google Drive** 、 **Tumblr** 、 **WordPress** 、そして任意のSSHサーバに公開することができます。

#### <i class="icon-upload"></i> ドキュメントの公開 {#publish-a-document}

<i class="icon-upload"></i> **Publish** のサブメニューを開きウェブサイトを選択することでドキュメントを公開することができます。ダイアログボックスで選択可能な公開形式は以下の通りです。

- Markdown : （例えば **GitHub** のような）それを解釈することができるウェブサイト上へマークダウン・テキストを公開する
- HTML : （例えばブログなどに）HTMLに変換した文書を公開する
- Template : 出力を自由に制御できます

> **注意:** デフォルトテンプレートはHTML形式でドキュメントが包まれたシンプルなウェブページです。テンプレートは <i class="icon-cog"></i> **Settings** ダイアログの **Advanced** タブでカスタマイズすることができます。

#### <i class="icon-upload"></i> 公開後の更新

公開した後、StackEdit は公開ドキュメントにリンクされ、ドキュメントの状態が維持されるため、容易にドキュメントを更新することができます。あなたがドキュメントを変更し、公開ドキュメントを更新したい場合、ナビゲーションバーの <i class="icon-upload"></i> ボタンをクリックします。

> **注意:** <i class="icon-upload"></i> ボタンはドキュメントがまだ公開されていない場合は無効になっています。

#### <i class="icon-upload"></i> ドキュメント公開管理

1つのドキュメントが複数の場所に公開することができるため、<i class="icon-provider-stackedit"></i> メニューパネル内の <i class="icon-upload"></i> **Manage publication** をクリックすることで、その公開場所を管理することができます。ここでは、ドキュメントに関連づけられている公開場所を削除できます。

> **注意:** ファイルがウェブサイトやブログから削除されている場合、ドキュメントをその場所に公開することができなくなります。

----------


マークダウン・エクストラ
--------------------

StackEdit は **Markdown Extra** をサポートし、いくつかの素晴らしい機能が **Markdown** 構文に追加されています。

> **ヒント:**  <i class="icon-cog"></i> **Settings** ダイアログボックスの **Extensions** タブ内で任意の **Markdown Extra** 機能を無効にできます。

> **注意:** **Markdown構文** の詳細は [こちら][2] を参照。 **Markdown Extra** に関する詳細は [こちら][3] です。


### テーブル

**Markdown Extra** によるテーブル（作表）のための構文は次のとおりです。

```
Item     | Value
-------- | ---
Computer | $1600
Phone    | $12
Pipe     | $1
```

Item     | Value
-------- | ---
Computer | $1600
Phone    | $12
Pipe     | $1

コロンを使うことでセル内の文字寄せができます。

```
| Item     | Value | Qty   |
| :------- | ----: | :---: |
| Computer | $1600 |  5    |
| Phone    | $12   |  12   |
| Pipe     | $1    |  234  |
```

| Item     | Value | Qty   |
| :------- | ----: | :---: |
| Computer | $1600 |  5    |
| Phone    | $12   |  12   |
| Pipe     | $1    |  234  |


### 定義リスト

**Markdown Extra** による定義リストのための構文は次のとおりです。

```
Term 1
Term 2
:   Definition A
:   Definition B

Term 3

:   Definition C

:   Definition D

	> part of definition D
```

Term 1
Term 2
:   Definition A
:   Definition B

Term 3

:   Definition C

:   Definition D

	> part of definition D


### フェンス付コードブロック

GitHubのフェンスで囲まれたコードブロックも **Highlight.js** 構文強調表示でサポートされています。

```
// Foo
var bar = 0;
```

> **ヒント:** **Prettify** の代わりに **Highlight.js** を使用するには、 <i class="icon-cog"></i> **Settings** ダイアログで **Markdown Extra** 拡張機能にチェックします。

> **注意:** 詳しい情報は下記参照 :

> - **Prettify** 構文のついて [詳細][5]
> - **Highlight.js** 構文について [詳細][6]


### 脚注

Wikipediaなどでおなじみの参考リンクを脚注にページ内リンクとして集約記述する構文です。

```
You can create footnotes like this[^footnote].

  [^footnote]: Here is the *text* of the **footnote**.
```

You can create footnotes like this[^footnote].

  [^footnote]: Here is the *text* of the **footnote**.


### SmartyPants

SmartyPantsは、「スマート」タイポグラフィック句読文字のHTMLエンティティにアスキー句読文字に変換します。例えば：

|                  | ASCII                        | HTML              |
 ----------------- | ---------------------------- | ------------------
| Single backticks | `'Isn't this fun?'`            | 'Isn't this fun?' |
| Quotes           | `"Isn't this fun?"`            | "Isn't this fun?" |
| Dashes           | `-- is en-dash, --- is em-dash` | &ndash; is en-dash, &mdash; is em-dash |


### 目次（Table of contents）

マーカー `[TOC]` を使用して目次を挿入することができます。

[TOC]


### 数式（MathJax）

あなたは [math.stackexchange.com][1] のように、 **MathJax** を使用して *LaTeX* の数式をレンダリングすることができます。

```
The *Gamma function* satisfying $\Gamma(n) = (n-1)!\quad\forall n\in\mathbb N$ is via the Euler integral

$$
\Gamma(z) = \int_0^\infty t^{z-1}e^{-t}dt\,.
$$
```

The *Gamma function* satisfying $\Gamma(n) = (n-1)!\quad\forall n\in\mathbb N$ is via the Euler integral

$$
\Gamma(z) = \int_0^\infty t^{z-1}e^{-t}dt\,.
$$


> **ヒント:** 数式があなたのウェブサイト上で正しくレンダリングされるようにするには、テンプレートに **MathJax** をインクルードしなければなりません。

```
<script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS_HTML"></script>
```

> **注意:** **LaTeX** 数式に関する詳細な情報は [こちら][4] を参照してください。


### UML 図式

下記のようなシーケンス図を描画することができます。

```sequence
Alice->Bob: Hello Bob, how are you?
Note right of Bob: Bob thinks
Bob-->Alice: I am good thanks!
```

フローチャートも描画できます。

```flow
st=>start: Start
e=>end
op=>operation: My Operation
cond=>condition: Yes or No?

st->op->cond
cond(yes)->e
cond(no)->op
```

> **注意:** 詳細情報の参照先:

> - **Sequence diagrams** 構文について [詳細][7],
> - about **Flow charts** 構文について [詳細][8].

### Support StackEdit

[![](https://cdn.monetizejs.com/resources/button-32.png)](https://monetizejs.com/authorize?client_id=ESTHdCYOi18iLhhO&summary=true)

  [^stackedit]: [StackEdit](https://stackedit.io/) は、Stack Overflow や他のスタック交換サイトで使用されるマークダウン・ライブラリと、PageDownをベースとしたフル機能のオープンソース・マークダウン・エディタです。 ↩


  [1]: http://math.stackexchange.com/
  [2]: http://daringfireball.net/projects/markdown/syntax "Markdown"
  [3]: https://github.com/jmcmanus/pagedown-extra "Pagedown Extra"
  [4]: http://meta.math.stackexchange.com/questions/5020/mathjax-basic-tutorial-and-quick-reference
  [5]: https://code.google.com/p/google-code-prettify/
  [6]: http://highlightjs.org/
  [7]: http://bramp.github.io/js-sequence-diagrams/
  [8]: http://adrai.github.io/flowchart.js/
  [6]: http://highlightjs.org/
