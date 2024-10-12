<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
<div class="contents m-5">
    <p class="title">すべてのデータを初期化</p>
    <form action="wipe.php" method="POST">
        <input class="button is-link" type="submit" value="初期化を実行"></input>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $dir = glob('../data/*');
        foreach ($dir as $file) {
            //ファイルを削除する
            if (unlink($file)) {
                echo $file . 'の削除に成功しました。<br>';
            } else {
                echo $file . 'の削除に失敗しました。<br>';
            }
        }
        $list = fopen("../data/user-list.csv", "w");
        fclose($list);
    }
    ?>
</div>