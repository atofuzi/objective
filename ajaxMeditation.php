<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　(トレーニング：瞑想）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// Ajax処理
//================================

// postがあり、ユーザーIDがあり、ログインしている場合
if(!empty($_POST['training_meditation'])){
    debug('POST送信があります。');
    $status = getStatus($_SESSION['player']);

    $mp = mt_rand(80,120);
    $magicPower = mt_rand(40,60);
    $magicDefense =  mt_rand(40,60);

    $status['MP'] = $status['MP'] + $mp;
    $status['魔力'] = $status['魔力'] + $magicPower;
    $status['魔法防御'] = $status['魔法防御'] + $magicDefense;

    setStatus($_SESSION['player'],$status);
}
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<div class="meditation-screen">
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
                    <td>HP</td>
                    <td><?php echo $_SESSION['player']->getHp();?></td>
                    <td>MP</td>
                    <td><?php echo $_SESSION['player']->getMp();?> (<span style="color:red;">↑</span><?php echo $mp.")"; ?></td>
                </tr>
                <tr>
                    <td>力</td>
                    <td><?php echo $_SESSION['player']->getPower();?></td>
                    <td>魔力</td>
                    <td><?php echo $_SESSION['player']->getMagicPower(); ?> (<span style="color:red;">↑</span><?php echo $magicPower.")"; ?> </td>
                </tr>
                <tr>
                    <td>物理防御</td>
                    <td><?php echo $_SESSION['player']->getDefense();?></td>
                    <td>魔法防御</td>
                    <td><?php echo $_SESSION['player']->getMagicDefense(); ?> (<span style="color:red;">↑</span><?php echo $magicDefense.")"; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
           