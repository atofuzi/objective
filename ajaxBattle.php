<?php
//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　(バトル）');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

//================================
// Ajax処理
//================================

// postあり
if(!empty($_POST)){
    debug('POST送信があります。');
    debug('選んだバトルコマンドは：'.$_POST['battleCommand']);
    $select_command = $_POST['battleCommand'];
    $player = $_SESSION['battle_player'];
    $boss =  $_SESSION['boss'];
    $damage = "";

        switch($select_command){
            case Command::ATTACK :

                    $player->attack($boss);
                    $damage = $boss->getDamage();
                    $_SESSION['boss_hp'] = $boss->getHp();
                    break;

            case MagicSkill::HEEL :

                    HEEL::use($player,$_SESSION['player']);
                    break;

            case MagicSkill::ATTACK_BOOST :

                    ATTACK_BOOST::use($player,$_SESSION['player']);
                    break;

            case MagicSkill::HOLY :
            
                    HOLY::use($player,$boss);
                    $damage = $boss->getDamage();
                    break;
        }

    BattleTern::Count();

}

debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>
    <div class="ajax-boss-hp">
        <p class="boss-hp-gage" style="width: <?php echo getBossGage($boss->getHp()); ?>px;"  ></p>
    </div>

        <span class="boss-damage"><?php echo $damage; ?></span>


    <div class="hp-mp">
        <div class="hp">HP
            <p class="hp-gage" style="width: <?php echo getGage($player->getHp());?>px;" ></p>
            <p class="hp-point"><?php echo $player->getHp()."/".$_SESSION['player']->getHp();?></p>
        </div>
        <div class="mp">MP
            <p class="mp-gage" style="width: <?php echo getGage($player->getMp());?>px;" ></p>
            <p class="mp-point"><?php echo $player->getMp()."/".$_SESSION['player']->getMp();?></p>
        </div>
    </div>

