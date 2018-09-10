<?php
// ===============
// 伪数据生成器
// ===============

// 浮点数精度
if (!defined('FLOAT_PRECISION')) {
    define('FLOAT_PRECISION', 2);
}
if (!defined('FLOAT_MAX')) {
    define('FLOAT_MAX', 100000);
}

/**
 * A simple command line option parser.
 * @author guolinchao
 * @email  luoyecb@163.com
 */
class ArgParser
{
    /**
     * option type
     */
    const TYPE_INT = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOL = 'bool';
    const TYPE_STRING = 'str';

    /**
     * all options
     * 
     * @var array
     */
    protected static $opts = [];

    /**
     * all args
     * 
     * @var array
     */
    protected static $args = [];

    protected static $isParsed = false;

    /**
     * add an option
     * 
     * @param string $name
     * @param string $type
     * @param mixed $default default value
     */
    public static function addArgument($name, $type, $default) {
        // check option type
        switch ($type) {
            case self::TYPE_INT:
            case self::TYPE_FLOAT:
            case self::TYPE_BOOL:
            case self::TYPE_STRING:
                break;
            default:
                throw new InvalidArgumentException(sprintf('unknown option type[%s].', $type));
        }

        self::$opts[$name] = [
            't' => $type,
            'v' => $default,
        ];
    }

    /**
     * add bool option
     */
    public static function addBool($name, $default) {
        self::addArgument($name, self::TYPE_BOOL, $default);
    }

    /**
     * add int option
     */
    public static function addInt($name, $default) {
        self::addArgument($name, self::TYPE_INT, $default);
    }

    /**
     * add float option
     */
    public static function addFloat($name, $default) {
        self::addArgument($name, self::TYPE_FLOAT, $default);
    }

    /**
     * add string option
     */
    public static function addString($name, $default) {
        self::addArgument($name, self::TYPE_STRING, $default);
    }

    /**
     * check is a valid option flag
     * 
     * @param  string $opt
     * @return boolean|string
     */
    protected static function isOpt($opt) {
        $opt = trim($opt);
        if (empty($opt) || strlen($opt) <= 1) {
            return false;
        }
        if (strncmp($opt, '--', 2) === 0) {
            return substr($opt, 2); // option name without '--'
        }
        if ($opt[0] == '-') {
            return substr($opt, 1); // option name without '-'
        }
        return false;
    }

    /**
     * check is a valid number
     * 
     * @param  string $str
     * @return boolean|float|integer
     */
    protected static function getNumeric($str) {
        if (is_numeric($str)) {
            return $str + 0; // change to int or float
        }
        return false; // not number
    }

    public static function getArgs() {
        return self::$args;
    }

    public static function getOptions() {
        return self::$opts;
    }

    public static function getOption($name) {
        if (self::$isParsed) {
            return self::$opts[$name]; // actual value
        }
        return self::$opts[$name]['v']; // default value
    }

    /**
     * parse options
     * 
     * @return array
     */
    public static function parse() {
        global $argv;
        if (!self::$isParsed) {
            $idx = 1; // index start from 1.
            $len = count($argv);
            while ($idx < $len) {
                $cur = $argv[$idx];
                // parse args
                if (!($name = self::isOpt($cur))) {
                    self::$args = array_slice($argv, $idx);
                    break;
                } else {
                    // parse options
                    if (!isset(self::$opts[$name])) { // invalid option flag
                        $idx++;
                        continue;
                    }
                    // default value
                    $dft = self::$opts[$name];
                    $idx++; // handle next argument
                    switch ($dft['t']) {
                    case self::TYPE_BOOL:
                        self::$opts[$name]['v'] = true;
                        break;
                    case self::TYPE_INT:
                        if (isset($argv[$idx]) && ($int = self::getNumeric($argv[$idx])) !== false
                            && is_int($int) ) {
                            self::$opts[$name]['v'] = $int;
                            $idx++;
                        }
                        break;
                    case self::TYPE_FLOAT:
                        if (isset($argv[$idx]) && ($float = self::getNumeric($argv[$idx])) !== false
                            && is_float($float) ) {
                            self::$opts[$name]['v'] = $float;
                            $idx++;
                        }
                        break;
                    case self::TYPE_STRING:
                        if (isset($argv[$idx]) && !self::isOpt($argv[$idx])) {
                            self::$opts[$name]['v'] = $argv[$idx];
                            $idx++;
                        }
                    }
                }
            }

            foreach (self::$opts as $name=>$v) {
                self::$opts[$name] = $v['v'];
            }

            self::$isParsed = true;
        }

        return self::$opts;
    }
}


// ========================================================================
// 随机数生成工具类
class RandUtils
{
    // [min, max]
    public static function rand($max = NULL, $min = 0) {
        if($max === NUll) {
            $max = mt_getrandmax();
        }
        return mt_rand($min, $max);
    }

    // 0-1 之间的浮点数
    public static function randFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
	
    // 随机数字字符串
	public static function randNumber($length) {
		$numberStr = '';
        while ($length > 4) {
            $numberStr .= RandUtils::rand(9999, 1000);
            $length -= 4;
        }
		$numberStr .= RandUtils::rand(pow(10, $length)-1, pow(10, $length-1));
		return $numberStr;
	}
}

