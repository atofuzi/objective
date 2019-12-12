<?php

//共通変数・関数ファイルを読込み
require('function.php');


debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「ゲーム開始');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');

debug('スタートフラグ：'.$startFlg);
debug('リスタートフラグ：'.$restartFlg);
debug('プレイヤー生成フラグ：'.$createPlayer);
debug('ホームフラグ：'.$homeFlg);
debug('トレーニングフラグ：'.$trainingFlg);
debug('クエストフラグ：'.$questFlg);
debug('バトルフラグ：'.$questFlg);
debug('セッション：'.print_r($_SESSION,true));

if(!empty($_SESSION) && empty($_POST)){
    $_SESSION = array();
}

if(!empty($_POST)){

    debug('ポスト送信あり');
    $startFlg = (!empty($_POST['start']))? true : false;
    $restartFlg  = (!empty($_POST['restart']))? true : false;
    $createPlayer  = (!empty($_POST['createPlayer']))? true : false;
    $homeFlg  = (!empty($_POST['home']))? true : false;
    $trainingFlg  = (!empty($_POST['training']))? true : false;
    $questFlg  = (!empty($_POST['quest']))? true : false;
    $battleFlg  = (isset($_POST['quest_number']))? true : false;
    
    debug(print_r($_POST,true));
    debug('スタートフラグ：'.$startFlg);
    debug('リスタートフラグ：'.$restartFlg);
    debug('プレイヤー生成フラグ：'.$createPlayer);
    debug('ホームフラグ：'.$homeFlg);
    debug('トレーニングフラグ：'.$trainingFlg);
    debug('クエストフラグ：'.$questFlg);
    debug('バトルフラグ：'.$questFlg);

    if($startFlg){
        debug('ゲームスタート');
        $_SESSION['start'] = GAME_START;
    }elseif($createPlayer){
        debug('プレイヤーを生成します');
        createPlayer();
    }elseif($homeFlg){
        debug('ホームへ移動します');
    }elseif($trainingFlg){
        debug('トレーニングへ移動します');
    }elseif($questFlg){
        debug('クエスト選択画面へ移動します');
    }elseif($battleFlg){
        debug('バトル画面へ移動します');
        $number = $_POST['quest_number'];
        $boss = $quest[$number]->getQuestMonster();
    }else{
        debug('ゲームをリセットしました');
        $_SESSION = array();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="utf-8">
        <title>ゲーム</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    </head>

    <body>
        <div class="game-screen" style="<?php if($homeFlg) echo "background-image: url(img/home.jpg); background-size:contain; position: relative;" ?>">
            <?php if(empty($_SESSION)){ ?>
                <h1 id="title">モンスター討伐クエスト</h1>
                <img src="img/building_europe_kojou.png">
                <div class="menu">
                    <form method="post">
                        <input type="submit" name="start" value="クエスト開始">
                    </form>
                </div>
            <?php }elseif($startFlg){ ?>
                 <img src="img/1044176.jpg">
                    <div class="menu">
                        <form method="post">
                            <input type="submit" name="createPlayer" value="プレイヤーを召喚">
                        </form>
                    </div>
            <?php }elseif($createPlayer){ ?>
                <div class="player-panel">
                    <p>レア度：
                    <?php
                        for($i=0; $i<getRarity($_SESSION['player']); $i++){
                            echo "★";
                        }
                    ?>
                    </p>
                    <p><?php echo $_SESSION['player']->getName(); ?></p>
                    <img src="<?php echo $_SESSION['player']->getImg(); ?>">
                    <table>
                        <tr>
                            <td>HP</td>
                            <td><?php echo $_SESSION['player']->getHp(); ?></td>
                            <td>MP</td>
                            <td><?php echo $_SESSION['player']->getMp(); ?></td>
                        </tr>
                        <tr>
                            <td>力</td>
                            <td><?php echo $_SESSION['player']->getPower(); ?></td>
                            <td>魔力</td>
                            <td><?php echo $_SESSION['player']->getMagicPower(); ?></td>
                        </tr>
                        <tr>
                            <td>物理防御</td>
                            <td><?php echo $_SESSION['player']->getDefense(); ?></td>
                            <td>魔法防御</td>
                            <td><?php echo $_SESSION['player']->getMagicDefense(); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="menu">
                    <form method="post">
                        <input type="submit" name="home" value="町へ行く">
                    </form>
                </div>
            <?php }elseif($homeFlg){ ?>
                <div class="popup">
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
                                    <td><?php echo $_SESSION['player']->getHp(); ?></td>
                                    <td>MP</td>
                                    <td><?php echo $_SESSION['player']->getMp(); ?></td>
                                </tr>
                                <tr>
                                    <td>力</td>
                                    <td><?php echo $_SESSION['player']->getPower(); ?></td>
                                    <td>魔力</td>
                                    <td><?php echo $_SESSION['player']->getMagicPower(); ?></td>
                                </tr>
                                <tr>
                                    <td>物理防御</td>
                                    <td><?php echo $_SESSION['player']->getDefense(); ?></td>
                                    <td>魔法防御</td>
                                    <td><?php echo $_SESSION['player']->getMagicDefense(); ?></td>
                                </tr>
                            </table>
                            <div class="sub-block">
                                <h2>魔法スキル</h2>
                                    <?php 
                                        $magic = $_SESSION['player']->getMagic();
                                        if(empty($magic)){
                                            echo "<span>・無し</span>";
                                        }else{
                                            foreach($magic as $key => $value){
                                    ?>
                                            <span><i class="fas fa-caret-right"></i><?php echo $value; ?></span><br>
                                    <?php
                                            }
                                        }
                                     ?>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="menu" style="position: absolute; bottom: 0; left: 40px;">
                    <form method="post">
                        <div class="js-status-show">ステータス</div>
                        <input type="submit" name="training" value="修行">
                        <input type="submit" name="quest" value="クエスト">
                        <input type="submit" name="restart" value="リセット">
                    </form>
                </div>
            <?php }elseif($trainingFlg){ ?>
                <div id="ajaxreload">
                    <div class="training-screen">
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
                                        <td><?php echo $_SESSION['player']->getHp(); ?></td>
                                        <td>MP</td>
                                        <td><?php echo $_SESSION['player']->getMp(); ?></td>
                                    </tr>
                                    <tr>
                                        <td>力</td>
                                        <td><?php echo $_SESSION['player']->getPower(); ?></td>
                                        <td>魔力</td>
                                        <td><?php echo $_SESSION['player']->getMagicPower(); ?></td>
                                    </tr>
                                    <tr>
                                        <td>物理防御</td>
                                        <td><?php echo $_SESSION['player']->getDefense(); ?></td>
                                        <td>魔法防御</td>
                                        <td><?php echo $_SESSION['player']->getMagicDefense(); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
 
                <div class="menu">
                        <div class="js-click-run">走る</div>
                        <div class="js-click-meditation">瞑想する</div>
                        <div class="js-click-magic">魔法を覚える</div>
                    <form method="post">
                        <input type="submit" name="home" value="町へ戻る">
                    </form>
                </div>
            <?php }elseif($questFlg){ ?>
                <div class="quest-screen">
                    <form method="post">
                    <h2>クエスト一覧</h2>
                    <?php foreach($quest as $key => $value){ ?>
                    <button class="quest-panel" name="quest_number" value="<?php echo $key;?>">
                        <div class="quest-img">
                            <img src="<?php echo $quest[$key]->getQuestImg();?>">
                        </div>
                        <div class="quest-detail">
                            <div class="quest-title">
                                <span class="quest-name">QUEST：<?php echo $quest[$key]->getQuestName();?></span>
                                <span class="quest-level">レベル：<?php echo $quest[$key]->getQuestLevel();?></span>
                            </div>
                            <p class="boss-name">BOSS：<?php echo $quest[$key]->getMonsterName();?></p>
                            <p class="quest-status">clear</p>
                        </div>
                    </button>
                    <?php } ?>
                </div>
                <div class="menu">
                        <input type="submit" name="home" value="町へ戻る">
                </div>
            <?php }elseif($battleFlg){ ?>
                <div class="battle-screen">
                    <div class="boss-area">
                        <img src="<?php echo $boss->getImg(); ?>">
                        <span class="damage">ダメージ</span>
                    </div>
                    <div class="player-area">
                        <div class="player-info">
                            <div class="player-img">
                                <img src="<?php echo $_SESSION['player']->getImg();?>">
                            </div>
                            <div class="hp-mp">
                                <p class="hp">HP</p>
                                <p class="mp">MP</p>
                            </div>
                            <div class="command">
                            <p>こうげき</p>
                            <p>魔法</p>
                            <p>逃げる</p>
                        </div>
                        </div>
                        <div class="js-command-list">
                            <p>ヒール</p>
                            <p>アタックブースト</p>
                            <P>ホーリー</p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </body>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>

        var $status_show = $('.js-status-show'); 
        var $popup = $('.popup');
        var $status_panel = $('.status-panel');
        var $game_screen = $('.game-screen');
        var popFlg = false;

        

        
        //修行：走る
        function AjaxRun() {
        
        // jQueryのajaxメソッドを使用しajax通信
        $.ajax({
            type: "POST", // GETメソッドで通信

            url: "ajaxRun.php", // 取得先のURL

            data: { training_run : "run"},

            cache: false, // キャッシュしないで読み込み

            // 通信成功時に呼び出されるコールバック
            success: function (data) {

                $('#ajaxreload').html(data);

            },
            // 通信エラー時に呼び出されるコールバック
            error: function () {

                alert("Ajax通信エラー");


            }
        });

        }


         //修行：魔法を覚える
        function AjaxMagic(magic) {
        
        // jQueryのajaxメソッドを使用しajax通信
        $.ajax({
            type: "POST", // GETメソッドで通信

            url: "ajaxMagic.php", // 取得先のURL

            data: { select_magic : magic},

            cache: false, // キャッシュしないで読み込み

            // 通信成功時に呼び出されるコールバック
            success: function (data) {

                $('#ajaxreload').html(data);

                $('.popup-2').fadeIn(1000);
                setTimeout(function(){ 
                    $('.popup-2').fadeOut(1000);
                 }, 2500);
            },
            // 通信エラー時に呼び出されるコールバック
            error: function () {

                alert("Ajax通信エラー");


            }
        });

        }


        //修行：瞑想
        function AjaxMeditation() {
        
        // jQueryのajaxメソッドを使用しajax通信
        $.ajax({
            type: "POST", // GETメソッドで通信

            url: "ajaxMeditation.php", // 取得先のURL

            data: { training_meditation : "meditation"},

            cache: false, // キャッシュしないで読み込み

            // 通信成功時に呼び出されるコールバック
            success: function (data) {

                $('#ajaxreload').html(data);
            },
            // 通信エラー時に呼び出されるコールバック
            error: function () {

                alert("Ajax通信エラー");


            }
        });

        }



        //修行メニューを選択した場合のアクション
        $(document).on('click', function(e) {
            if($(e.target).is($('.js-click-run'))){
                AjaxRun();
            }else if($(e.target).is($('.js-click-meditation'))){
                AjaxMeditation();
            }else if($(e.target).is($('.js-click-magic'))){
                var magic = "";
                AjaxMagic(magic);
            }
        });

        //ステータスポップアップ機能
        $(document).on('click', function(e) {
            if( ( $(e.target).is($game_screen) === true || $(e.target).is($status_show) ) && popFlg === true){
                $popup.fadeOut();
                popFlg = false;
            }else if($(e.target).is($status_show)){
                $popup.fadeIn();
                popFlg = true;
            //修行・魔法でのクリック時の処理
            }else if($(e.target).is($('.magic'))){
                console.log('魔法クリック');
                var magic = $(e.target).data('magic');

                AjaxMagic(magic);
            }
        });

    </script>

</html>