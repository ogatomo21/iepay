<?php
// qr.php
// ?text={生成したいテキスト} のパラメーターに渡すとQRコードが生成できる
// 多分もう一生いじることはないやつ
// PHP QR Code を利用(https://phpqrcode.sourceforge.net/)
require_once "qr/qrlib.php";
if(isset($_GET['text']) and $_GET["text"] != ""){
$contents = $_GET['text'];
$filepath = 'tmp/' . $contents . '.png';
QRcode::png($contents, $filepath, QR_ECLEVEL_Q, 10);
header('Content-Type: image/png');
readfile($filepath);
unlink($filepath);
}else{
    echo "Please Input to text parameter.<br>Sample: qr.php?text=example";
}