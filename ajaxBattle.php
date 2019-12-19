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
if(!empty($_POST['battleCommand'])){

    $select_command = $_POST['battleCommand'];
    $player = $_SESSION['battle_player'];
    $boss =  $_SESSION['boss'];
    $damage = "";
    $p_damage = "";

    if($select_command != "boss"){
        BattleTern::Count();
    }

    debug('バトルターン：'.$_SESSION['battle_tern'].'回目');
    debug('選んだバトルコマンドは：'.$select_command);
    


        switch($select_command){
            case Command::ATTACK :
                    debug('プレイヤーの攻撃');

                    $player->attack($boss);
                    $damage = $boss->getDamage();
                    $_SESSION['boss_hp'] = $boss->getHp();

                    debug($boss->getName().'へ'.$damage.'の物理ダメージ');
            
                    debug('ボスの残りHPは'.$boss->getHp());
                    break;

            case MagicSkill::HEEL :

                    HEEL::use($player,$_SESSION['player']);
                    break;

            case MagicSkill::ATTACK_BOOST :

                    ATTACK_BOOST::use($player,$_SESSION['player']);
                    break;

            case MagicSkill::HOLY :
                    debug('プレイヤーの魔法撃');

                    HOLY::use($player,$boss);

                    $damage = $boss->getDamage();
                    
                    debug($boss->getName().'へ'.$damage.'の物理ダメージ');
                    $_SESSION['boss_hp'] = $boss->getHp();

                    debug('ボスの残りHPは'.$boss->getHp());

                    break;

            case "boss" :
                    debug('ボスの攻撃');

                    $boss->attack($player);
                    $p_damage = -1*$player->getDamage();
                    $_SESSION['player_hp'] = $player->getHp();

                    debug($player->getName().'へ'.$p_damage.'の物理ダメージ');
                    debug('プレイヤーの残りHPは'.$_SESSION['player_hp']);
        }

        if($_SESSION['boss_hp']  < 0){
            $_SESSION['quest']->setQuestClear();
            debug('クエストステータス：'.$_SESSION['quest']->getQuestClear());
        }
}

debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>

    <div class="ajax-boss-hp">
        <p class="boss-hp-gage" style="width: <?php echo getBossGage($boss->getHp()); ?>px;"  ></p>
    </div>

    <span class="boss-damage"><span><?php echo $damage; ?></span></span>
    <span class="player-damage"><span><?php echo $p_damage; ?></span></span>

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

