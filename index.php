<?php
/*
    コードレビューのメモ
*/

/*
    あんまりスネークケースは使わないね
    PSRっていうPHPのコーディング規約がある
    readmeにコーディングスタイルを書いとくといいかも？
    そこで psr-2に準拠、とか書いてあるとわかりやすい
*/
define('RECIPE_FILE_PATH', 'data/recipe.txt');

$recipe_names = lines_from_file(RECIPE_FILE_PATH);

// それぞれIDを振って$recipesに入れる
$recipes = array();

/*
    $cnt = count($arr) の話。こっちのほうがいいのかなって思ったらとりあえずベンチマークを取ること。
    裏付けがあった上でこっちのほうが綺麗だし早いってなるのが一番いい。
    ただ、速くなるけどリーダブルさを失う、っていう場面っていうのはあって、実際やることもあるけど
    そういう小手先の変更だと大したことない、アルゴリズムの変更のほうがずっと速くなる

    $cntを他で使わないんなら、条件で $i < count($arr) したほうがいいかも。

    この場合は
    foreach ($recipe_names as $id => $name) {
    のようにした方がいい。
    for だと条件部分でバグを埋め込むことがあるけど
    foreach はそういったことがないので、使わない手はない。
*/
for ($id = 0, $cnt = count($recipe_names); $id < $cnt; $id++) {
    $recipes[$id] = new Recipe($id, $recipe_names[$id]);
}

/*
    symfony component というコマンドラインツール開発用のフレームワークがある
*/
// $argv[1]にIDが指定される
if (isset($argv[1])) {
    // IDが指定されたらそのIDのレシピを出力する
    /*
        argv[n]は何度もでてこないほうがいい
        修正するのが大変だから。
    */
    $id = intval($argv[1]);
    echo $recipes[$id]."\n";
}
else {
    // IDが指定されなかったらすべて出力する
    echo_array_as_lines($recipes);
    /*
        あんまり関数化する意味がない？
        ただ単に出力するだけなら、ここにforeach書いちゃったほうが
        あるいはRecipeDictionaryっていうクラスのtoStringでやっちゃうとか

        as は冗長だし、複数形になってたら配列を取ることはわかるからarrayもいらない
        ライブラリなら、どんな引数が渡ってくるかわからないから、汎用的な名前をつけたいけど、
        この場合アプリケーションなので、レシピの情報を渡したらいい感じに処理してくれるー、みたいな関数名のがいい。
        この場合output_recipesとか。ライブラリならoutput_linesとか。
    */
}


/**
 * ファイルから1行ずつ読み込んで配列を返す。
 */
function lines_from_file($path) {
    return file($path, FILE_IGNORE_NEW_LINES);
}
/**
 * 配列の要素を改行区切りですべて表示する。
 */
function echo_array_as_lines($arr) {
    foreach ($arr as $val) {
        echo $val."\n";
    }
}

class Recipe {
    private $id;
    private $name;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
    public function __toString() {
        return $this->id.': '.$this->name;
    }
}