// 字符串处理工具类
class StringUtils
{
    public static function shuffle($str, $len) {
        return substr(str_shuffle($str), 0, $len);
    }
}
// ========================================================================


/**
 * Generator interfalce
 */
interface IGenerator
{
	public function createData();
}

// phone number
class PhoneNumberGenerator implements IGenerator
{
	private $phoneNumberHead = [
		134, 135, 136, 137, 138, 139, 147, 150, 151, 152, 157, 158, 159, 1705, 178, 182, 183, 184, 187, 188, // China Mobile
        130, 131, 132, 145, 155, 156, 1707, 1708, 1709, 1718, 1719, 176, 185, 186, // China Unicom
        133, 153, 1700, 1701, 177, 180, 181, 189, // China Telecom
        170, 171, 176, // virtual operators
	];
	private $headLen = 44;
	
	public function createData() {
		$phone = $this->phoneNumberHead[ RandUtils::rand($this->headLen) ];
		$phone .= RandUtils::randNumber(11 - strlen($phone));
		return $phone;
	}
}

// phone number
class PhoneNumber2Generator implements IGenerator
{
    public function createData() {
        return '1' . RandUtils::randNumber(10);
    }
}

// UUID
class UUIDGenerator implements IGenerator
{
	public function createData() {
		$tmpStr = md5(uniqid().microtime().mt_rand());
		$uuid = substr($tmpStr, 0, 8) . '-';
		$uuid .= substr($tmpStr, 8, 4) . '-';
		$uuid .= substr($tmpStr, 12, 4) . '-';
		$uuid .= substr($tmpStr, 16, 4) . '-';
		$uuid .= substr($tmpStr, 20, 12);
		return $uuid;
	}
}

// Decimal
class DecimalGenerator implements IGenerator
{
	public function createData() {
        $float = RandUtils::randFloat() * RandUtils::rand(FLOAT_MAX);
        return sprintf('%.'.FLOAT_PRECISION.'f', $float);
	}
}

// Date
class DateGenerator implements IGenerator
{
    public function createData() {
        $op = ['-', '+'][ RandUtils::rand()%2 ];
        $ts = strtotime(sprintf('%s%s sec', $op, RandUtils::rand(100000000)));
        return date('Y-m-d', $ts);
    }
}

// Time
class TimeGenerator implements IGenerator
{
    public function createData() {
        $op = ['-', '+'][ RandUtils::rand()%2 ];
        $ts = strtotime(sprintf('%s%s sec', $op, RandUtils::rand(100000000)));
        return date('H:i:s', $ts);
    }
}

// DateTime
class DateTimeGenerator implements IGenerator
{
    public function createData() {
        $op = ['-', '+'][ RandUtils::rand()%2 ];
        $ts = strtotime(sprintf('%s%s sec', $op, RandUtils::rand(100000000)));
        return date('Y-m-d H:i:s', $ts);
    }
}

// word
class WordGenerator implements IGenerator
{
	protected $wordList = array(
        'alias', 'consequatur', 'aut', 'perferendis', 'sit', 'voluptatem',
        'accusantium', 'doloremque', 'aperiam', 'eaque','ipsa', 'quae', 'ab',
        'illo', 'inventore', 'veritatis', 'et', 'quasi', 'architecto',
        'beatae', 'vitae', 'dicta', 'sunt', 'explicabo', 'aspernatur', 'aut',
        'odit', 'aut', 'fugit', 'sed', 'quia', 'consequuntur', 'magni',
        'dolores', 'eos', 'qui', 'ratione', 'voluptatem', 'sequi', 'nesciunt',
        'neque', 'dolorem', 'ipsum', 'quia', 'dolor', 'sit', 'amet',
        'consectetur', 'adipisci', 'velit', 'sed', 'quia', 'non', 'numquam',
        'eius', 'modi', 'tempora', 'incidunt', 'ut', 'labore', 'et', 'dolore',
        'magnam', 'aliquam', 'quaerat', 'voluptatem', 'ut', 'enim', 'ad',
        'minima', 'veniam', 'quis', 'nostrum', 'exercitationem', 'ullam',
        'corporis', 'nemo', 'enim', 'ipsam', 'voluptatem', 'quia', 'voluptas',
        'sit', 'suscipit', 'laboriosam', 'nisi', 'ut', 'aliquid', 'ex', 'ea',
        'commodi', 'consequatur', 'quis', 'autem', 'vel', 'eum', 'iure',
        'reprehenderit', 'qui', 'in', 'ea', 'voluptate', 'velit', 'esse',
        'quam', 'nihil', 'molestiae', 'et', 'iusto', 'odio', 'dignissimos',
        'ducimus', 'qui', 'blanditiis', 'praesentium', 'laudantium', 'totam',
        'rem', 'voluptatum', 'deleniti', 'atque', 'corrupti', 'quos',
        'dolores', 'et', 'quas', 'molestias', 'excepturi', 'sint',
        'occaecati', 'cupiditate', 'non', 'provident', 'sed', 'ut',
        'perspiciatis', 'unde', 'omnis', 'iste', 'natus', 'error',
        'similique', 'sunt', 'in', 'culpa', 'qui', 'officia', 'deserunt',
        'mollitia', 'animi', 'id', 'est', 'laborum', 'et', 'dolorum', 'fuga',
        'et', 'harum', 'quidem', 'rerum', 'facilis', 'est', 'et', 'expedita',
        'distinctio', 'nam', 'libero', 'tempore', 'cum', 'soluta', 'nobis',
        'est', 'eligendi', 'optio', 'cumque', 'nihil', 'impedit', 'quo',
        'porro', 'quisquam', 'est', 'qui', 'minus', 'id', 'quod', 'maxime',
        'placeat', 'facere', 'possimus', 'omnis', 'voluptas', 'assumenda',
        'est', 'omnis', 'dolor', 'repellendus', 'temporibus', 'autem',
        'quibusdam', 'et', 'aut', 'consequatur', 'vel', 'illum', 'qui',
        'dolorem', 'eum', 'fugiat', 'quo', 'voluptas', 'nulla', 'pariatur',
        'at', 'vero', 'eos', 'et', 'accusamus', 'officiis', 'debitis', 'aut',
        'rerum', 'necessitatibus', 'saepe', 'eveniet', 'ut', 'et',
        'voluptates', 'repudiandae', 'sint', 'et', 'molestiae', 'non',
        'recusandae', 'itaque', 'earum', 'rerum', 'hic', 'tenetur', 'a',
        'sapiente', 'delectus', 'ut', 'aut', 'reiciendis', 'voluptatibus',
        'maiores', 'doloribus', 'asperiores', 'repellat'
    );
	
