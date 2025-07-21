<?php
// 大文字だとバグるので小文字に変換
$username = strtolower($_POST["user"]);
$password = $_POST["password"];

// ディレクトリトラバーサル対策
$username = basename($username);

// CSVファイルを読み込む
if (($handle = fopen('data/user-list.csv', 'r')) !== false) {
    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        // 2列目のユーザー名(表面)と完全一致した場合
        if ($data[1] === $username) {
            // 1列目のユーザーキー
            $userkey = $data[0];
            break; // 必要であれば、一致したらループを終了する
        }
    }
    fclose($handle);
} else {
    echo "ユーザーデータが破損しています。初期化してください。";
    exit;
}

if(!isset($userkey) or !file_exists("data/".$userkey.".txt")){ // ユーザーデータがない場合
    header("Location: index.php?error");
    exit;

}else{ // ユーザーデータがある場合

    //ユーザーデータの取得
    $user_data = fopen('data/'.$userkey.'.txt', "r");

    $password_hash = trim(fgets($user_data)); // trim関数：文字列の最初と最後にあるホワイトスペースを取り除く
    $name = trim(fgets($user_data));
    // $password_hash = str_replace(array("\r\n","\r","\n"), '', fgets($user_data));
    // $role = str_replace(array("\r\n","\r","\n"), '', fgets($user_data));

    fclose($user_data);
    // パスワードの照合
    if(!password_verify($password,$password_hash)){ // パスワードが一致しない場合
        header("Location: index.php?error");
        exit;
    }

    // ログインOK
    session_start();
    $data = [];
    $data['id'] = $username;
    $data['name'] = $name;
    $data['key'] = $userkey;

    $_SESSION['data'] = $data; // ユーザ情報をセッション変数に格納{
    
    if (strpos($username, 'shop') === 0) {
        header('Location: shop.php');
        exit;
    } else {
        header('Location: main.php');
        exit;
    }
    exit;
}
?>

