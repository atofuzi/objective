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

//使用する各フラグ変数を初期化
$startFlg = false;  //スタート画面の判定
$createPlayer = false;  //プレイヤー召喚画面の判定
$restartFlg = false;  //リセットの判定
$homeFlg = false;  //ホーム画面の判定
$trainingFlg = false;  //修行画面の判定
$questFlg = false;  //クエスト選択画面の判定
$battleFlg  = false;  //バトル画面の判定
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
$player[] = new Player('魔女♀','img/mazyo.png',Type::LANK3_M);
$player[] = new Player('悪魔の子','img/akuma_baby.png',Type::LANK4);

//モンスターのオブジェクト生成
//construct($name,$img,$hp,$power,$magic_power,$defense,$magic_defense)
$monster['マミー'] = new Monster('マミー','img/mummy.png',1000,300,300,50,50);
$monster['デーモン'] = new Monster('デーモン','img/demon.png',2000,600,600,100,100);
$monster['イフリート'] = new Monster('イフリート','img/ifrit.png',5000,1000,1000,500,500);

//クエストのオブジェクト生成
$quest[] = new Quest('マミーを討伐せよ！',1,$monster['マミー']);
$quest[] = new Quest('デーモンを討伐せよ！',2,$monster['デーモン']);
$quest[] = new Quest('イフリートを討伐せよ！',3,$monster['イフリート']);

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

    public function attack($targetOjt){
    
        //ターゲットのディフェンス力を計算
        $defense = mt_rand($targetOjt->getDefense()*0.9,$targetOjt->getDefense()*1.1);

        //自身が与えるダメージ量を計算
        $damage = $this->getPower() - round($defense);

        //ダメージ量がマイナスの場合
        if($damage < 0){
            $damage = 1;
        }
        //クリティカル判定(確率2分の１)
        if(!mt_rand(0,4)){
            $damage = ceil($damage *1.5); 
        }


        //ターゲットの残Hpの計算・格納
        $hp = $targetOjt->getHp() - $damage;

        //残りHPがマイナスの場合は0にする
        if($hp < 0){
            $hp = 0;
        }
                
        $targetOjt->setHp($hp);
        $targetOjt->setDamage($damage);
    }

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

    public function attack($targetOjt){
        parent::attack($targetOjt);
    }

    public function setMp($num){
        $this->mp = $num;
    }
    public function getMp(){
        return $this->mp;
    }
    public function setMagic($str){
        array_push($this->magic_skill,$str);
    }
    public function getMagic(){
        return $this->magic_skill;
    }
    public function getType(){
        return $this->type;
    }
}

class BattlePlayer extends Player{
    protected $damage;
    public function __construct($object){
        $this->name = $object->getName();
        $this->img = $object->getImg();
        $this->hp = $object->getHp();
        $this->mp = $object->getMp();
        $this->power = $object->getPower();
        $this->magic_power = $object->getMagicPower();
        $this->defense = $object->getDefense();
        $this->magic_defense = $object->getMagicDefense();
        $this->magic_skill = $object->getMagic();
    }

    public function attack($object_boss){
        parent::attack($object_boss);
    }
    //魔法攻撃をする場合※今回は未実装
    public function magic($str,$object1,$object2){
        
    }

    public function setDamage($num){
        $this->damage = $num;
    }
    public function getDamage(){
        return $this->damage;
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
    public function attack($targetOjt){
        parent::attack($targetOjt);
    }

}


class Boss extends Monster{
    protected $damage;
    public function __construct($object){
        $this->name = $object->getName();
        $this->img = $object->getImg();
        $this->hp = $object->getHp();
        $this->power = $object->getPower();
        $this->magic_power = $object->getMagicPower();
        $this->defense = $object->getDefense();
        $this->magic_defense = $object->getMagicDefense();
    }
    public function attack($object_player){
        parent::attack($object_player);
    }

    public function magic($str,$object1,$object2){
        
    }
    public function setDamage($num){
        $this->damage = $num;
    }
    public function getDamage(){
        return $this->damage;
    }
}

//クエストクラス
class Quest{
    protected $questName;
    protected $questLevel;
    protected $monsterName;
    protected $questImg;
    protected $monster;
    protected $questClear;
    
