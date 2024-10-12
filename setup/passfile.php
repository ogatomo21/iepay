<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
<div class="contents m-5">
    <p class="title">ログインデータパスワードファイル生成</p>
    <form action="passfile.php" method="POST">
        <div class="field">
            <label class="label">ユーザーID</label>
            <div class="control">
                <input class="input" type="text" name="id" style="width: 20em" required>
            </div><br>
            <p>例)ユーザー:1000001 店舗:shop001<br>店舗の場合はshopを先頭に付けないと反応しません。</p>
        </div>

        <div class="field">
            <label class="label">ユーザー名(店舗名)</label>
            <div class="control">
                <input class="input" type="text" placeholder="例)山田太郎(山田商店)" name="username" style="width: 20em" autocomplete="new-password" required>
            </div>
        </div>

        <div class="field">
            <label class="label">設定するパスワード</label>
            <div class="control">
                <input class="input" type="password" name="password" style="width: 20em" autocomplete="new-password" required>
            </div>
        </div>

        <input class="button is-link" type="submit"></input>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $pass_old = $_POST["password"];
        $userid = strtolower($_POST["id"]);
        $username = $_POST["username"];
        $pass_hash = password_hash($pass_old, PASSWORD_DEFAULT);
        $userkey = uniqid();
        $filedir = "../data/$userkey.txt";
        $userlist = fopen('../data/user-list.csv', 'a');
        $write = $userkey . "," . $userid . "\n";
        fwrite($userlist, $write);
        echo "<hr>";
        echo "「 $filedir 」として生成しました";
        file_put_contents($filedir, "$pass_hash\n$username");
    }
    ?>
</div>