    public function createData() {
		return $this->wordList[ RandUtils::rand(count($this->wordList) - 1) ];
	}
}

// word
class Word2Generator implements IGenerator
{
    public function createData() {
        $char_len = RandUtils::rand(10, 2);
        return StringUtils::shuffle('abcdefghijklmnopqrstuvwxyz', $char_len);
    }
}

// Email
class EmailGenerator implements IGenerator
{
	private $mailSuffix = array(
		'@gmail.com','@yahoo.com','@msn.com','@hotmail.com','@foxmail.com',
		'@qq.com','@163.com','@163.net','@googlemail.com','@126.com','@sina.cn',
		'@sina.com','@sohu.com','@yahoo.com.cn','@tom.com','@sogou.com','@netvigator.com'
	);
	private $suffixLen = 17;
	
	public function createData() {
		$woreGe = FactoryGenerator::create('word');
		return $woreGe->createData() . $this->mailSuffix[ RandUtils::rand($this->suffixLen - 1) ];
	}
}

// 汉字
class ChineseGenerator implements IGenerator
{
    // 2500个常用汉字
	private $chineseChars = array(
		'一','乙','二','十','丁','厂','七','卜','人','入','八','九','几','儿','了','力','乃','刀','又','三','于','干','亏','士','工','争','色','壮','冲','冰',
		'土','才','寸','下','大','丈','与','万','上','小','口','巾','山','千','乞','川','亿','个','勺','久','凡','及','夕','丸','么','广','亡','门','义','之',
		'尸','弓','己','已','子','卫','也','女','飞','刃','习','叉','马','乡','丰','王','井','开','夫','天','无','元','专','云','扎','艺','木','五','支','厅',
		'不','太','犬','区','历','尤','友','匹','车','巨','牙','屯','比','互','切','瓦','止','少','日','中','冈','贝','内','水','见','午','牛','手','毛','气',
		'升','长','仁','什','片','仆','化','仇','币','仍','仅','斤','爪','反','介','父','从','今','凶','分','乏','公','仓','月','氏','勿','欠','风','丹','匀',
		'乌','凤','勾','文','六','方','火','为','斗','忆','订','计','户','认','心','尺','引','丑','巴','孔','队','办','以','允','予','劝','双','书','幻','玉',
		'刊','示','末','未','击','打','巧','正','扑','扒','功','扔','去','甘','世','古','节','本','术','可','丙','左','厉','右','石','布','龙','平','灭','轧',
		'东','卡','北','占','业','旧','帅','归','且','旦','目','叶','甲','申','叮','电','号','田','由','史','只','央','兄','叼','叫','另','叨','叹','四','生',
		'失','禾','丘','付','仗','代','仙','们','仪','白','仔','他','斥','瓜','乎','丛','令','用','甩','印','乐','句','匆','册','犯','外','处','冬','鸟','务',
	    '包','饥','主','市','立','闪','兰','半','汁','汇','头','汉','宁','穴','它','讨','写','让','礼','训','必','议','讯','记','永','司','尼','民','出','辽',
	    '奶','奴','加','召','皮','边','发','孕','圣','对','台','矛','纠','母','幼','丝','式','刑','动','扛','寺','吉','扣','考','托','老','执','巩','圾','扩',
	    '扫','地','扬','场','耳','共','芒','亚','芝','朽','朴','机','权','过','臣','糠','燥','臂','翼','骤','鞭','覆','蹦','镰','翻','鹰','警','攀','蹲','颤',
		'再','协','西','压','厌','在','有','百','存','而','页','匠','夸','夺','灰','达','列','死','成','夹','轨','邪','划','迈','毕','至','此','贞','师','尘',
	    '尖','劣','光','当','早','吐','吓','虫','曲','团','同','吊','吃','因','吸','吗','屿','帆','岁','回','岂','刚','则','肉','网','年','朱','先','丢','舌',
	    '竹','迁','乔','伟','传','乒','乓','休','伍','伏','优','伐','延','件','任','伤','价','份','华','仰','仿','伙','伪','自','血','向','似','后','行','舟',
	    '全','会','杀','合','兆','企','众','爷','伞','创','肌','朵','杂','危','旬','瓣','爆','疆','庄','庆','亦','刘','齐','坝','贡','攻','赤','折','抓','扮',
		'交','次','衣','产','决','充','妄','闭','问','闯','羊','并','关','米','灯','州','汗','污','江','池','汤','忙','兴','宇','守','宅','字','安','讲','军',
	    '许','论','农','讽','设','访','寻','那','迅','尽','导','异','孙','阵','阳','收','阶','阴','防','奸','如','妇','好','她','妈','戏','羽','观','欢','买',
	    '红','纤','级','约','纪','驰','巡','寿','弄','麦','形','进','戒','吞','远','违','运','扶','抚','坛','技','坏','扰','拒','找','批','扯','址','走','抄',
		'坊','抖','护','壳','志','扭','块','声','把','报','却','劫','芽','花','芹','芬','苍','芳','严','芦','劳','克','苏','杆','杠','杜','材','村','杏','极',
	    '李','杨','求','更','束','豆','两','丽','医','辰','励','否','还','歼','来','连','步','坚','旱','盯','呈','时','吴','助','县','旨','负','各','名','多',
		'里','呆','园','旷','围','呀','吨','足','邮','男','困','吵','串','员','听','吩','吹','呜','吧','吼','别','岗','帐','财','针','钉','告','我','乱','利',
	    '秃','秀','私','每','兵','估','体','何','但','伸','作','伯','伶','佣','低','你','住','位','伴','身','皂','佛','近','彻','役','返','余','希','坐','谷',
	    '妥','含','邻','岔','肝','肚','肠','龟','免','狂','犹','角','删','条','卵','岛','迎','饭','饮','系','言','冻','状','亩','况','床','库','疗','应','冷',
	    '这','序','辛','弃','冶','忘','闲','间','闷','判','灶','灿','弟','汪','沙','抢','孝','均','抛','投','坟','抗','坑','语','扁','袄','祖','神','祝','误',
		'汽','沃','泛','沟','没','沈','沉','怀','忧','快','完','宋','宏','牢','究','穷','灾','良','证','启','评','补','初','社','识','诉','诊','词','译','君',
	    '灵','即','层','尿','尾','迟','局','改','张','忌','际','陆','阿','陈','阻','附','妙','妖','妨','努','忍','劲','鸡','驱','纯','纱','纳','纲','驳','纵',
	    '纷','纸','纹','纺','驴','纽','奉','玩','环','武','青','责','现','表','规','抹','拢','拔','拣','担','坦','押','抽','拐','拖','拍','者','顶','拆','拥',
	    '抵','拘','势','抱','垃','拉','拦','拌','幸','招','坡','披','拨','择','抬','诱','说','诵','垦','退','既','屋','单','炒','炊','炕','炎','炉','沫','浅',
		'其','取','苦','若','茂','苹','苗','英','范','直','茄','茎','茅','林','枝','杯','柜','析','板','松','枪','构','杰','述','枕','丧','或','画','卧','事',
	    '刺','枣','雨','卖','矿','码','厕','奔','奇','奋','态','欧','垄','妻','轰','顷','转','斩','轮','软','到','非','叔','肯','齿','些','虎','虏','肾','贤',
	    '尚','旺','具','果','味','昆','国','昌','畅','明','易','昂','典','固','忠','咐','呼','鸣','咏','呢','岸','岩','帖','罗','帜','岭','凯','败','贩','购',
		'图','钓','制','知','垂','牧','物','乖','刮','秆','和','季','委','佳','侍','供','使','例','版','侄','侦','侧','凭','侨','佩','货','依','的','迫','质',
	    '欣','征','往','爬','彼','径','所','舍','金','命','斧','爸','采','受','乳','贪','念','贫','肤','肺','肢','肿','胀','朋','股','肥','服','胁','周','昏',
	    '鱼','兔','狐','忽','狗','备','饰','饱','饲','变','京','享','店','夜','庙','府','底','剂','郊','废','净','盲','放','刻','育','闸','闹','郑','券','卷',
		'沿','泡','注','泻','泳','泥','沸','波','泼','泽','治','怖','性','怕','怜','怪','学','宝','宗','定','宜','审','宙','官','空','帘','实','试','郎','诗',
	    '肩','房','诚','衬','衫','视','话','诞','询','该','详','建','肃','录','隶','居','届','刷','屈','弦','承','孟','孤','陕','降','限','妹','姑','姐','姓',
	    '始','驾','参','艰','线','练','组','细','驶','织','终','驻','驼','绍','经','贯','奏','春','帮','珍','玻','毒','型','挂','封','持','项','垮','挎','城',
	    '挠','政','赴','赵','挡','挺','括','拴','拾','挑','指','垫','挣','挤','拼','法','泄','河','沾','泪','油','泊','咳','哪','炭','峡','罚','贱','贴','骨',
		'挖','按','挥','挪','某','甚','革','荐','巷','带','草','茧','茶','荒','茫','荡','荣','故','胡','南','药','标','枯','柄','栋','相','查','柏','柳','柱',
	    '柿','栏','树','要','咸','威','歪','研','砖','厘','厚','砌','砍','面','耐','耍','牵','残','殃','轻','鸦','皆','背','战','点','临','览','竖','省','削',
	    '尝','是','盼','眨','哄','显','哑','冒','映','星','昨','畏','趴','胃','贵','界','虹','虾','蚁','思','蚂','虽','品','咽','骂','哗','咱','响','哈','咬',
		'拜','看','矩','怎','牲','选','适','秒','香','种','秋','科','重','复','竿','段','便','俩','贷','顺','修','保','室','宫','宪','突','穿','窃','客','冠',
		'促','侮','俭','俗','俘','信','皇','泉','鬼','侵','追','俊','盾','待','律','很','须','叙','剑','逃','食','盆','胆','胜','胞','胖','脉','勉','狭','狮',
	    '独','狡','狱','狠','贸','怨','急','饶','蚀','饺','饼','弯','将','奖','哀','亭','亮','度','迹','庭','疮','疯','疫','疤','姿','亲','音','帝','施','闻',
	    '阀','阁','差','养','美','姜','叛','送','类','迷','前','首','逆','总','炼','炸','炮','烂','剃','洁','洪','洒','浇','浊','洞','测','洗','活','派','洽',
	    '染','济','洋','洲','浑','浓','津','恒','恢','恰','恼','恨','举','觉','宣','钞','钟','钢','钥','钩','卸','缸','原','套','峰','圆','第','剪','兽','清',
		'昼','费','陡','眉','孩','除','险','院','娃','姥','姨','姻','娇','怒','架','贺','盈','勇','怠','柔','垒','绑','绒','结','绕','骄','绘','给','络','骆',
	    '绝','绞','统','耕','耗','艳','泰','珠','班','素','蚕','顽','盏','匪','捞','栽','捕','振','载','赶','起','盐','捎','捏','埋','捉','捆','捐','损','都',
	    '哲','逝','捡','换','挽','热','恐','壶','挨','耻','耽','恭','莲','莫','荷','获','晋','恶','真','框','桂','档','桐','株','桥','桃','格','校','核','样',
		'逐','烈','殊','顾','轿','较','顿','毙','致','柴','桌','虑','监','紧','党','晒','眠','晓','鸭','晃','晌','晕','蚊','哨','哭','恩','唤','啊','唉','罢',
		'贼','贿','钱','钳','钻','铁','铃','铅','缺','氧','特','牺','造','乘','敌','秤','租','积','秧','秩','称','秘','透','笔','笑','笋','债','借','值','倚',
	    '倾','倒','倘','俱','倡','候','俯','倍','倦','健','臭','射','躬','息','徒','徐','舰','舱','般','航','途','拿','爹','爱','颂','翁','脆','脂','胸','胳',
	    '脏','胶','脑','狸','狼','逢','留','皱','饿','恋','桨','浆','衰','高','席','准','座','脊','症','病','疾','疼','疲','效','离','唐','资','凉','站','剖',
	    '竞','部','旁','旅','畜','阅','羞','瓶','拳','粉','料','益','兼','烤','烘','宵','宴','根','索','哥','速','逗','栗','配','翅','辱','唇','夏','础','破',
		'烦','烧','烛','烟','递','涛','浙','涝','酒','涉','消','浩','海','涂','浴','浮','流','润','浪','浸','涨','烫','涌','悟','悄','悔','悦','害','宽','家',
		'宾','窄','容','宰','案','请','朗','诸','读','扇','袜','袖','袍','被','祥','课','谁','调','冤','谅','谈','谊','剥','恳','展','剧','屑','弱','陵','陶',
	    '陷','陪','娱','娘','通','能','难','预','桑','绢','绣','验','继','球','理','捧','堵','描','域','掩','捷','排','掉','堆','推','掀','授','教','掏','掠',
	    '培','接','控','探','据','掘','职','基','著','勒','黄','萌','萝','菌','菜','萄','菊','萍','菠','营','械','梦','梢','梅','检','梳','梯','桶','救','副',
	    '票','戚','爽','聋','袭','盛','雪','辅','辆','虚','雀','堂','常','匙','晨','符','葱','落','朝','辜','葵','棒','棋','植','森','椅','椒','棵','棍','棉',
		'睁','眯','眼','悬','野','啦','晚','啄','距','跃','略','蛇','累','唱','患','唯','崖','崭','崇','圈','铜','铲','银','甜','梨','犁','移','笨','笼','笛',
		'敏','做','袋','悠','偿','偶','偷','您','售','停','偏','假','得','衔','盘','船','斜','盒','鸽','悉','欲','彩','领','脚','脖','脸','脱','象','够','猜',
	    '猪','猎','猫','猛','馅','馆','凑','减','毫','麻','痒','痕','廊','康','庸','鹿','盗','章','竟','商','族','旋','望','率','着','盖','粘','粗','粒','断',
		'添','淋','淹','渠','渐','混','渔','淘','液','淡','深','婆','梁','渗','情','惜','惭','悼','惧','惕','惊','惨','惯','寇','寄','宿','窑','密','谋','谎',
	    '祸','谜','逮','敢','屠','弹','随','蛋','隆','隐','婚','婶','颈','绩','绪','续','骑','绳','维','绵','绸','绿','琴','斑','替','款','堪','搭','塔','越',
	    '趁','趋','超','提','堤','博','揭','喜','插','揪','搜','煮','援','裁','搁','搂','搅','握','揉','斯','期','欺','联','散','惹','葬','葛','董','葡','敬',
		'棕','惠','惑','逼','厨','厦','硬','确','雁','殖','裂','雄','暂','雅','辈','悲','紫','辉','敞','赏','掌','晴','暑','最','量','喷','晶','喇','遇','喊',
		'跌','跑','遗','蛙','蛛','蜓','喝','喂','喘','喉','幅','帽','赌','赔','黑','铸','铺','链','销','锁','锄','锅','锈','锋','锐','短','智','毯','鹅','剩',
	    '稍','程','稀','税','筐','等','筑','策','筛','筒','答','筋','筝','傲','傅','牌','堡','集','焦','傍','储','奥','街','惩','御','循','艇','舒','番','释',
	    '禽','腊','脾','腔','鲁','猾','猴','然','馋','装','蛮','就','痛','童','阔','善','羡','普','粪','尊','道','曾','焰','港','湖','渣','湿','温','渴','滑',
	    '湾','渡','游','滋','溉','愤','慌','惰','愧','愉','慨','割','寒','富','窜','搏','塌','蜂','嗓','置','棚','景','践','辩','糖','糕','燃','澡','激','懒',
		'窝','窗','遍','裕','裤','裙','谢','谣','谦','属','屡','强','粥','疏','隔','隙','絮','嫂','登','缎','缓','编','骗','缘','瑞','魂','肆','摄','摸','填',
		'鼓','摆','携','搬','摇','搞','塘','摊','蒜','勤','鹊','蓝','墓','幕','蓬','蓄','蒙','蒸','献','禁','楚','想','槐','榆','楼','概','赖','酬','感','碍',
	    '碑','碎','碰','碗','碌','雷','零','雾','雹','输','督','龄','鉴','睛','睡','睬','鄙','愚','暖','盟','歇','暗','照','跨','跳','跪','路','跟','遣','蛾',
		'罪','罩','错','锡','锣','锤','锦','键','锯','矮','辞','稠','愁','筹','签','简','毁','舅','鼠','催','傻','像','躲','微','愈','遥','腰','腥','腹','腾',
	    '腿','触','解','酱','痰','廉','新','韵','意','粮','数','煎','塑','慈','煤','煌','满','漠','源','滤','滥','滔','溪','溜','滚','滨','粱','滩','慎','誉',
	    '塞','谨','福','群','殿','辟','障','嫌','嫁','叠','缝','缠','静','碧','璃','墙','撇','嘉','摧','截','誓','境','摘','摔','聚','蔽','慕','暮','蔑','模',
	    '榴','榜','榨','歌','遭','酷','酿','酸','磁','愿','需','弊','裳','颗','嗽','竭','端','壤','耀','躁','嚼','嚷','籍','魔','灌','蠢','霸','露','囊','罐',
		'蜻','蜡','蝇','蜘','赚','锹','锻','舞','稳','算','箩','管','僚','鼻','魄','貌','膜','膊','膀','鲜','疑','馒','裹','敲','豪','膏','遮','腐','瘦','辣',
		'旗','精','歉','熄','熔','漆','漂','漫','滴','演','漏','慢','寨','赛','察','蜜','谱','嫩','翠','熊','凳','骡','缩','慧','撕','撒','趣','趟','撑','播',
	    '撞','撤','增','聪','鞋','蕉','蔬','横','槽','樱','橡','飘','醋','醉','震','霉','瞒','题','暴','瞎','影','踢','踏','踩','踪','蝶','蝴','嘱','墨','镇',
	    '靠','稻','黎','稿','稼','箱','箭','篇','僵','躺','僻','德','艘','膝','膛','熟','摩','颜','毅','糊','遵','潜','潮','懂','额','慰','劈','操','燕','薯',
	    '薪','薄','颠','橘','整','融','醒','餐','嘴','蹄','器','赠','默','镜','赞','缴','戴','擦','鞠','藏','霜','霞','瞧','蹈','螺','穗','繁','辫','赢','糟',
		'篮','邀','衡','膨','雕','磨','凝','辨','壁','避',
	);
    
