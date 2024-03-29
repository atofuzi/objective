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
    
    $hp = mt_rand(80,120);
    $power = mt_rand(40,60);
    $defense =  mt_rand(40,60);

    $status['HP'] = $status['HP'] + $hp;
    $status['力'] = $status['力'] + $power;
    $status['物理防御'] = $status['物理防御'] + $defense;

    setStatus($_SESSION['player'],$status);
}
debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>

<div class="run-screen">
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
                    <td><?php echo $_SESSION['player']->getHp();?> (<span style="color:red;">↑</span><?php echo $hp.")"; ?></td>
                    <td>MP</td>
                    <td><?php echo $_SESSION['player']->getMp(); ?></td>
                </tr>
                <tr>
                    <td>力</td>
                    <td><?php echo $_SESSION['player']->getPower();?> (<span style="color:red;">↑</span><?php echo $power.")"; ?></td>
                    <td>魔力</td>
                    <td><?php echo $_SESSION['player']->getMagicPower(); ?></td>
                </tr>
                <tr>
                    <td>物理防御</td>
                    <td><?php echo $_SESSION['player']->getDefense();?> (<span style="color:red;">↑</span><?php echo $defense.")"; ?></td>
                    <td>魔法防御</td>
                    <td><?php echo $_SESSION['player']->getMagicDefense(); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
           