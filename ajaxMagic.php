<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　(トレーニング：魔法）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// Ajax処理
//================================

// postがあり、ユーザーIDがあり、ログインしている場合
if(!empty($_POST['select_magic'])){
    debug('POST送信があります。');
    debug('選んだ魔法は：'.$_POST['select_magic']);
    $select_magic = $_POST['select_magic'];
    switch($select_magic){
        case Magic::HEEL :
            $_SESSION['player']->setMagic(Magic::HEEL);
            break;
        case Magic::ATTACK_BOOST :
            $_SESSION['player']->setMagic(Magic::ATTACK_BOOST);
            break;
        case Magic::HOLY :
            $_SESSION['player']->setMagic(Magic::HOLY);
        break;
        }
}
debug(print_r($_SESSION['player']->getMagic(),true));
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

<div class="magic-screen">
    <div class="status-panel">
        <div class="block">
            <p>レア度：
            <?php
                for($i=0; $i<getRarity($_SESSION['player']); $i++){
                    echo "★";
                }
            ?>
            </p>
            <p><?php echo $_SESSION['player']->getName(); ?></p>
            <img src="<?php echo $_SESSION['player']->getImg(); ?>">
        </div>
        <div class="block">
            <table>
                <tr>
                    <td>回復魔法</td>
                    <td class="magic" aria-hidden="true" data-magic="<?php echo Magic::HEEL; ?>">ヒール</td>
                </tr>
                <tr>
                    <td>補助魔法</td>
                    <td class="magic" aria-hidden="true" data-magic="<?php echo Magic::ATTACK_BOOST; ?>">アタックブースト</td>
                </tr>
                <tr>
                    <td>攻撃魔法</td>
                    <td class="magic" aria-hidden="true" data-magic="<?php echo Magic::HOLY; ?>">ホーリー</td>
                </tr>
            </table>
        </div>
        <div class="popup-2">魔法を覚えました!!!</div>
    </div>
   
</div>