    public function createData() {
        return $this->chineseChars[ RandUtils::rand(2499) ];
    }
}

// 汉字
class Chinese2Generator implements IGenerator
{
	// PHP正则: /[\x4e00-\x9fa5]+/
	private $min = 19968; // \u4e00
	private $max = 40869; // \u9fa5

	public function createData() {
		$rand = dechex(RandUtils::rand($this->max, $this->min));
		return json_decode(sprintf('"\u%s"', $rand));
	}
}

// 姓名
class NameGenerator implements IGenerator
{
	// 百家姓
	protected $surname = array(
		'赵','钱','孙','李','周','吴','郑','王','冯','陈','楮','卫','蒋','沈','韩','杨','朱','秦',
		'尤','许','何','吕','施','张','孔','曹','严','华','金','魏','陶','姜','戚','谢','邹','喻',
		'柏','水','窦','章','云','苏','潘','葛','奚','范','彭','郎','鲁','韦','昌','马','苗','凤',
		'花','方','俞','任','袁','柳','酆','鲍','史','唐','费','廉','岑','薛','雷','贺','倪','汤',
		'滕','殷','罗','毕','郝','邬','安','常','乐','于','时','傅','皮','卞','齐','康','伍','余',
		'元','卜','顾','孟','平','黄','和','穆','萧','尹','姚','邵','湛','汪','祁','毛','禹','狄',
		'米','贝','明','臧','计','伏','成','戴','谈','宋','茅','庞','熊','纪','舒','屈','项','祝',
		'董','梁','杜','阮','蓝','闽','席','季','麻','强','贾','路','娄','危','江','童','颜','郭',
		'梅','盛','林','刁','锺','徐','丘','骆','高','夏','蔡','田','樊','胡','凌','霍','虞','万',
		'支','柯','昝','管','卢','莫','经','房','裘','缪','干','解','应','宗','丁','宣','贲','邓',
		'郁','单','杭','洪','包','诸','左','石','崔','吉','钮','龚','程','嵇','邢','滑','裴','陆',
		'荣','翁','荀','羊','於','惠','甄','麹','家','封','芮','羿','储','靳','汲','邴','糜','松',
		'井','段','富','巫','乌','焦','巴','弓','牧','隗','山','谷','车','侯','宓','蓬','全','郗',
		'班','仰','秋','仲','伊','宫','宁','仇','栾','暴','甘','斜','厉','戎','祖','武','符','刘',
		'景','詹','束','龙','叶','幸','司','韶','郜','黎','蓟','薄','印','宿','白','怀','蒲','邰',
		'从','鄂','索','咸','籍','赖','卓','蔺','屠','蒙','池','乔','阴','郁','胥','能','苍','双',
		'闻','莘','党','翟','谭','贡','劳','逄','姬','申','扶','堵','冉','宰','郦','雍','郤','璩',
		'桑','桂','濮','牛','寿','通','边','扈','燕','冀','郏','浦','尚','农','温','别','庄','晏',
		'柴','瞿','阎','充','慕','连','茹','习','宦','艾','鱼','容','向','古','易','慎','戈','廖',
		'庾','终','暨','居','衡','步','都','耿','满','弘','匡','国','文','寇','广','禄','阙','东',
		'欧','殳','沃','利','蔚','越','夔','隆','师','巩','厍','聂','晁','勾','敖','融','冷','訾',
		'辛','阚','那','简','饶','空','曾','毋','沙','乜','养','鞠','须','丰','巢','关','蒯','相',
		'查','后','荆','红','游','竺','权','逑','盖','益','桓','公','万俟','司马','上官','欧阳','夏侯',
		'诸葛','闻人','东方','赫连','皇甫','尉迟','公羊','澹台','公冶','宗政','濮阳','淳于','单于','太叔',
		'申屠','公孙','仲孙','轩辕','令狐','锺离','宇文','长孙','慕容','鲜于','闾丘','司徒','司空','丌官',
		'司寇','仉','督','子车','颛孙','端木','巫马','公西','漆雕','乐正','壤驷','公良','拓拔','夹谷',
		'谷梁','晋','楚','阎','法','汝','鄢','涂','钦','段干','百里','东郭','南门','呼延','归','海',
		'羊舌','微生','岳','帅','缑','亢','况','后','有','琴','梁丘','左丘','东门','西门','商','牟',
		'佴','伯','赏','南宫','墨','哈','谯','笪','年','爱','阳','佟','第五','言','福','宰父','佘',
	);
	
