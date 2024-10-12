<?php date_default_timezone_set('Asia/Tokyo'); ?>
<?php

function ie_write($wid, $wshop, $amount, $wtype, $wcontent)
{
    // 現在の日時を取得
    $currentDateTime = time();

    //履歴表示用にユニークなIDをCSVの頭につける
    $paymentID = uniqid("iepay-");

    // 追加する履歴をCSVフォーマットに整形
    $newTransactionLine = $currentDateTime . ',' . $wshop . ',' . $amount . ',' . $wtype . ',' . $wcontent . "," . $paymentID;

    // CSVファイルに追記
    $csvFile = "./history/$wid.csv";
    $file = fopen($csvFile, 'a');

    if ($file) {
        fwrite($file, "\n" . $newTransactionLine);
        fclose($file);
    } else {
        echo '履歴の書き込みに失敗しました。';
    }
}

function ie_read($rid)
{
    // CSVファイルのパス
    $csvFile = "./history/$rid.csv";

    // 既存の残高
    $currentBalance = 0;


    if (!file_exists($csvFile)) {
        // ファイルを作成
        file_put_contents($csvFile, '');
    }

    // CSVファイルを読み込みモードで開く
    $file = fopen($csvFile, 'r');

    if ($file) {
        // CSVデータを格納する配列を初期化
        $transactions = array();

        while (($line = fgetcsv($file)) !== false) {
            // 空白行を無視
            if (count($line) !== 5) {
                continue;
            }

            $transactionDateTime = DateTime::createFromFormat('Y-m-d H:i', $line[0]);
            $shopName = $line[1];
            $transactionAmount = (float) $line[2];
            $transactionType = $line[3];
            $transactionContent = $line[4];

            // 取引情報を配列に格納
            $transactions[] = array(
                'date' => $transactionDateTime,
                'shop' => $shopName,
                'amount' => $transactionAmount,
                'type' => $transactionType,
                'content' => $transactionContent,
            );
        }

        // 取引日時でソート（新しい順）
        usort($transactions, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        // テーブルのヘッダーを出力
        echo '<table class="table">';
        echo '<thead><tr><th>取引日時</th><th>店舗名</th><th>取引金額</th><th>取引種別</th><th>取引内容</th></tr></thead>';

        // ソートされた取引情報を出力
        foreach ($transactions as $transaction) {
            echo '<tr>';
            echo '<td>' . $transaction['date']->format('Y年m月d日 H時i分') . '</td>';
            echo '<td>' . $transaction['shop'] . '</td>';
            echo '<td>' . $transaction['amount'] . '円</td>';
            if ($transaction['type'] === '?') {
                echo '<td><span class="has-text-success">精算</span></td>';
            } else {
                echo '<td>' . ($transaction['type'] === '+' ? '<span class="has-text-link">チャージ</span>' : '<span class="has-text-danger">支払い</span>') . '</td>';
            }
            echo '<td>' . $transaction['content'] . '</td>';
            echo '</tr>';
        }

        fclose($file);
        echo '</table>';
    } else {
        echo '履歴の読み込みに失敗しました。';
    }
}

function ie_read2($rid)
{
    // CSVファイルのパス
    $csvFile = "./history/$rid.csv";

    // 既存の残高
    $currentBalance = 0;


    if (!file_exists($csvFile)) {
        // ファイルを作成
        file_put_contents($csvFile, '');
    }

    // CSVファイルを読み込みモードで開く
    $file = fopen($csvFile, 'r');

    if ($file) {
        // CSVデータを格納する配列を初期化
        $transactions = array();

        while (($line = fgetcsv($file)) !== false) {
            // 空白行を無視
            if (count($line) !== 6) {
                continue;
            }

            $transactionDateTime = date('Y年m月d日 H時i分', $line[0]);
            $shopName = $line[1];
            $transactionAmount = (float) $line[2];
            $transactionType = $line[3];
            $transactionContent = $line[4];
            $paymentID = $line[5];

            // 取引情報を配列に格納
            $transactions[] = array(
                'date' => $transactionDateTime,
                'shop' => $shopName,
                'amount' => $transactionAmount,
                'type' => $transactionType,
                'content' => $transactionContent,
                'id' => $paymentID,
            );
        }

        // 取引日時でソート（新しい順）
        usort($transactions, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        // テーブルのヘッダーを出力
        echo '<div class="history">';
        // ソートされた取引情報を出力（最大3件まで）
        $count = 0;
        foreach ($transactions as $transaction) {
            if ($count >= 3) {
                break;
            }
            echo '<div class="card"><div class="card-content"><div class="columns"><div class="column is-11">';
            echo '<p class="subtitle m-0">';
            if ($transaction['type'] === '?') {
                echo '<span class="has-text-success">精算</span>';
                $color = "hsl(141, 71%, 48%)";
            } elseif ($transaction['type'] === '+') {
                echo '<span class="has-text-link">チャージ</span>';
                $color = "hsl(217, 71%, 53%)";
            } else {
                echo '<span class="has-text-danger">支払い</span>';
                $color = "hsl(348, 100%, 61%)";
            }
            echo " " . $transaction['shop'] . '</p>';
            echo '<p style="text-align: right;">' . $transaction['date'] . '</p>';
            echo '<p class="title mb-0">' . $transaction['amount'] . '円</p>';
            echo '<p>' . $transaction['content'] . '</p>';
            echo '</div><br><div class="column is-1 m-0" style="border:' . $color . ' 2px solid;"><a href="detail.php?id=' . $transaction['id'] . '"><div style="display:flex;justify-content:right;align-items:center;"><div class="has-text-right has-text-black"><i class="fas fa-chevron-right fa-2x has-text-black"></i><p class="title has-text-black">詳細</p></div></div></a></div></div></div></div><br>';
            $count++;
        }

        fclose($file);
        echo '</div>';
    } else {
        echo '履歴の読み込みに失敗しました。';
    }
}

function ie_read3($rid)
{
    // CSVファイルのパス
    $csvFile = "./history/$rid.csv";

    // 既存の残高
    $currentBalance = 0;

    if (!file_exists($csvFile)) {
        // ファイルを作成
        file_put_contents($csvFile, '');
    }

    // CSVファイルを読み込みモードで開く
    $file = fopen($csvFile, 'r');

    if ($file) {
        // CSVデータを格納する配列を初期化
        $transactions = array();

        while (($line = fgetcsv($file)) !== false) {
            // 空白行を無視
            if (count($line) !== 6) {
                continue;
            }

            $transactionDateTime = $line[0];
            $shopName = $line[1];
            $transactionAmount = (float) $line[2];
            $transactionType = $line[3];
            $transactionContent = $line[4];
            $paymentID = $line[5];

            // 取引情報を配列に格納
            $transactions[] = array(
                'date' => $transactionDateTime,
                'shop' => $shopName,
                'amount' => $transactionAmount,
                'type' => $transactionType,
                'content' => $transactionContent,
                'id' => $paymentID,
            );
        }

        // 取引日時でソート（新しい順）
        usort($transactions, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        // 取引履歴の表示
        echo '<div class="history">';

        // 初期化
        $currentMonth = null;

        foreach ($transactions as $transaction) {
            // 取引の日付から年月を取得
            $transactionMonth = date('Y年m月d日 H時i分', $transaction['date']);

            // 新しい月が始まったら見出しを追加
            if ($transactionMonth !== $currentMonth) {
                if ($currentMonth !== null) {
                    echo '</div>'; // 前月の取引終了
                }
                echo '<p class="title">' . $transactionMonth . '</p>'; // 新しい月の見出し
                echo '<div class="month-transactions">'; // 月ごとの取引をグループ化するためのディビジョン
                $currentMonth = $transactionMonth; // 現在の月を更新
            }

            // 取引情報の表示
            echo '<div class="card"><div class="card-content"><div class="columns"><div class="column is-11">';
            echo '<p class="subtitle m-0">';
            if ($transaction['type'] === '?') {
                echo '<span class="has-text-success">精算</span>';
                $color = "hsl(141, 71%, 48%)";
            } elseif ($transaction['type'] === '+') {
                echo '<span class="has-text-link">チャージ</span>';
                $color = "hsl(217, 71%, 53%)";
            } else {
                echo '<span class="has-text-danger">支払い</span>';
                $color = "hsl(348, 100%, 61%)";
            }
            echo " " . $transaction['shop'] . '</p>';
            echo '<p style="text-align: right;">' . date('Y年m月d日 H時i分', $transaction['date']) . '</p>';
            echo '<p class="title mb-0">' . $transaction['amount'] . '円</p>';
            echo '<p>' . $transaction['content'] . '</p>';
            echo '</div><br><div class="column is-1 m-0" style="border:' . $color . ' 2px solid;"><a href="detail.php?id=' . $transaction['id'] . '"><div style="display:flex;justify-content:right;align-items:center;"><div class="has-text-right has-text-black"><i class="fas fa-chevron-right fa-2x has-text-black"></i><p class="title has-text-black">詳細</p></div></div></a></div></div></div></div><br>';
        }

        // 最後の月の取引終了
        echo '</div>';

        fclose($file);
        echo '</div>';
    } else {
        echo '履歴の読み込みに失敗しました。';
    }
}



// 会員IDから会員名を取得する関数
function ie_name($id)
{
    $filePath = "data/$id.txt";

    if (file_exists($filePath)) {
        $file = fopen($filePath, "r");

        // ファイルを1行ずつ読み込み、2行目のデータを取得
        $line1 = fgets($file); // 1行目を読み飛ばす
        $line2 = fgets($file); // 2行目を取得

        fclose($file);

        // 会員名を返す（2行目のデータ）
        return trim($line2); // ホワイトスペースを取り除く
    } else {
        echo "会員データが存在しません。";
    }
}

function wallet($wal_id, $wal_shop, $wal_balance, $wal_type, $wal_content)
{
    // ファイルを読み込む
    $filePath = "./balance/$wal_id.txt";
    $balanceText = file_get_contents($filePath);

    // テキストから残高を取得
    $balance = intval($balanceText);

    // CSVファイルを読み込む
    if (($handle = fopen('data/user-list.csv', 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            // 1列目のユーザーキーと完全一致した場合
            if ($data[0] === $wal_id) {
                // 2列目のユーザーID
                $user = $data[1];
                break; // 必要であれば、一致したらループを終了する
            }
        }
        fclose($handle);
    } else {
        echo "ユーザーデータが破損しています。初期化してください。";
        exit;
    }

    if (!isset($user)) {
        echo "ユーザーデータが破損しています。初期化してください。";
        exit;
    }
    $wal_name = ie_name($wal_id);

    if ($wal_type === "+") {
        $syubetu = "チャージ";
        $newBalance = $balance + $wal_balance;
        file_put_contents($filePath, strval($newBalance));
        plus($balance, $wal_balance, $newBalance, $wal_id, true);
        $pay = true;
    } elseif ($wal_type == "-") {
        $syubetu = "支払い";
        // 残高が指定金額以上か確認
        if ($balance >= $wal_balance) {
            // 引く処理
            $newBalance = $balance - $wal_balance;

            // 新しい残高をファイルに書き込む
            file_put_contents($filePath, strval($newBalance));

            pay($balance, $wal_balance, $newBalance, $wal_id, true);

            $pay = true;
        } else {
            $fusoku = $wal_balance - $balance;
            pay($balance, $wal_balance, $fusoku, $wal_id, false);
            echo "<br><strong style='color: red;'><u>決済はまだ完了していません。</u></strong>";
            $pay = false;
        }
    } elseif ($wal_type == "?") {
        $syubetu = "精算";
        $newBalance = $wal_balance - $balance;
        seisan($balance, $wal_balance, $newBalance, $wal_id, true);
        file_put_contents($filePath, 0);
        $pay = true;
    } else {
        echo "不正なアクセスです。";
        $pay = false;
        exit;
    }

    if ($pay) {
        ie_write($wal_id, $wal_shop, $wal_balance, $wal_type, $wal_content);
    }
}

function plus($oldBalance, $AddBalance, $newBalance, $user, $ok)
{
    $old = number_format($oldBalance);
    $add = number_format($AddBalance);
    $new = number_format($newBalance);

    if ($ok) {
        $html = <<< "EOM"
    <div class="card mb-6">
        <div class="card-content has-text-centered">
            <div style="width: 100%;height: 12vh;" class="has-background-link mb-6 container">
                <div class="has-text-centered m-6">
                    <p class="title has-text-white" style="font-weight: bold;">チャージ</p>
                    <p class="subtitle has-text-white" style="font-weight:bold;">成功</p>
                </div>
            </div>
            <p class="title is-1 m-6">$add 円</p>
            <p class="subtitle">チャージ前残高 $old 円</p>
            <p class="subtitle mb-6">チャージ後残高 $new 円</p>
            <hr>
            <small>
                <p>会員ID: $user</p>
            </small>
        </div>
    </div>
    <p class="has-text-centered"><a class="button is-primary" href="shop.php">トップへ</a></p>
    EOM;
    } else {
        $html = <<< "EOM"
        <div class="card mb-6">
            <div class="card-content has-text-centered">
                <div style="width: 100%;height: 12vh;" class="has-background-primary mb-6 container">
                    <div class="has-text-centered m-6">
                        <p class="title has-text-white" style="font-weight: bold;">チャージ</p>
                        <p class="subtitle has-text-white" style="font-weight:bold;">失敗</p>
                    </div>
                </div>
                <p class="title is-1 m-6">$add 円</p>
                <hr>
                <small>
                    <p>会員ID: $user</p>
                </small>
            </div>
        </div>
        <p class="has-text-centered"><a class="button is-primary" href="shop.php" autofocus>トップへ</a></p>
        EOM;
    }
    echo $html;
}

function pay($oldBalance, $AddBalance, $newBalance, $user, $ok)
{
    $old = number_format($oldBalance);
    $add = number_format($AddBalance);
    $new = number_format($newBalance);

    if ($ok) {
        $html = <<< "EOM"
    <div class="card mb-6">
        <div class="card-content has-text-centered">
            <div style="width: 100%;height: 12vh;" class="has-background-danger mb-6 container">
                <div class="has-text-centered m-6">
                    <p class="title has-text-white" style="font-weight: bold;">支払い</p>
                    <p class="subtitle has-text-white" style="font-weight:bold;">成功</p>
                </div>
            </div>
            <p class="title is-1 m-6">$add 円</p>
            <p class="subtitle">支払い前残高 $old 円</p>
            <p class="subtitle mb-6">支払い後残高 $new 円</p>
            <hr>
            <small>
                <p>会員ID: $user</p>
            </small>
        </div>
    </div>
    <p class="has-text-centered"><a class="button is-primary" href="shop.php">トップへ</a></p>
    EOM;
    } else {
        $html = <<< "EOM"
        <div class="card mb-6">
            <div class="card-content has-text-centered">
                <div style="width: 100%;height: 12vh;" class="has-background-danger mb-6 container">
                    <div class="has-text-centered m-6">
                        <p class="title has-text-white" style="font-weight: bold;">支払い</p>
                        <p class="subtitle has-text-white" style="font-weight:bold;">失敗</p>
                    </div>
                </div>
                <p class="title is-1 m-6">$add 円</p>
                <p class="subtitle">現在の残高 $old 円</p>
                <p class="subtitle mb-6">不足分残高 $new 円</p>
                <hr>
                <small>
                    <p>会員ID: $user</p>
                </small>
            </div>
        </div>
        <p class="has-text-centered"><a class="button is-primary" href="shop.php" autofocus>トップへ</a></p>
        EOM;
    }
    echo $html;
}

function seisan($oldBalance, $AddBalance, $newBalance, $user, $ok)
{
    $old = number_format($oldBalance);
    $add = number_format($AddBalance);
    $new = number_format($newBalance);

    if ($ok) {
        $html = <<< "EOM"
    <div class="card mb-6">
        <div class="card-content has-text-centered">
            <div style="width: 100%;height: 12vh;" class="has-background-success mb-6 container">
                <div class="has-text-centered m-6">
                    <p class="title has-text-white" style="font-weight: bold;">精算</p>
                    <p class="subtitle has-text-white" style="font-weight:bold;">成功</p>
                </div>
            </div>
            <p class="title is-1 m-6">$add 円</p>
            <p class="subtitle">支払い前残高 $old 円</p>
            <p class="subtitle mb-6">現金払い額 $new 円</p>
            <p class="subtitle mb-6">支払い後残高 0 円</p>
            <hr>
            <small>
                <p>会員ID: $user</p>
            </small>
        </div>
    </div>
    <p class="has-text-centered"><a class="button is-primary" href="shop.php">トップへ</a></p>
    EOM;
    } else {
        $html = <<< "EOM"
        <div class="card mb-6">
            <div class="card-content has-text-centered">
                <div style="width: 100%;height: 12vh;" class="has-background-success mb-6 container">
                    <div class="has-text-centered m-6">
                        <p class="title has-text-white" style="font-weight: bold;">精算</p>
                        <p class="subtitle has-text-white" style="font-weight:bold;">失敗</p>
                    </div>
                </div>
                <p class="title is-1 m-6">$add 円</p>
                <hr>
                <small>
                    <p>会員ID: $user</p>
                </small>
            </div>
        </div>
        <p class="has-text-centered"><a class="button is-primary" href="shop.php" autofocus>トップへ</a></p>
        EOM;
    }
    echo $html;
}

function usage_amount($rid){
        // CSVファイルのパス
        $csvFile = "./history/$rid.csv";
        if (!file_exists($csvFile)) {
            // ファイルを作成
            file_put_contents($csvFile, '');
        }   
        // CSVファイルを読み込みモードで開く
        $file = fopen($csvFile, 'r');
        if ($file) {
            // CSVデータを格納する配列を初期化
            $transactions = array();
            while (($line = fgetcsv($file)) !== false) {
                // 空白行を無視
                if (count($line) !== 6) {
                    continue;
                }
                $transactionDateTime = $line[0];
                $transactionAmount = (float) $line[2];
                $transactionType = $line[3];
                // 取引情報を配列に格納
                $transactions[] = array(
                    'date' => $transactionDateTime,
                    'amount' => $transactionAmount,
                    'type' => $transactionType,
                );
            }
            // 取引日時でソート（新しい順）
            usort($transactions, function ($a, $b) {
                return $b['date'] <=> $a['date'];
            });
            // 初期化
            $currentMonth = null;
            $amount = 0;
    
            foreach ($transactions as $transaction) {
                // 取引の日付から年月を取得
                $transactionMonth = date('Y年m月', $transaction['date']);
    
                // 新しい月が始まったら見出しを追加
                if ($transactionMonth !== $currentMonth) {
                    if ($currentMonth !== null) {
                        break;
                    }
                    $currentMonth = $transactionMonth; // 現在の月を更新
                }

                if($currentMonth !== date('Y年m月')){
                    return 0;
                }

                if($transaction['type'] === "-" or $transaction['type'] === "?"){
                    $amount = $amount + intval($transaction['amount']);
                }
            }
            fclose($file);

            return $amount;
        } else {
            echo '利用額の読み込みに失敗';
        }
}

function charge_amount($rid){
    // CSVファイルのパス
    $csvFile = "./history/$rid.csv";
    if (!file_exists($csvFile)) {
        // ファイルを作成
        file_put_contents($csvFile, '');
    }   
    // CSVファイルを読み込みモードで開く
    $file = fopen($csvFile, 'r');
    if ($file) {
        // CSVデータを格納する配列を初期化
        $transactions = array();
        while (($line = fgetcsv($file)) !== false) {
            // 空白行を無視
            if (count($line) !== 6) {
                continue;
            }
            $transactionDateTime = $line[0];
            $transactionAmount = (float) $line[2];
            $transactionType = $line[3];
            // 取引情報を配列に格納
            $transactions[] = array(
                'date' => $transactionDateTime,
                'amount' => $transactionAmount,
                'type' => $transactionType,
            );
        }
        // 取引日時でソート（新しい順）
        usort($transactions, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });
        // 初期化
        $currentMonth = null;
        $charge = 0;

        foreach ($transactions as $transaction) {
            // 取引の日付から年月を取得
            $transactionMonth = date('Y年m月', $transaction['date']);

            // 新しい月が始まったら見出しを追加
            if ($transactionMonth !== $currentMonth) {
                if ($currentMonth !== null) {
                    break;
                }
                $currentMonth = $transactionMonth; // 現在の月を更新
            }

            if($currentMonth !== date('Y年m月')){
                return 0;
            }

            if($transaction['type'] === "+"){
                $charge = $charge + intval($transaction['amount']);
            }
        }
        fclose($file);

        return $charge;
    } else {
        echo 'チャージ額の読み込みに失敗';
    }
}