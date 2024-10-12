<?php
session_start();
$_SESSION = array(); // セッション情報をクリアする
session_destroy(); // セッションを終了する
$alert = "<script type='text/javascript'>alert('ログアウトしました。ログイン画面に遷移します。');location.href = 'index.php';</script>";
echo $alert;
?>
