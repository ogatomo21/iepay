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
if (strpos($id, 'shop') != 0) {
    header('Location: main.php');
    exit; // リダイレクト後にスクリプトを終了
}
?>
<html>

<head>
    <title>決済 - いえPay</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
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
    <script src="https://unpkg.com/@ericblade/quagga2@1.7.4/dist/quagga.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        let formSubmitted = false;

        function pay() {
            getform = document.forms.payform
            if (getform.balance.value == "") {
                alert("金額を入力してください。")
                return false;
            } else {
                if (getform.type.value == "") {
                    alert("種別を選択してください。")
                    return false;
                }
            }
            document.getElementById("balance").readonly = true;
            document.getElementById("pay").readonly = true;
            document.getElementById("charge").readonly = true;
            document.getElementById("seisan").readonly = true;
            document.getElementById("payBtn").disabled = true;

            if (formSubmitted) {
                return;
            }

            let video = document.createElement("video");
            let canvas = document.getElementById("canvas");
            let ctx = canvas.getContext("2d");
            let msg = document.getElementById("msg");

            const userMedia = {
                video: {
                    facingMode: "environment"
                }
            };
            navigator.mediaDevices.getUserMedia(userMedia).then((stream) => {
                video.srcObject = stream;
                video.setAttribute("playsinline", true);
                video.play();
                startTick();
            });

            function startTick() {

                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    canvas.height = video.videoHeight;
                    canvas.width = video.videoWidth;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    let img = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    let code = jsQR(img.data, img.width, img.height, {
                        inversionAttempts: "dontInvert"
                    });
                    if (code) {
                        drawRect(code.location); // Rect
                        var myform = document.getElementById("payform");
                        var qr = document.getElementById("user");
                        qr.value = code.data;
                        if (!formSubmitted) {
                            formSubmitted = true;
                            myform.submit();
                        }
                    }
                }
                qrtick = setTimeout(startTick, 1);
            }

            function drawRect(location) {
                drawLine(location.topLeftCorner, location.topRightCorner);
                drawLine(location.topRightCorner, location.bottomRightCorner);
                drawLine(location.bottomRightCorner, location.bottomLeftCorner);
                drawLine(location.bottomLeftCorner, location.topLeftCorner);
            }

            function drawLine(begin, end) {
                ctx.lineWidth = 4;
                ctx.strokeStyle = "#FF3B58";
                ctx.beginPath();
                ctx.moveTo(begin.x, begin.y);
                ctx.lineTo(end.x, end.y);
                ctx.stroke();
            }
        }
    </script>
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item">
                <p>いえPay 店舗用アプリケーション</p>
            </a>
        </div>
        <div class="navbar-end">
            <div class="navbar-item">
                <p class="m-2"><?php echo to_html($name); ?>としてログイン中(ID：<?php echo to_html($id); ?>)</p>
                <div class="buttons">
                    <a href="./logout.php" class="button is-danger">
                        <strong>ログアウト</strong>
                    </a>
                </div>
            </div>
    </nav>

    <div class="m-2">

        <div class="columns">
            <div class="column is-half">
                <div id="wrapper">
                    <canvas id="canvas" style="width:100%"></canvas>
                </div>
            </div>
            <div class="column is-half">
                <form onsubmit="return false;" action="result.php" method="POST" id="payform">
                    <label class="label">金額</label>
                    <div class="field has-addons">
                        <div class="control">
                            <input id="balance" name="balance" class="input" type="text" style="width: 10em" inputmode="numeric" required>
                        </div>
                        <div class="control">
                            <a class="button is-static">
                                円
                            </a>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">内容(任意)</label>
                        <div class="control">
                            <input class="input" type="text" name="content" placeholder="例) 1/1 仕事分">
                        </div>
                    </div>

                    <div class="control">
                        <label class="label">決済種別</label>
                        <label class="radio">
                            <input type="radio" id="pay" name="type" value="-" required>
                            支払い(F1)
                        </label>
                        <label class="radio">
                            <input type="radio" id="charge" name="type" value="+" required>
                            チャージ(F2)
                        </label>
                        <label class="radio">
                            <input type="radio" id="seisan" name="type" value="?" required>
                            精算(F3)
                        </label>
                    </div>
                    <input id="user" name="user" type="hidden" required>
                </form>
                <button id="payBtn" onClick="pay()" class="button is-link">
                    <span class="icon">
                        <i class="fas fa-check"></i>
                    </span>
                    <p>決済</p>
                </button>
                <button id="reloadBtn" class="button is-danger">
                    <span class="icon">
                        <i class="fas fa-redo"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
</body>
<script>
    document.getElementById('balance').select()

    document.getElementById('balance').addEventListener('keydown', function() {
        if (event.keyCode == 13) {
            pay();
        }
    })

    document.getElementById('reloadBtn').addEventListener('click', function() {
        location.reload()
    })

    window.document.onkeydown = function(event) {
        if (event.keyCode >= 112 && event.keyCode <= 123) {
            event.keyCode = null;
            event.returnValue = false;
            if (event.keyCode == 112) {
                document.getElementById("pay").checked = true
                pay()
            }
            if (event.keyCode == 113) {
                document.getElementById("charge").checked = true
                pay()
            }
            if (event.keyCode == 114) {
                document.getElementById("seisan").checked = true
                pay()
            }
        }
    };
</script>

</html>