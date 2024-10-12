//
//  Mobile Nav JS
//  Created by Tomoya Ogawa
//  v1.0.0 - 2024/02/08
//
//  いえPay専用モジュール
//  スマホの場合ナビゲーションバーを表示します。
//  このスクリプトでは動的にナビゲーションバーの高さを計算し、自動的にhtmlにpadding-bottomを付与することで最下部までのスクロールを可能にしています。

document.addEventListener("DOMContentLoaded", function () {
    // ナビゲーションバーの要素を取得
    const navbar = document.getElementById('mbNav');

    // ナビゲーションバーの高さを取得
    const navbarHeight = navbar.clientHeight;

    // コンテンツの上部に必要な余白を設定
    document.body.style.paddingBottom = navbarHeight + 'px';
})