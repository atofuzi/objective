<?php
ini_set('log_errors','on');  //ログを取るか
ini_set('error_log','php.log');  //ログの出力ファイルを指定
session_start(); //セッション使う


//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = true;
//デバックログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}


define('GAME_START','ゲーム開始');

//使用する変数を初期化
$startFlg = false;
$createPlayer = false;
$restartFlg = false;
$homeFlg = false;
//$_SESSION = array();

//モンスター格納用
$monsters = array();

//プレイヤー格納用
$player = array();
//construct($name,$img,$hp,$power,$magic_power,$defense,$magic_defense,$mp)
$player[] = new Player('金太郎','img/kintaro.png',Type::LANK1);
$player[] = new Player('忍者','img/ninja.png',Type::LANK1_M);
$player[] = new Player('悪魔♂','img/akuma_boy.png',Type::LANK2);
$player[] = new Player('悪魔♀','img/akuma_gill.png',Type::LANK2_M);
$player[] = new Player('剣士♂','img/soldier_man.png',Type::LANK3);
$player[] = new Player('剣士♀','img/soldier_woman.jpg',Type::LANK3_M);
$player[] = new Player('悪魔の子','img/akuma_baby.png',Type::LANK4);


//抽象クラス（生き物クラス）
abstract class Creature{
    //プロパティ
    protected $name;
    protected $img;
    protected $hp;
    protected $power;
    protected $magic_power;
    protected $defense;
    protected $magic_defense;
    
    abstract public function attack();

    public function setName($str){
        $this->name = $str;
    }
    public function getName(){
        return $this->name;
    }
    public function setImg($url){
        $this->img = $url;
    }
    public function getImg(){
        return $this->img;
    }
    public function setHp($num){
        $this->hp = $num;
    }
    public function getHp(){
        return $this->hp;
    }
    public function setPower($num){
        $this->power = $num;
    }
    public function getPower(){
        return $this->power;
    }
    public function setMagicPower($num){
        $this->magic_power = $num;
    }
    public function getMagicPower(){
        return $this->magic_power;
    }
    public function setDefense($num){
        $this->defense = $num;
    }
    public function getDefense(){
        return $this->defense;
    }
    public function setMagicDefense($num){
        $this->magic_defense = $num;
    }
    public function getMagicDefense(){
        return $this->magic_defense;
    }
}

class Player extends Creature{
    protected $mp;
    protected $type;
    protected $magic_skill = array();
    //コンストラクト
    public function __construct($name,$img,$type){
        $this->name = $name;
        $this->img = $img;
        $this->type= $type;
    }
    public function attack(){

    }
    public function magic_attack(){

    }

    public function magic_skill(){

    }
    
    public function setMp($num){
        $this->mp = $num;
    }
    public function getMp(){
        return $this->mp;
    }
    public function setMagicSkill($str){
        $this->magic_skill .= $str;
    }

    public function getType(){
        return $this->type;
    }
}

class Monster extends Creature{
    //コンストラクト
    public function __construct($name,$img,$hp,$power,$magic_power,$defense,$magic_defense){
        $this->name = $name;
        $this->img = $img;
        $this->hp = $hp;
        $this->power = $power;
        $this->magic_power = $magic_power;
        $this->defense = $defense;
        $this->magic_defense = $magic_defense;
    }
    public function attack(){

    }
    public function magic_attack(){

    }
}

//プレイヤータイプ

class Type{
    const LANK1 = 10;
    const LANK1_M = 11;
    const LANK2 = 20;
    const LANK2_M = 21;
    const LANK3 = 30;
    const LANK3_M = 31;
    const LANK4 = 40;
  }

