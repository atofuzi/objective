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
debug('バトルフラグ：'.$battleFlg);

//セッションに値が入っているがポスト送信が空の場合、不正遷移の可能性もあるためリセット
if(!empty($_SESSION) && empty($_POST)){
    $_SESSION = array();
}

//ポスト送信がある場合
if(!empty($_POST)){

    debug('ポスト送信あり');

    //画面の判定用フラグ　遷移画面→true
    $startFlg = (!empty($_POST['start']))? true : false;
    $restartFlg  = (!empty($_POST['restart']))? true : false;
    $createPlayer  = (!empty($_POST['createPlayer']))? true : false;
    $homeFlg  = (!empty($_POST['home']))? true : false;
    $trainingFlg  = (!empty($_POST['training']))? true : false;
    $questFlg  = (!empty($_POST['quest']))? true : false;
    $battleFlg  = (isset($_POST['quest_number']))? true : false;
    

    if($startFlg){
        debug('ゲームスタート');
        $_SESSION['start'] = GAME_START;
    }elseif($createPlayer){
        debug('プレイヤーを生成します');
        //プレイヤー生成
        createPlayer();
    }elseif($homeFlg){
        debug('ホームへ移動します');
    }elseif($trainingFlg){
        debug('トレーニングへ移動します');
    }elseif($questFlg){
        debug('クエスト選択画面です');
    }elseif($battleFlg){
        debug('バトル画面です');
        debug('クエストナンバー：'.$_POST['quest_number']);

        $_SESSION['battle_tern'] = 0;

        if($_SESSION['battle_tern'] == 0){

            //クエストナンバーを取り出す
            $number = $_POST['quest_number'];

            //ボスオブジェクト生成
            $boss = new Boss($quest[$number]->getQuestMonster());

            //バトルプレイヤーオブジェクトを生成
            $player = new BattlePlayer($_SESSION['player']);

            //プレイヤーの魔法スキルセット
            $player_magic = $player->getMagic();

            //ボス・プレイヤーオブジェクトをセッションへ格納　※ajax通信でオブジェクトを送信する役割
            $_SESSION['boss'] = $boss;
            $_SESSION['battle_player']= $player; 

            //勝利・敗北を判断するためのHPを初期化
            $_SESSION['boss_hp'] = 0;
            $_SESSION['player_hp']= 0; 
            $_SESSION['quest'] = $quest[$number];

         
        }   
    }else{
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
            <!--  スタート画面  -->
            <?php }elseif($startFlg){ ?>
                 <img src="img/1044176.jpg">
                    <div class="menu">
                        <form method="post">
                            <input type="submit" name="createPlayer" value="プレイヤーを召喚">
                        </form>
                    </div>
            <!--  プレイヤー召喚画面  -->
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
            <!--  ホーム画面  -->
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
            <!--  修行画面  -->
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
            <!--  クエスト選択画面  -->
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
                            <p class="quest-status"><?php echo $quest[$key]->getQuestClear(); ?></p>
                        </div>
                    </button>
                    <?php } ?>
                </div>
                <div class="menu">
                        <input type="submit" name="home" value="町へ戻る">
                </div>
            <!--  バトル画面  -->
            <?php }elseif($battleFlg){ ?>
                <div class="battle-screen">
                    <div class="boss-area">
                        <div class="boss-hp">
                            <span><?php echo $boss->getName(); ?></span><br>
                            <span>HP</span>
                            <div id="ajax-boss-hp">
                                <p class="boss-hp-gage"></p>
                            </div>
                        </div>
                        <img class="boss-img" src="<?php echo $boss->getImg(); ?>">
                        <span id="boss-damage"><span></span></span>   
                    </div>
                    <div class="popup-area">
                        <div class="popup-3">ボスの攻撃！！！</div>
                        <div class="popup-lose" style="display:none;">
                            <form method="post">
                                <button name="quest_number" value="<?php echo $number ?>">リトライ？</button>
                                <input type="submit" name="home" value="町へ戻る">
                            </form> 
                        </div>
                        <div class="popup-win" style="display:none;">
                            <form method="post">
                                <p>勝利しました！</p>
                                <input type="submit" name="home" value="町へ戻る">
                            </form> 
                        </div>
                    </div>
                    <div class="player-area">
                        <span id="player-damage"><span></span></span>
                        <div class="player-info">
                            <div class="player-img">
                                <img src="<?php echo $_SESSION['player']->getImg();?>">
                            </div>
                            <div id="hp-mp">
                                <div class="hp">HP
                                    <p class="hp-gage"></p>
                                    <p class="hp-point"><?php echo $player->getHp()."/".$_SESSION['player']->getHp();?></p>
                                </div>
                                <div class="mp">MP
                                    <p class="mp-gage"></p>
                                    <p class="mp-point"><?php echo $player->getMp()."/".$_SESSION['player']->getMp();?></p>
                                </div>
                            </div>
                            <div class="command">
                            <p class="js-attack" aria-hidden="true" data-command="<?php echo Command::ATTACK; ?>">こうげき</p>
                            <p class="js-hover-magic">魔法</p>
                            <form method="post">
                                <input class="escape" type="submit" name="home" value="逃げる">
                            </form>
                        </div>
                        </div>
                        <div class="magic-list">
                        <?php foreach($player_magic as $key => $value){ ?>
                                <p class="js-magic" aria-hidden="true" data-command="<?php echo $value ?>"><?php echo $player_magic[$key]; ?></p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

    <!-- フッター -->
    <footer>

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

            //バトル画面の設定
            //HP・MPゲージの表示
            var $hp_gage = $('.hp-gage'),
                $mp_gage = $('.mp-gage'),
                $boss_hp_gage = $('.boss-hp-gage');

            //バトルの最初の画面
            window.onload=function() {
            <?php
            if($battleFlg){
            ?>
            var hp = <?php echo getGage($player->getHp()); ?>,
                mp = <?php echo getGage($player->getMp()); ?>,
                boss_hp = <?php echo getBossGage($boss->getHp()); ?>;

                $hp_gage.css("width",hp);
                $mp_gage.css("width",mp);
                $boss_hp_gage.css("width",boss_hp);
            <?php } ?>
            }
        

            //バトルでコマンドが押された場合の処理
            function AjaxBattle(command) {
            
                // jQueryのajaxメソッドを使用しajax通信
                $.ajax({
                    type: "POST", // GETメソッドで通信

                    url: "ajaxBattle.php", // 取得先のURL

                    data: { battleCommand : command },

                    cache: false, // キャッシュしないで読み込み
                
                    dataType: 'html',

                    // 通信成功時に呼び出されるコールバック
                    }).done(function(data){

                        var $html = $(data);
                        var $hp_mp = $($html.get().filter(x => x.className === "hp-mp"));
                        var $boss_hp = $($html.get().filter(x => x.className === "ajax-boss-hp"));
                        var $boss_damage = $($html.get().filter(x => x.className === "boss-damage"));
                        var $player_damage = $($html.get().filter(x => x.className === "player-damage"));
                        $('#hp-mp').html($hp_mp.html());
                        $('#ajax-boss-hp').html($boss_hp.html());
                        $('#boss-damage').html($boss_damage.html());
                        $('#player-damage').html($player_damage.html());
                        
                        if(command != "boss"){
                            AjaxGetBossHp(judgeWin,"boss");
                        }

                    // 通信エラー時に呼び出されるコールバック
                    }).fail(function(mdg){

                        alert("Ajax通信エラー");
                });
            }

            //ボスの残りHPを取得
            function AjaxGetBossHp(callback,target) {
                // jQueryのajaxメソッドを使用しajax通信
                $.ajax({
                    type: "POST", // POSTメソッドで通信

                    url: "ajaxGetBossHp.php", // 取得先のURL

                    data: { get_hp : target },

                    cache: false, // キャッシュしないで読み込み
                

                    // 通信成功時に呼び出されるコールバック
                    success: function (data) {

                        callback(data);

                    },
                    // 通信エラー時に呼び出されるコールバック
                    error: function () {

                        alert("Ajax通信エラー");

                    }
                });
            }

            //プレイヤーの残りHPを取得
            function AjaxGetPlayerHp(callback,target) {
                // jQueryのajaxメソッドを使用しajax通信
                $.ajax({
                    type: "POST", // POSTメソッドで通信

                    url: "ajaxGetPlayerHp.php", // 取得先のURL

                    data: { get_hp : target },

                    cache: false, // キャッシュしないで読み込み
                

                    // 通信成功時に呼び出されるコールバック
                    success: function (data) {
                        console.log(target+'の残りHP：'+data);
                        callback(data);
                    },
                    // 通信エラー時に呼び出されるコールバック
                    error: function () {

                        alert("Ajax通信エラー");

                    }
                });
            }

            //バトルで攻撃・魔法をクリックした場合の処理
            $(document).on('click', function(e) {
                if($(e.target).is($('.js-attack')) || $(e.target).is($('.js-magic')) ){
                    AjaxBattle($(e.target).data('command'));
                }
            });
            //負けた場合の処理   
            function judgeLose(player_hp){
                if(player_hp <= 0){
                    $('.popup-lose').show();
                }
            }

            //プレイヤーが攻撃した場合の処理・勝った場合の処理   
            function judgeWin(boss_hp){

                    if(boss_hp <= 0){
                        
                        $('#boss-damage').children('span').hide();
                        $('.boss-img').fadeOut(1000);
                        setTimeout(() => {
                                $('.popup-win').show();
                            },1000)
                    } else {
                        //ボスのHPがゼロじゃなかった場合の画面処理
                        let promise = new Promise((resolve, reject) => { // #1
                            setTimeout(() => {
                            resolve($('#boss-damage').children('span').fadeOut())
                            },500)
                        })

                        promise.then(() => { // #2
                            return new Promise((resolve, reject) => {
                                setTimeout(() =>{
                                console.log('ポップアップ表示：ボスの攻撃');
                                resolve($('.popup-3').show())
                                },500)
                            })
                        }).then(() => { // #3
                            return new Promise((resolve, reject) => {
                                setTimeout(() => {
                                console.log('ポップアップを消す')
                                resolve($('.popup-3').hide())
                                },1000)
                            })
                        }).then(() => { // #3
                            return new Promise((resolve, reject) => {
                                setTimeout(() => {
                                resolve( AjaxBattle("boss") )
                                },500)
                            })
                        }).then(() => {
                            return new Promise((resolve, reject) => {
                                setTimeout(() => {
                                resolve($('#player-damage').children('span').fadeOut())
                                },500)
                            })
                        }).then(() => {
                            //ボスの攻撃が終わった後のプレイヤーの残りHPの判定
                            AjaxGetPlayerHp(judgeLose,"Player");
                            
                        }).catch(() => { // エラーハンドリング
                            console.error('Something wrong!')
                        
                        });
                    }
            }

        </script>
    </footer>
    </body>
</html>