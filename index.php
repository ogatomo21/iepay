<?php
// HTMLエスケープ関数 <- 取得した任意のHTMLコードを実行する脆弱性に対処します
function to_html($str)
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, "UTF-8");
}
//======= ログイン状況の確認 ==============
session_start();

if (isset($_SESSION['data'])) {
    $login = true;
    $data = $_SESSION['data'];
    $id = $data['id'];
    $name = $data['name'];
} else {
    $login = false;
}

// すでにログインしている場合、メインページに遷移
if ($login) {
    header('Location: main.php');
    exit;
}

//======= ログイン状況の確認END ==============

if (isset($_GET["error"])) {
    $error = true;
} else {
    $error = false;
}

?>
<html>

<head>
    <title>ログイン - いえPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/CreativeBulma/bulma-tooltip@master/dist/bulma-tooltip.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="UTF-8">

    <meta name="msapplication-square70x70logo" content="icon/icon/site-tile-70x70.png">
    <meta name="msapplication-square150x150logo" content="icon/site-tile-150x150.png">
    <meta name="msapplication-wide310x150logo" content="icon/site-tile-310x150.png">
    <meta name="msapplication-square310x310logo" content="icon/site-tile-310x310.png">
    <meta name="msapplication-TileColor" content="#0078d7">
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="icon/favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="icon/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="icon/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="icon/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="icon/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="icon/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="icon/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="icon/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="icon/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="icon/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="icon/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="36x36" href="icon/android-chrome-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48" href="icon/android-chrome-48x48.png">
    <link rel="icon" type="image/png" sizes="72x72" href="icon/android-chrome-72x72.png">
    <link rel="icon" type="image/png" sizes="96x96" href="icon/android-chrome-96x96.png">
    <link rel="icon" type="image/png" sizes="128x128" href="icon/android-chrome-128x128.png">
    <link rel="icon" type="image/png" sizes="144x144" href="icon/android-chrome-144x144.png">
    <link rel="icon" type="image/png" sizes="152x152" href="icon/android-chrome-152x152.png">
    <link rel="icon" type="image/png" sizes="192x192" href="icon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="256x256" href="icon/android-chrome-256x256.png">
    <link rel="icon" type="image/png" sizes="384x384" href="icon/android-chrome-384x384.png">
    <link rel="icon" type="image/png" sizes="512x512" href="icon/android-chrome-512x512.png">
    <link rel="icon" type="image/png" sizes="36x36" href="icon/icon-36x36.png">
    <link rel="icon" type="image/png" sizes="48x48" href="icon/icon-48x48.png">
    <link rel="icon" type="image/png" sizes="72x72" href="icon/icon-72x72.png">
    <link rel="icon" type="image/png" sizes="96x96" href="icon/icon-96x96.png">
    <link rel="icon" type="image/png" sizes="128x128" href="icon/icon-128x128.png">
    <link rel="icon" type="image/png" sizes="144x144" href="icon/icon-144x144.png">
    <link rel="icon" type="image/png" sizes="152x152" href="icon/icon-152x152.png">
    <link rel="icon" type="image/png" sizes="160x160" href="icon/icon-160x160.png">
    <link rel="icon" type="image/png" sizes="192x192" href="icon/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="196x196" href="icon/icon-196x196.png">
    <link rel="icon" type="image/png" sizes="256x256" href="icon/icon-256x256.png">
    <link rel="icon" type="image/png" sizes="384x384" href="icon/icon-384x384.png">
    <link rel="icon" type="image/png" sizes="512x512" href="icon/icon-512x512.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icon/icon-16x16.png">
    <link rel="icon" type="image/png" sizes="24x24" href="icon/icon-24x24.png">
    <link rel="icon" type="image/png" sizes="32x32" href="icon/icon-32x32.png">
    <link rel="manifest" href="manifest.json">

</head>

<body>
    <div class="m-5">
        <p style="text-align:center;"><img width="300rem" src="./image.png"></p><br>
        <?php if ($error) : ?>
            <article class="message is-danger">
                <div class="message-body">
                    ユーザー名またはパスワードが違います。
                </div>
            </article>
        <?php endif; ?>
        <div class="box" style="text-align: center;margin: 0 auto;">
            <form action="login.php" method="POST">
                <div class="field">
                    <label class="label">メールアドレス</label>
                    <div class="control">
                        <input id="user" name="user" class="input" type="text" style="width: 20rem" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">パスワード</label>
                    <div class="control">
                        <input id="password" name="password" class="input" type="password" style="width: 20rem" required>
                    </div>
                </div>
                <p class="has-tooltip-bottom" data-tooltip="ログインする際はしっかりとドメインを確認してください。">ログイン先ドメイン <span class="icon is-small"><i class="fas fa-info-circle is-small"></i></span> : <?php echo to_html($_SERVER['SERVER_NAME']); ?></p><br>
                <article class="message is-primary">
                </article>
                <div class="control">
                    <input type="submit" class="button is-primary is-outlined" value="ログイン"></input>
                </div>
            </form>

        </div>
    </div>
</body>

</html>