    public function __construct($str,$num,$object){
        $this->questName = $str;
        $this->questLevel = $num;
        $this->monsterName = $object->getName();
        $this->questImg = $object->getImg();
        $this->monster = $object;
    }
    public function getQuestName(){
        return $this->questName;
    }
    public function getQuestLevel(){
        return $this->questLevel;
    }
    public function getMonsterName(){
        return $this->monsterName;
    }
    public function getQuestImg(){
        return $this->questImg;
    }
    public function getQuestMonster(){
        //モンスターオブジェクトを返す
        return $this->monster;
    }
    public function setQuestClear(){
        $this->questClear = "Clear";
    }
    public function getQuestClear(){
        return $this->questClear;
    }
}

//魔法
class MagicSkill{
    const HEEL = "ヒール";
    const ATTACK_BOOST = "アタックブースト";
    const HOLY = "ホーリー";
  }

//魔法の仕様書の抽象クラス
abstract class Magic{
    public static $target; 
    abstract public static function use($object1,$object2);
    public function getCost(){
        return $this->cost;
    }
    
} 

class HEEL{
    public static $cost = 20;
    public static $target = Target::MYSELF;
    public static function use($object1,$object2){
        //$object1：バトルプレイヤーのオブジェクト
        //$object2：プレイヤーのオブジェクト

        //プレイヤーHPの30％を回復
        $hp = $object1->getHp()+round($object2->getHp() * 0.3);
            //回復後のHPがプレイヤーのHPよりも多い場合はプレイヤーのHPが回復後のHPになる
            if($hp > $object2->getHp()){
                $hp  = $object2->getHp();
            }
        $object1->setHp($hp);
        $mp = $object1->getMp() - HEEL::$cost;
        $object1->setMp($mp);
    }
}

class ATTACK_BOOST{
    public static $cost = 40;
    public static $target = Target::MYSELF;
    public static function use($object1,$object2){
        //$object1：バトルプレイヤーのオブジェクト
        //$object2：プレイヤーのオブジェクト

        //元々のプレイヤーの攻撃力を2倍にする
        $power = $object2->getPower() * 2;
        
        $object1->setPower($power);
        $object1->setMp( $object1->getMp() - ATTACK_BOOST::$cost );
    }
}

class HOLY{
    public static $cost = 100;
    public static $target = Target::ENEMY;
    public static function use($object1,$object2){
        //$object1：バトルプレイヤーのオブジェクト
        //$object2：ボスのオブジェクト

        $magic_defense = mt_rand($object2->getMagicDefense()*0.9,$object2->getMagicDefense()*1.1);

        $damage = $object1->getMagicPower() * 9 - round($m_defense);

        if($damage < 0){
            $damage = 1;
        }
 
        $hp = $object2->getHp() - $damage;
        $object2->setHp($hp);
        $object2->setDamage($damage);

        $object1->setMp( $object1->getMp() - HOLY::$cost );
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


//バトルコマンド
class Command{
    const ATTACK = "こうげき";
    const MAGIC = 1;
  }

//バトルコマンドの対象先
class Target{
    const MYSELF = 0;
    const ENEMY = 1;
}

//バトルターン
class BattleTern{
    public static function Count(){
        $_SESSION['battle_tern'] = $_SESSION['battle_tern'] + 1;
    } 
    public static function Reset(){
        $_SESSION['battle_tern'] = 0;
    }
}

//ステータ生成
function createStatus($object){
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
    createStatus($_SESSION['player']);
    debug('＜プレイヤーステータス＞');
    debug('HP：'.$_SESSION['player']->getHp());
    debug('MP：'.$_SESSION['player']->getMp());
    debug('力：'.$_SESSION['player']->getPower());
    debug('魔力：'.$_SESSION['player']->getMagicPower());
    debug('物理防御：'.$_SESSION['player']->getDefense());
    debug('魔法防御：'.$_SESSION['player']->getMagicDefense());
}
//プレイヤーキャラのレア度を取得
function getRarity($object){
    return floor(($object->getType()/10));

}
//プレイヤーのステータスを取得
function getStatus($object){
    $status = array(
                'HP' => $object->getHp(),
                'MP' => $object->getMp(),
                '力' => $object->getPower(),
                '魔力' => $object->getMagicPower(),
                '物理防御' => $object->getDefense(),
                '魔法防御' => $object->getMagicDefense()
            );
    return $status;
}

//プレイヤーのステイタスを格納
function setStatus($object,$array){
    $object->setHp($array['HP']);
    $object->setMp($array['MP']);
    $object->setPower($array['力']);
    $object->setMagicPower($array['魔力']);
    $object->setDefense($array['物理防御']);
    $object->setMagicDefense($array['魔法防御']);
}

//プレイヤーのHPゲージを取得
function getGage($num){
    return ceil($num/40);
}
//ボスのHPゲージを取得
function getBossGage($num){
    return ceil($num/10);
}

?>