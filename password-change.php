<?php date_default_timezone_set('Asia/Tokyo'); ?>
<?php
require_once 'read_write.php';

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
    $userkey = $data['key'];
} else {
    $login = false;
}

// ログインしていない場合、ログインページに遷移
if (!$login) {
    $alert = "<script type='text/javascript'>alert('セッションの期限が終了したか、不正なアクセスです。ログイン画面に遷移します。');location.href = 'index.php';</script>";
    echo $alert;
    exit;
}
//======= ログイン状況の確認END ==============

if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["old-password"]) and isset($_POST["new-password"]) and isset($_POST["key"])) {
    $filename = "./data/" . $_POST["key"] . ".txt";
    $lines = file($filename);
    if (password_verify($_POST["old-password"], trim($lines[0]))) {
        $newPass = password_hash($_POST["new-password"], PASSWORD_DEFAULT);
        $write = $newPass . "\n" . $lines[1];
        file_put_contents($filename, $write);
        echo "<script>alert('パスワードを変更しました。ログアウトします。');location.href = 'logout.php';</script>";
        exit;
    } else {
        echo "<script>alert('現在のパスワードが違います。');location.href = 'password-change.php';</script>";
    }
}
?>
<html>

<head>
    <title>パスワード変更 - いえPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta charset="UTF-8">

    <meta name="msapplication-square70x70logo" content="icon/site-tile-70x70.png">
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
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item">
                <p>いえPay</p>
            </a>
        </div>
    </nav>

    <div class="m-5">
        <p class="title">パスワード変更</p>
        <p><b>※注意！ パスワード変更はキャンセルできません。<br>また、変更後は反映のためログアウトされます。</b></p><br>
        <form action="password-change.php" method="POST">
            <div class="card">
                <div class="card-content">
                    <div class="field">
                        <label class="label">現在のパスワード</label>
                        <div class="control">
                            <input class="input" type="password" required name="old-password" autocomplete="current-password">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">新しいパスワード</label>
                        <div class="control">
                            <input class="input" type="password" required name="new-password" autocomplete="new-password">
                        </div>
                    </div>

                    <input type="hidden" name="key" value="<?php echo to_html($userkey); ?>">
                    <input type="submit" value="パスワードを変更" class="button is-danger">
                </div>
        </form>
    </div>
</body>

</html>