	protected $lastName = array(
		// 男士常见名
        '伟','强','磊','洋','勇','军','杰','涛','超','明',
        '刚','平','辉','鹏','华','飞','鑫','波','斌','宇',
        '浩','凯','健','俊','帆','帅','旭','宁','龙','林',
        '欢','阳','建华','亮','成','畅','建','峰','建国','建军',
        '晨','瑞','志强','兵','雷','东','欣','博','彬','坤',
        '全安','荣','岩','杨','文','利','楠','建平','嘉俊','晧',
        '建明','子安','新华','鹏程','学明','博涛','捷','文彬','楼','鹰',
        '松','伦','超','钟','瑜','振国','洪','毅','昱然','哲',
        '翔','翼','祥','国庆','哲彦','正诚','正豪','正平','正业','志诚',
        '志新','志勇','志明','志强','志文','致远','智明','智勇','智敏','智渊',
		// 女士常见名
        '芳','娜','敏','静','敏静','秀英','丽','洋','艳','娟',
        '文娟','君','文君','珺','霞','明霞','秀兰','燕','芬','桂芬',
        '玲','桂英','丹','萍','华','红','玉兰','桂兰','英','梅',
        '莉','秀珍','雪','依琳','旭','宁','婷','馨予','玉珍','凤英',
        '晶','欢','玉英','颖','红梅','佳','倩','琴','兰英','云',
        '洁','爱华','淑珍','春梅','海燕','晨','冬梅','秀荣','瑞','桂珍',
        '莹','秀云','桂荣','秀梅','丽娟','婷婷','玉华','琳','雪梅','淑兰',
        '丽丽','玉','秀芳','欣','淑英','桂芳','丽华','丹丹','桂香','淑华',
        '秀华','桂芝','小红','金凤','文','利','楠','红霞','瑜','桂花',
        '璐','凤兰','腊梅','瑶','嘉','怡','冰冰','玉梅','慧','婕','莉莉'
    );
	protected $surnameLength = 503; // 一共504个姓氏
	protected $lasgNameLength = 200; // 一共201个常见名
	
