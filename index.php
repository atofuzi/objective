<?php

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「ゲーム開始');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');



if(!empty($_POST)){

    debug('ポスト送信あり');
    $startFlg = (!empty($_POST['start']))? true : false;
    $restartFlg  = (!empty($_POST['restart']))? true : false;
    $createPlayer  = (!empty($_POST['createPlayer']))? true : false;
    $homeFlg  = (!empty($_POST['home']))? true : false;
    $trainingFlg  = (!empty($_POST['training']))? true : false;

    debug(print_r($_POST,true));
    debug('スタートフラグ：'.$startFlg);
    debug('リスタートフラグ：'.$startFlg);
    debug('プレイヤー生成フラグ：'.$createPlayer);
    debug('ホームフラグ：'.$createPlayer);
    debug('トレーニングフラグ：'.$createPlayer);

    if($startFlg){
        debug('ゲームスタート');
        $_SESSION['start'] = GAME_START;
    }elseif($createPlayer){
        debug('プレイヤーを生成します');
        createPlayer();
    }elseif($homeFlg){

    }elseif($trainingFlg){

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

        //修行：瞑想
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

            },
            // 通信エラー時に呼び出されるコールバック
            error: function () {

                alert("Ajax通信エラー");


            }
        });

        }


        //修行：魔法を覚える
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
            }
            else if($(e.target).is($status_show)){
                $popup.fadeIn();
                popFlg = true;
            }else if($(e.target).is($('.magic'))){
                console.log('魔法クリック');
                $('.popup-2').fadeIn(1000);
                setTimeout(function(){ 

                    $('.popup-2').fadeOut(1000);
                    var magic = $(e.target).data('magic');
                    AjaxMagic(magic);

                 }, 2500);
            }
        });

    </script>

</html>