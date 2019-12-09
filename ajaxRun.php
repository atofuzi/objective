<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　(トレーニング：走る）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// Ajax処理
//================================

// postがあり、ユーザーIDがあり、ログインしている場合
if(!empty($_POST['training_run'])){
    debug('POST送信があります。');
    $status = getStatus($_SESSION['player']);
    $status['HP'] = $status['HP'] + 100;
    $status['力'] = $status['力'] + 50;
    $status['物理防御'] = $status['物理防御'] + 50;
    setStatus($_SESSION['player'],$status);
}
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>