	public function createData() {
		$name = $this->surname[ RandUtils::rand($this->surnameLength) ];
		$name .= $this->lastName[ RandUtils::rand($this->lasgNameLength) ];
		return $name;
	}
}

// 姓名
class Name2Generator extends NameGenerator
{
	public function createData() {
		$chge = FactoryGenerator::create('chinese');

        $name = $this->surname[ RandUtils::rand($this->surnameLength) ];
        $index = RandUtils::rand() % 3;
		$name .= $chge->createData();
		if($index <= 1) {
			$name .= $chge->createData();
		}
		return $name;
	}
}

// ===============================================================
// 
class FactoryGenerator
{
    public static $classmaps = [
        'phone' => PhoneNumberGenerator::class,
        'phone2' => PhoneNumber2Generator::class,
        'uuid' => UUIDGenerator::class,
        'decimal' => DecimalGenerator::class,
        'date' => DateGenerator::class,
        'time' => TimeGenerator::class,
        'datetime' => DateTimeGenerator::class,
        'word' => WordGenerator::class,
        'word2' =>Word2Generator::class,
        'email' => EmailGenerator::class,
        'chinese' => ChineseGenerator::class,
        'chinese2' => Chinese2Generator::class,
        'name' => NameGenerator::class,
        'name2' => Name2Generator::class,
    ];
    public static $objectContainers = [];

