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

if($_SERVER["REQUEST_METHOD"] == "GET" and isset($_GET["id"])){
    $history = "./history/" . $userkey . ".csv";
    if(file_exists($history)){
        $file = fopen($history, 'r');
        while (($data = fgetcsv($file)) !== FALSE) {
            if($data[5] == $_GET["id"]){
                $history_data = array(
                    "timestamp" => $data[0],
                    "shop_name" => $data[1],
                    "pay" => $data[2],
                    "type" => $data[3],
                    "content" => $data[4],
                    "payID" => $data[5],
                );
                break;
            }
        }
        fclose($file);

        if(isset($history_data)){
            $error = false;
            if($history_data["type"] == "+"){
                $payText = $history_data["shop_name"] . "からチャージ";
            }elseif($history_data["type"] == "-"){
                $payText = $history_data["shop_name"] . "に支払い";
            }else{
                $payText = $history_data["shop_name"] . "で精算";
            }
        }else{
            $error = true;
        }
    }
}else{
    $error = true;
}
?>
<html>

<head>
    <title>決済詳細 - いえPay</title>
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

    <link rel="stylesheet" href="./module/mobile-nav/style.css">
    <script src="./module/mobile-nav/script.js"></script>

    <style>
        .container{
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
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
        <?php if(!$error) : ?>
        <div class="card">
            <div class="card-content">
                <div class="has-text-centered">

                    <?php if($history_data["type"] == "+") : ?>
                        <div style="width: 100%;height: 10vh;" class="has-background-link mb-6 container">
                            <p class="title has-text-white" style="font-weight: bold;">チャージ</p>
                        </div>
                    <?php elseif($history_data["type"] == "-") : ?>
                        <div style="width: 100%;height: 10vh;" class="has-background-danger mb-6 container">
                            <p class="title has-text-white" style="font-weight: bold;">支払い</p>
                        </div>
                    <?php else : ?>
                        <div style="width: 100%;height: 10vh;" class="has-background-success mb-6 container">
                            <p class="title has-text-white" style="font-weight: bold;">精算</p>
                        </div>
                    <?php endif; ?>

                    <p class="title" style="font-weight: bold;"><?php echo $payText;?></p>
                    <?php if($history_data["content"] != "") : ?>
                        <p class="subtitle">内容: <?php echo $history_data["content"]?></p>
                    <?php endif; ?>
                    <p class="subtitle is-6"><?php echo date('Y年m月 H時i分s秒',$history_data['timestamp'])?></p>
                    <p class="title is-1 m-6" style="font-weight: bold;"><?php echo number_format($history_data["pay"]);?>円</p>
                    <hr>
                    <p>決済ID: <?php echo $history_data["payID"];?></p>
                </div>
            </div>
        </div>
        <?php else : ?>
        <p>エラーが発生しました。</p>
        <?php endif; ?>
    </div>

    <div id="mbNav" class="mobile-nav">
        <div>
            <a href="main.php">
                <span class="icon">
                    <i class="fas fa-2x fa-home"></i>
                </span>
                <p>ホーム</p>
            </a>
        </div>
        <div>
            <a href="history.php">
                <span class="icon">
                    <i class="fas fa-2x fa-history"></i>
                </span>
                <p>履歴</p>
            </a>
        </div>
        <div>
            <a href="logout.php">
                <span class="icon">
                    <i class="fas fa-2x fa-sign-out-alt"></i>
                </span>
                <p>ログアウト</p>
            </a>
        </div>
    </div>
</body>

</html>