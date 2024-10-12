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

// ファイルを読み込みモードで開く
$filename = "balance/$userkey.txt";

if (!file_exists($filename)) {
    // ファイルを作成し0を書き込む
    file_put_contents($filename, '0');
}

$file = fopen($filename, 'r');

if ($file) {
    // ファイルから現在の残高を読み取る
    $currentBalance = fgets($file);
    fclose($file);
}

//======= ログイン状況の確認END ==============
if (strpos($id, 'shop') === 0) {
    header('Location: shop.php');
    exit; // リダイレクト後にスクリプトを終了
}
?>
<html>

<head>
    <title>ホーム - いえPay</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        @font-face {
            font-family: "dseg7";
            src: url("./font/DSEG7ClassicMini-Bold.woff2") format("woff2");
            src: url("./font/DSEG7ClassicMini-Bold.woff") format("woff");
            src: url("./font/DSEG7ClassicMini-Bold.ttf") format("truetype");
        }

        * {
            font-family: "ヒラギノ角ゴ W3", "Noto Sans JP", sans-serif;
            ;
        }

        .container {
            display: flex;
            /* flexbox */
            justify-content: flex-start;
            /* 水平方向 */
            align-items: center;
            /* 垂直方向 */
            width: fit-content;
        }

        #balanceText {
            font-family: "dseg7", "ヒラギノ角ゴ W3", "Noto Sans JP", sans-serif;
        }
    </style>

    <link rel="stylesheet" href="./module/mobile-nav/style.css">
    <script src="./module/mobile-nav/script.js"></script>
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item">
                <p>いえPay</p>
            </a>
        </div>
    </nav>

    <div class="m-2">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div style="text-align: center;">
                        <p class="title">残高: <span id="balanceText"><?php echo $currentBalance; ?></span>円</p><br>
                        <div class="buttons" style="justify-content: center;">
                            <button id="modal-open" class="button is-primary">
                                <span class="icon">
                                    <i class="fas fa-qrcode"></i>
                                </span>
                                <p>QRコード提示</p>
                            </button>
                            <button id="changeView" class="button is-link">
                                <span class="icon">
                                    <i class="fas fa-wallet"></i>
                                </span>
                                <p>残高表示/非表示</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <div id="modal" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">支払い用QRコード</p>
                </header>
                <section class="modal-card-body" style="text-align: center;">
                    <p><b>QRコードの取り扱いには十分注意してください。</b></p>
                    <img src="qr.php?text=<?php echo $userkey; ?>" width="200rem" /><br>
                    <p><small><b><span id="nowTime"></span></b></small></p>
                </section>
                <footer class="modal-card-foot">
                    <button id="modal-close" class="button">閉じる</button>
                </footer>
            </div>
        </div><br>

        <div class="card">
            <div class="card-content">
                <p class="title">
                    <span class="icon">
                        <i class="fas fa-user"></i>
                    </span>
                    会員メニュー
                </p>
                <div class="m-0" style="width: fit-content;">
                    <div class="container">
                        <img src="user.svg" style="width:10vh" class="ml-3">
                        <p class="subtitle m-3"><?php echo to_html($name); ?> 様</p><br>
                    </div>
                </div><br>

                <p class="mx-2"><small>
                        お客様番号: <?php echo to_html($userkey) ?><br>
                        ご登録メールアドレス: <?php echo to_html($id) ?>
                    </small></p><br>

                <div class="buttons">
                    <a href="./username-change.php" class="button is-primary">
                        <span class="icon">
                            <i class="far fa-user"></i>
                        </span>
                        <p>ユーザー名変更</p>
                        </あ>

                        <a href="./password-change.php" class="button is-link">
                            <span class="icon">
                                <i class="fas fa-key"></i>
                            </span>
                            <p>パスワード変更</p>
                        </a>

                        <button class="button is-warning" disabled>
                            <span class="icon">
                                <i class="far fa-envelope"></i>
                            </span>
                            <p>メールアドレス変更</p>
                        </button>

                        <a href="./logout.php" class="button is-danger">
                            <span class="icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </span>
                            <p>ログアウト</p>
                        </a>
                </div>
                <p><small>※現在メールアドレス変更はWebでは行えません。管理者にお問い合わせください。(ご希望に添えない場合もございます)</small></p>
            </div>
        </div><br>

        <div class="card">
            <div class="card-content">
                <p class="title">
                    <span class="icon">
                        <i class="fas fa-history"></i>
                    </span>
                    最近の取引履歴
                </p>
                <p class="subtitle has-text-right"><a href="history.php">
                        <span class="icon">
                            <i class="fas fa-angle-double-right"></i>
                        </span>
                        もっと見る
                    </a></p>
                <?php ie_read2($userkey); ?>
            </div>
        </div>
    </div>

    <div id="mbNav" class="mobile-nav">
        <div>
            <a href="main.php">
                <i class="fas fa-lg fa-home mb-1"></i>
                <p>ホーム</p>
            </a>
        </div>
        <div>
            <a href="history.php">
                <i class="fas fa-lg fa-history mb-1"></i>
                <p>履歴</p>
            </a>
        </div>
        <div class="has-background-danger">
            <a href="logout.php" class="has-text-white">
                <i class="fas fa-lg fa-sign-out-alt mb-1"></i>
                <p>ログアウト</p>
            </a>
        </div>
    </div>

</body>

<script>
    modal = document.getElementById("modal");
    var currentBalance = "<?php echo $currentBalance; ?>";
    balanceText = document.getElementById("balanceText");
    balanceView = true;


    document.getElementById("modal-open").addEventListener('click', function() {
        modal.classList.add('is-active');
    })

    document.getElementById("modal-close").addEventListener('click', function() {
        modal.classList.remove('is-active');
    })

    document.getElementById("changeView").addEventListener('click', function() {
        if (balanceView == true) {
            balanceText.innerText = "---";
            balanceView = false;
        } else {
            balanceText.innerText = currentBalance;
            balanceView = true;
        }
    })

    function kt(num) {
        // 桁数が1桁だったら先頭に0を加えて2桁に調整する
        var ret = num;
        if (num < 10) {
            ret = "0" + num;
        }
        return ret;
    }

    function getTime() {
        nowTime = document.getElementById("nowTime")

        var now = new Date();
        var year = now.getFullYear(); // 年(4桁の西暦)
        var mon = now.getMonth() + 1; // 月(1～12)
        var date = now.getDate(); // 日(1～31)
        var hour = now.getHours(); // 時(0～23)
        var min = now.getMinutes(); // 分(0～59)
        var sec = now.getSeconds(); // 秒(0～59)

        var writeDate = year + "-" + kt(mon) + "-" + kt(date) + " " + kt(hour) + ":" + kt(min) + ":" + kt(sec);
        nowTime.innerText = writeDate;

        setInterval('getTime()', 1);
    }
    getTime()
</script>

</html>