function setStatus($object){
    debug('ステータスを生成します');
    debug('プレイヤータイプ：'.$object->getType());
    debug('プレイヤー名前：'.$object->getName());
    debug('プレイヤー画像：'.$object->getImg());

    switch($object->getType()){
        case Type::LANK1 :
            $object->setHp(mt_rand(450, 500));
            $object->setPower(mt_rand(10, 15));
            $object->setMagicPower(mt_rand(5, 10));
            $object->setDefense(mt_rand(10, 15));
            $object->setMagicDefense(mt_rand(5, 10));
            $object->setMp(mt_rand(80, 100));
            break;

        case Type::LANK1_M :
            $object->setHp(mt_rand(400, 450));
            $object->setPower(mt_rand(5, 10));
            $object->setMagicPower(mt_rand(10, 15));
            $object->setDefense(mt_rand(5, 10));
            $object->setMagicDefense(mt_rand(10, 15));
            $object->setMp(mt_rand(100,120));
            break;

        case Type::LANK2 :
            $object->setHp(mt_rand(650, 700));
            $object->setPower(mt_rand(30, 40));
            $object->setMagicPower(mt_rand(20, 30));
            $object->setDefense(mt_rand(30, 40));
            $object->setMagicDefense(mt_rand(20, 30));
            $object->setMp(mt_rand(120, 150));
            break;

        case Type::LANK2_M :
            $object->setHp(mt_rand(500, 550));
            $object->setPower(mt_rand(20, 30));
            $object->setMagicPower(mt_rand(30, 40));
            $object->setDefense(mt_rand(20, 30));
            $object->setMagicDefense(mt_rand(30, 40));
            $object->setMp(mt_rand(150, 180));
            break;  

        case Type::LANK3 :
            $object->setHp(mt_rand(750, 850));
            $object->setPower(mt_rand(60, 80));
            $object->setMagicPower(mt_rand(40, 60));
            $object->setDefense(mt_rand(60, 80));
            $object->setMagicDefense(mt_rand(40, 60));
            $object->setMp(mt_rand(200, 250));
            break; 

        case Type::LANK3_M :
            $object->setHp(mt_rand(650, 750));
            $object->setPower(mt_rand(40, 60));
            $object->setMagicPower(mt_rand(60, 80));
            $object->setDefense(mt_rand(40, 60));
            $object->setMagicDefense(mt_rand(60, 80));
            $object->setMp(mt_rand(250, 300));
            break;

        case Type::LANK4 :
            $object->setHp(mt_rand(1000, 1200));
            $object->setPower(mt_rand(100, 120));
            $object->setMagicPower(mt_rand(100, 120));
            $object->setDefense(mt_rand(100, 120));
            $object->setMagicDefense(mt_rand(100, 120));
            $object->setMp(mt_rand(500,700));
            break;
    }
}
//プレイヤー生成
function createPlayer(){
    global $player;

    //ランダムにプレイヤーを選択
    $player = $player[mt_rand(0,6)];

    $_SESSION['player'] = $player;

    //プレイヤーのステータスを自動生成
    setStatus($_SESSION['player']);
    debug('＜プレイヤーステータス＞');
    debug('HP：'.$_SESSION['player']->getHp());
    debug('MP：'.$_SESSION['player']->getMp());
    debug('力：'.$_SESSION['player']->getPower());
    debug('魔力：'.$_SESSION['player']->getMagicPower());
    debug('物理防御：'.$_SESSION['player']->getDefense());
    debug('魔法防御：'.$_SESSION['player']->getMagicDefense());
}

function getRarity($object){
    return floor(($object->getType()/10));

}

if(!empty($_POST)){

    debug('ポスト送信あり');
    $startFlg = (!empty($_POST['start']))? true : false;
    $restartFlg  = (!empty($_POST['restart']))? true : false;
    $createPlayer  = (!empty($_POST['createPlayer']))? true : false;
    $homeFlg  = (!empty($_POST['home']))? true : false;

    debug(print_r($_POST,true));
    debug('スタートフラグ：'.$startFlg);
    debug('リスタートフラグ：'.$startFlg);
    debug('プレイヤー生成フラグ：'.$createPlayer);
    debug('ホームフラグ：'.$createPlayer);

    if($startFlg){
        debug('ゲームスタート');
        $_SESSION['start'] = GAME_START;
    }elseif($createPlayer){
        debug('プレイヤーを生成します');
        createPlayer();
    }elseif($homeFlg){


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
                        <input type="submit" name="restart" value="修行">
                        <input type="submit" name="restart" value="クエスト">
                        <input type="submit" name="restart" value="リセット">
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



        $(document).on('click', function(e) {
            if( $(e.target).is($game_screen) === true && popFlg === true){
                $popup.fadeOut();
                popFlg = false;
            }
            else if($(e.target).is($status_show)){
                $popup.addClass('show').fadeIn();
                popFlg = true;
            }
        });
        

        
    </script>

</html>