    // register generator
    public static function register($geKey, $className) {
        if (!isset(self::$classmaps[$geKey]) && class_exists($className)) {
            self::$classmaps[$geKey] = $className;
        }
    }

    // factory method
    public static function create($geKey) {
        if (isset(self::$classmaps[$geKey])) {
            if (!isset(self::$objectContainers[$geKey])) {
                self::$objectContainers[$geKey] = new self::$classmaps[$geKey]();
            }
            return self::$objectContainers[$geKey];
        }
        return null;
    }

    // 静态调用
    public static function __callStatic($name, $args) {
        if (isset(self::$classmaps[$name])) {
            $obj = self::create($name);
            return $obj->createData();
        }
        return null;
    }

    // 格式化字符串
    public static function formatString($string) {
        return preg_replace_callback('/\{\s*(\w+)\s*\}/i',
            [__CLASS__, '_formatStringCallback'], $string);
    }

    public static function _formatStringCallback($matchs) {
        $key = $matchs[1];
        if (isset(self::$classmaps[$key])) {
            $obj = self::create($key);
            return $obj->createData();
        }
        return $matchs[0];
    }
}

// main
function execMain() {
	if (PHP_SAPI != 'cli') {
		exit('Please run under the commnad line.');
	}

    // 命令行参数
    ArgParser::addBool('help', false);
    ArgParser::addInt('n', 1);
    ArgParser::addString('key', '');
    ArgParser::addString('format', '');
    ArgParser::parse();

    $opts = ArgParser::getOptions();
    extract($opts);

    if ($help) {
        $filename = basename(__FILE__, '.php');
        echo <<<USAGE_STR
Usage: php {$filename} [option]
option:
    --help|-help: help infomation.
    --key|-key KEY_NAME
    --format|-format FORMAT_STRING
    -n NUM

USAGE_STR;
        exit(0);
    }

    if ($key) {
        $ret = FactoryGenerator::$key();
        if ($ret) {
            echo $ret, "\n";
        }
        exit(0);
    }

    // 格式化字符串
    if ($format) {
        for ($j = 0; $j < $n; $j++) {
            echo FactoryGenerator::formatString($format);
            echo "\n";
        }
        exit(0);
    }
}
// start exec.
execMain();
