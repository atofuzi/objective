<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　(バトル）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// Ajax処理
//================================

// postがあり、ユーザーIDがあり、ログインしている場合
if(!empty($_POST['battleCommand'])){
    debug('POST送信があります。');
    debug('選んだバトルコマンドは：'.$_POST['battleCommand']);
    $select_command = $_POST['battleCommand'];
    
        switch($select_command){
            case Command::ATTACK :
                //$_SESSION['player']->attack();

                //ダメージ
                $defense = mt_rand($_SESSION['boss_defense']*0.9,$_SESSION['boss_defense']*1.1);
                $damage = $_SESSION['player_power'] * 3 - $defense;
                    if(!mt_rand(0,5)){
                        $damage = $damage *1.2; 
                    }
                $_SESSION['boss_hp'] = $_SESSION['boss_hp'] -$damage;
                $_SESSION['damage'] = $damage;
                break;

            case Magic::HEEL :
                $_SESSION['player']->magic();
                $_SESSION['player_hp'] = $_SESSION['player_hp']+round($_SESSION['player']->getHp() * 0.3);
                    if($_SESSION['player_hp'] > $_SESSION['player']->getHp()){
                        $_SESSION['player_hp'] = $_SESSION['player']->getHp();
                    }
                debug('プレイヤーのHP：'.$SESSION['player_hp']);
                break;

            case Magic::ATTACK_BOOST :
                $_SESSION['player_power'] = $_SESSION['player']->getPower() * 1.5;
                break;

            case Magic::HOLY :
            
                break;
        }
    Battle::Count();

}

debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>
    <div class="hp">HP
        <p class="hp-gage" style="width: <?php echo getGage($_SESSION['player_hp']);?>px;" ></p>
        <p class="hp-point"><?php echo $_SESSION['player_hp']."/".$_SESSION['player']->getHp();?></p>
    </div>
    <div class="mp">MP
        <p class="mp-gage" style="width: <?php echo getGage($_SESSION['player_mp']);?>px;" ></p>
        <p class="mp-point"><?php echo $_SESSION['player_mp']."/".$_SESSION['player']->getMp();?></p>
    </div>