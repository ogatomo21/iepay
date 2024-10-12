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
$charge = charge_amount($userkey);
$usage = usage_amount($userkey);

$charge2 = number_format(charge_amount($userkey));
$usage2 = number_format(usage_amount($userkey));

$pieData = "[" . $usage . "," . $charge . "]";
?>
<html>

<head>
    <title>履歴 - いえPay</title>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <link rel="stylesheet" href="./module/mobile-nav/style.css">
    <script src="./module/mobile-nav/script.js"></script>

    <style>
        .canvas-container {
            position: relative;
            width: calc(100% - 40px);
            height: 300px;
            overflow: hidden;
            margin: 20px;
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
        <p class="title mb-6">履歴</p>
        <p class="subtitle">今月の取引金額の内訳</p>
        <?php if ($charge == 0 and $usage == 0) : ?>
            <p class="has-text-centered m-6">今月の利用データはありません</p>
        <?php else : ?>
            <div id="canvas-continer" class="canvas-container">
                <canvas id="monthlyData" width="500" height="500"></canvas>
            </div>
        <?php endif; ?>
        <?php ie_read3($userkey); ?>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var container = document.getElementById("canvas-container")
            var chart = document.getElementById("monthlyData")
            ctx.width = container.width();
            ctx.height = 300;
        })

        var ctx = document.getElementById("monthlyData");
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["利用額", "チャージ額"],
                datasets: [{
                    backgroundColor: [
                        "#F44336",
                        "#2196F3",
                    ],
                    data: <?php echo $pieData; ?>
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            // This more specific font property overrides the global property
                            font: {
                                size: 20
                            }
                        }
                    },
                    datalabels: {
                        color: 'white',
                        font: {
                            size: 20
                        },
                        formatter: function(value, context) {
                            var formattedValue = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            return formattedValue + '円';
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
</body>

</html>