<?php
error_reporting(E_ALL);
if(version_compare(PHP_VERSION, '5.3.0', '<')) die('Require PHP 5.3 or higher');
if(!extension_loaded('mysqli') && !extension_loaded('pdo_mysql')) die('Install mysqli or pdo_mysql extension!');
session_name('SQL');
session_start();
$bg='';
$step=20;
$version="3.6.8";
$bbs= array('False','True');
$jquery= (file_exists('jquery.js')?"/jquery.js":"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js");
class DBT {
	public static $sqltype= array('mysqli','pdo_mysql');
	private $_cnx, $_query, $_fetch = array(), $_num_col, $dbty;
	private static $instance = NULL;
	public static function factory($host,$user,$pwd,$db='') {
		if(!isset(self::$instance))
		try {
		self::$instance = new DBT($host,$user,$pwd,$db);
		} catch(Exception $ex) {
			return false;
		}
		return self::$instance;
	}
	public function __construct($host,$user,$pwd,$db) {
		$dbty= self::$sqltype;
		$this->dbty=(extension_loaded($dbty[0])?$dbty[0]:$dbty[1]);
		if($this->dbty == self::$sqltype[0]) {
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$this->_cnx = new mysqli($host,$user,$pwd,$db);
			mysqli_report(MYSQLI_REPORT_OFF);
		} else {
			$this->_cnx = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pwd);
			$this->_cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}
	private function __clone() {}
	public function db($db) {
		return $this->_cnx->query('USE '.$db);
	}
	public function query($sql) {
		try{
			if($this->dbty == self::$sqltype[0]) {
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$this->_query = $this->_cnx->query($sql);
			mysqli_report(MYSQLI_REPORT_OFF);
			} else {
			$this->_query = $this->_cnx->query($sql);
			}
		return $this;
		} catch(Exception $e) {
		return false;
		}
	}
	public function begin() {
		if($this->dbty == self::$sqltype[0]) {
		$this->_cnx->autocommit(FALSE);
		if(version_compare(PHP_VERSION, '5.5.0', '<')) return $this->query("START TRANSACTION");
		else return $this->_cnx->begin_transaction();
		} else {
		return $this->_cnx->beginTransaction();
		}
	}
	public function commit() {
		return $this->_cnx->commit();
	}
	public function last() {
		if($this->dbty == self::$sqltype[0]) {
		return $this->_cnx->affected_rows;
		} else {
		return $this->_query->rowCount();
		}
	}
	public function fetch($mode=0) {
	$res= array();
	if($this->dbty == self::$sqltype[0]) {
		if($mode == 1) {
			while($row = $this->_query->fetch_row()) {
			$res[] = $row;
			}
		} elseif($mode == 2) {
			while($row = $this->_query->fetch_assoc()) {
			$res[] = $row;
			}
		} else {
			return $this->_query->fetch_row();
		}
		return $res;
	} else {
		if($mode > 0) {
			switch($mode){
			case 1:
			$ty = PDO::FETCH_NUM;
			break;
			case 2:
			$ty = PDO::FETCH_ASSOC;
			break;
			}
			while($row = $this->_query->fetch($ty)) {
			$res[] = $row;
			}
			return $res;
		} else {
			return $this->_query->fetch(PDO::FETCH_NUM);
		}
	}
	}
	public function num_row() {
		if($this->dbty == self::$sqltype[0]) {
		$_num_row = $this->_query->num_rows;
		} else {
		$_num_row = $this->_query->rowCount();
		}
		return $_num_row;
	}
	public function num_col() {
		if($this->dbty == self::$sqltype[0]) {
		$this->_num_col = $this->_query->field_count;
		} else {
		$this->_num_col = $this->_query->columnCount();
		}
		return $this->_num_col;
	}
}
class ED {
	public $con, $path, $sg, $u_pr, $fieldtype, $ver, $sqlda, $pg_lr=8, $salt="#a1b2c3#";
	public $deny= array('mysql','information_schema','performance_schema', 'sys');
	public function __construct() {
	$pi= (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO'));
	$this->sg= preg_split('!/!', $pi,-1,PREG_SPLIT_NO_EMPTY);
	$scheme= 'http'.(empty($_SERVER['HTTPS']) === true || $_SERVER['HTTPS'] === 'off' ? '' : 's').'://';
	$r_uri= isset($_SERVER['PATH_INFO']) === true ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
	$script= $_SERVER['SCRIPT_NAME'];
	$this->path= $scheme.$_SERVER['HTTP_HOST'].(strpos($r_uri, $script) === 0 ? $script : rtrim(dirname($script),'/.\\')).'/';
	$this->fieldtype= array('Numbers'=>array('INT','TINYINT','SMALLINT','MEDIUMINT','BIGINT','DOUBLE','DECIMAL','FLOAT'),'Strings'=>array('VARCHAR','CHAR','TEXT','TINYTEXT','MEDIUMTEXT','LONGTEXT'),'DateTime'=>array('DATE','DATETIME','TIME','TIMESTAMP','YEAR'),'Binary'=>array('BIT','BLOB','TINYBLOB','MEDIUMBLOB','LONGBLOB'),'Lists'=>array('ENUM','SET'),'Spatial'=>array('GEOMETRY','POINT','LINESTRING','POLYGON','MULTIPOINT','MULTILINESTRING','MULTIPOLYGON','GEOMETRYCOLLECTION'));
	$this->sqlda= array('CONTAINS_SQL'=>'CONTAINS SQL','NO SQL'=>'NO SQL','READS_SQL_DATA'=>'READS SQL DATA','MODIFIES_SQL_DATA'=>'MODIFIES SQL DATA');
	}
	public function clean($el, $cod='') {
		if(get_magic_quotes_gpc()) {
		$el= stripslashes($el);
		}
		if($cod==1) {
		return trim(str_replace(array("\r\n","\r"), array("\n","\n"), $el));//quota
		} else {
		return trim(str_replace(array(">","<","\\","'",'"',"\r\n","\r"), array("&gt;","&lt;","\\\\","&#039;","&quot;","\n","\n"), $el));
		}
	}
	public function sanitize($el) {
		return preg_replace(array('/[^A-Za-z0-9]/'),'_',trim($el));
	}
	public function utf($fi) {
		if(function_exists("iconv") && preg_match("~^\xFE\xFF|^\xFF\xFE~",$fi)) {
		$fi=iconv("utf-16","utf-8",$fi);
		}
		return $fi;
	}
	public function form($url, $enc='') {
		return "<form action='".$this->path.$url."' method='post'".($enc==1 ? " enctype='multipart/form-data'":"").">";
	}
	public function fieldtypes($slct='') {
		$ft='';
		foreach($this->fieldtype as $fdk=>$fdtype) {
		if(is_array($fdtype)) {
		$ft .= "<optgroup label='$fdk'>";
		foreach($fdtype as $fdty) $ft .= "<option value='$fdty'".(($slct!='' && $fdty==$slct)?" selected":"").">$fdty</option>";
		$ft .= "</optgroup>";
		}
		}
		return $ft;
	}
	public function post($idxk='', $op='', $clean='') {
		if($idxk === '' && !empty($_POST)) {
		return ($_SERVER['REQUEST_METHOD'] === 'POST' ? TRUE : FALSE);
		}
		if(!isset($_POST[$idxk])) return FALSE;
		if(is_array($_POST[$idxk])) {
		if(isset($op) && is_numeric($op)) {
		return $this->clean($_POST[$idxk][$op],$clean);
		} else {
		$aout= array();
		foreach($_POST[$idxk] as $key=>$val) {
		if($val !='') $aout[$key]= $this->clean($val,$clean);
		}
		}
		} else $aout= $this->clean($_POST[$idxk],$clean);
		if($op=='i') return isset($aout);
		if($op=='e') return empty($aout);
		if($op=='!i') return !isset($aout);
		if($op=='!e') return !empty($aout);
		return $aout;
	}
	public function redir($way='', $msg=array()) {
		if(count($msg) > 0) {
		foreach($msg as $ks=>$ms) $_SESSION[$ks]= $ms;
		}
		header('Location: '.$this->path.$way);exit;
	}
	public function menu($db='',$tb='',$left='',$sp=array()) {
		$srch=((!empty($_SESSION['_sqlsearch_'.$db.'_'.$tb]) && $this->sg[0]==20) ? " [<a href='{$this->path}24/$db/$tb/reset'>reset search</a>]":"");
		$str='';
		if($db==1 || $db!='') $str .="<div class='l2'><ul><li><a href='{$this->path}'>Databases</a></li>";
		if($db!='' && $db!=1) $str .= "<li><a href='{$this->path}31/$db'>Export</a></li><li><a href='{$this->path}5/$db'>Tables</a></li>";

		if($tb!="") $str .="<li class='divider'>---</li><li><a href='{$this->path}10/$db/$tb'>Structure</a></li><li><a href='{$this->path}20/$db/$tb'>Browse</a></li><li><a href='{$this->path}21/$db/$tb'>Insert</a></li><li><a href='{$this->path}24/$db/$tb'>Search</a></li><li><a class='del' href='{$this->path}25/$db/$tb'>Empty</a></li><li><a class='del' href='{$this->path}26/$db/$tb'>Drop</a></li>";

		$str .=($db==""?"":"</ul><br class='clear'/></div>");
		if($db!="" && $db!=1) $str .="<div class='l3'>&nbsp;Database: <b>$db</b>".($tb==""?"":" Table: <b>$tb</b>".$srch).(count($sp) >1 ?" ".$sp[0].": <b>".$sp[1]."</b>":"")."</div>";
		$str .="<div class='container'>";
		if($left==2) $str .="<div class='col3'>";

		$f=1;$nrf_op='';
		while($f<50) {
		$nrf_op.= "<option value='$f'>$f</option>";
		++$f;
		}
		if($left==1) $str .= "<div class='col1'><h3>SQL Query</h3>".$this->form("30/$db")."<textarea name='qtxt'></textarea><br/><button type='submit'>Run</button></form>".(!in_array($db,$this->deny)?"
		<h3>Import</h3><small>sql, csv, json, gz, zip</small>".$this->form("30/$db",1)."<input type='file' name='importfile' />
		<input type='hidden' name='send' value='ja' /><br/><button type='submit'>Upload (&lt;".ini_get("upload_max_filesize")."B)</button></form>
		<h3>Create Table</h3>".$this->form("6/$db")."Table Name<br/><input type='text' name='ctab' /><br/>
		Number of fields<br/><select name='nrf'>".$nrf_op."</select><br/><button type='submit'>Create</button></form>
		<h3>Rename DB</h3>".$this->form("3/$db")."<input type='text' name='rdb' /><br/><button type='submit'>Rename</button></form>
		<h3>Create</h3><a href='{$this->path}40/$db'>View</a><a href='{$this->path}41/$db'>Trigger</a><a href='{$this->path}42/$db'>Routine</a><a href='{$this->path}43/$db'>Event</a>":"")."</div><div class='col2'>";
		return $str;
	}
	public function enco($str) {
		$salt= $this->salt.$_SERVER['HTTP_USER_AGENT'];
		$count = strlen($str);
		$str = (string)$str;
		$kount = strlen($salt);
		$x=0; $y=0;
		$eStr = "";
		while($x < $count) {
			$char = ord($str[$x]);
			$keyS = is_numeric($salt[$y]) ? $salt[$y] : ord($salt[$y]);
			$encS = $char + $keyS;
			$eStr .= chr($encS);
			++$x;++$y;
			if($y == $kount) $y=0;
		}
		return base64_encode(base64_encode($eStr));
	}
	public function deco($str) {
		$salt= $this->salt.$_SERVER['HTTP_USER_AGENT'];
		$str = base64_decode(base64_decode($str));
		$count = strlen($str);
		$str = (string)$str;
		$kount = strlen($salt);
		$x=0; $y=0;
		$eStr = "";
		while($x < $count) {
			$char = ord($str[$x]);
			$keyS = is_numeric($salt[$y]) ? $salt[$y] : ord($salt[$y]);
			$decS = $char - $keyS;
			$eStr .= chr($decS);
			++$x;++$y;
			if($y == $kount) $y=0;
		}
		return $eStr;
	}
	public function check($level=array(), $param=array()) {
		if(isset($_SESSION['token']) && !empty($_SESSION['user'])) {//check login
			$pwd = $this->deco($_SESSION['token']);
			$usr = $_SESSION['user'];
			$ho = $_SESSION['host'];
			$this->con = DBT::factory($ho, $usr, $pwd);
			if($this->con === false) {
				$this->redir("50",array('err'=>"Can't connect to the server"));
			}
			session_regenerate_id(true);
		} else {
			$this->redir("50");
		}
		//mysql version
		$this->ver= $this->con->query('select version()')->fetch();
		$v2= preg_split("/[\-]+/", $this->ver[0], -1, PREG_SPLIT_NO_EMPTY);
		if(version_compare($v2[0], '5.1.30', '<')) die('Require MySQL 5.1.30 or higher');
		//privileges
		$this->u_pr=array();
		$q_upri = $this->con->query("SHOW GRANTS FOR '{$usr}'@'{$ho}'")->fetch(1);
		preg_match('/^GRANT\s(.*)\sON\s(.*)\./i', $q_upri[0][0], $upr1);
		$u_pr1= $upr1[1];//usage
		if($u_pr1 == 'USAGE') {
		preg_match('/^GRANT\s(.*)\sON\s(.*)\./i', $q_upri[1][0], $upr2);
		$u_pr2= $upr2[1];//priv
		} else {
		$u_pr2=$upr1[1];
		}
		//access
		if(!empty($u_pr2)) $this->u_pr[]= explode(",",$u_pr2);
		$u_root= array(52,53,54,55);//restrict user management
		if(in_array($this->sg[0],$u_root) && $u_pr1!="ALL PRIVILEGES" && !stripos($u_pr1,'CREATE USER')) $this->redir('',array("err"=>"Access denied"));

		if(isset($this->sg[1])) $db= $this->sg[1];
		if(in_array(1,$level)) {//check db
			$se = $this->con->db($db);
			if(!$se) $this->redir();
		}
		if(in_array(2,$level)) {//check table
			$q_com = $this->con->query("SHOW TABLE STATUS FROM $db like '".$this->sg[2]."'");
			if(!$q_com->num_row()) $this->redir("5/".$db);
			foreach($q_com->fetch(2) as $r_com) {
			if(stristr($r_com['Comment'],'Unknown storage')==true) $this->redir("5/".$db);
			if($r_com['Comment']=='VIEW' && $this->sg[0] != 20) $this->redir("5/".$db);//prevent to show view as table
			}
			$q_ = $this->con->query("SELECT COUNT(*) FROM ".$this->sg[2]);
			if(!$q_->num_row()) $this->redir("5/".$db);
		}
		if(in_array(3,$level)) {//check field
			$tb= $this->sg[2];
			$qr= $this->con->query("SHOW FIELDS FROM {$db}.{$tb} LIKE '".$this->sg[3]."'");
			if(!$qr->num_row()) $this->redir($param['redir']."/$db/".$tb);
			if(isset($this->sg[5])) {
			$qr2= $this->con->query("SHOW FIELDS FROM {$db}.{$tb} LIKE '".$this->sg[5]."'");
			if(!$qr2->num_row()) $this->redir($param['redir']."/$db/".$tb);
			}
		}
		if(in_array(4,$level)) {//check paginate
			if(!is_numeric($param['pg']) || $param['pg'] > $param['total'] || $param['pg'] < 1) $this->redir($param['redir']);
		}
		if(in_array(5,$level)) {//check spp
			$sp= array('view','trigger','procedure','function','event');
			$op= $this->sg[0];
			$tb= $this->sg[2];
			$sg3= $this->sg[3];
			if($op!=49 && ($op==40 && $sg3!=$sp[0]) || ($op==41 && $sg3!=$sp[1]) || ($op==42 && $sg3!=$sp[2] && $sg3!=$sp[3]) || ($op==43 && $sg3!=$sp[4])) $this->redir("5/".$db);
			if($sg3 == $sp[0]) {//check view
				$q = $this->con->query("SHOW CREATE VIEW $tb");
				if(!$q) $this->redir("5/".$db);
			} elseif($sg3 == $sp[1]) {//check trigger
				$q = $this->con->query("SHOW TRIGGERS FROM $db WHERE `Trigger`='$tb'")->fetch();
				if($tb != $q[0]) $this->redir("5/".$db);
			} elseif($sg3 == $sp[4]) {//check event
				$q = $this->con->query("SHOW EVENTS FROM $db LIKE '$tb'")->fetch();
				if($tb != $q[1]) $this->redir("5/".$db);
			} else {//check proc, func
				$q = $this->con->query("SHOW ".$sg3." STATUS WHERE `Db`='$db' AND `Name`='$tb'")->fetch();
				if($tb != $q[1]) $this->redir("5/".$db);
			}
		}
		if(in_array(6,$level)) {//check user
		if(empty($this->sg[2])) {
		$u1='';$h1=base64_decode($this->sg[1]);
		} else {
		$u1=$this->sg[1];$h1=base64_decode($this->sg[2]);
		}
		$q_exist = $this->con->query("SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user='$u1' AND host='$h1');")->fetch();
		if($q_exist[0] != 1) $this->redir("52");
		}
	}
	public function pg_number($pg, $totalpg) {
		if($totalpg > 1) {
		$kl= ($pg > $this->pg_lr ? $pg-$this->pg_lr:1);//left pg
		$kr= (($pg > $totalpg-$this->pg_lr) ? $totalpg:$pg+$this->pg_lr);//right pg
		if($this->sg[0]==20) $link= $this->path."20/".$this->sg[1]."/".$this->sg[2];
		elseif($this->sg[0]==5) $link= $this->path."5/".$this->sg[1];
		$pgs='';
		while($kl <= $kr) {
			$pgs .= (($kl == $pg) ? "<span class='active'>".$kl."</span>":"<a href='$link/$kl'>$kl</a>");
			++$kl;
		}
		$lft= ($pg>1?"<a href='$link/1'>First</a><a href='$link/".($pg-1)."'>Prev</a>":"");
		$rgt= ($pg < $totalpg?"<a href='$link/".($pg+1)."'>Next</a><a href='$link/$totalpg'>Last</a>":"");
		return "<div class='pg'>".$lft.$pgs.$rgt."</div>";
		}
	}
	public function imp_csv($fname, $fbody) {
		$exist= $this->con->query("SELECT 1 FROM ".$fname);
		if(!$exist) $this->redir("5/".$this->sg[1],array('err'=>"Table not exist"));
		$fname= $this->sanitize($fname);
		$e= array();
		if(@is_file($fbody)) $fbody= file_get_contents($fbody);
		$fbody= $this->utf($fbody);
		$fbody= preg_replace('/^\xEF\xBB\xBF|^\xFE\xFF|^\xFF\xFE/','', $fbody);
		$lines= array();
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $fbody) as $line) {
		if(trim($line)!='') $lines[] = $line;
		}
		preg_match("/\"[\"]?+\"|[;,]/", $lines[0], $mark);
		$i=1;
		while($i < count($lines)) {
			$e1='';
			$li=array();
			foreach(str_getcsv($lines[$i],$mark[0]) as $lin) $li[] = preg_replace("/^\"*|\"*$/u", "", $lin);
			$e1.="INSERT INTO ".$fname."(".implode(',',str_getcsv($lines[0],$mark[0])).") VALUES(";
			foreach($li as $l) $e1 .= (is_numeric($l)?$l:"'".htmlspecialchars($l)."'").',';
			$e[]= substr($e1,0,-1).");";
			++$i;
		}
		if(empty($e)) $this->redir("5/".$this->sg[1],array('err'=>"Query failed"));
		return $e;
	}
	public function imp_json($fname, $fbody) {
		$exist= $this->con->query("SELECT 1 FROM ".$fname);
		if(!$exist) $this->redir("5/".$this->sg[1],array('err'=>"Table not exist"));
		$e= array();
		if(@is_file($fbody)) $fbody= file_get_contents($fbody);
		$fbody= $this->utf($fbody);
		$rgxj ="~^\xEF\xBB\xBF|^\xFE\xFF|^\xFF\xFE|(\/\/).*\n*|(\/\*)*.*(\*\/)\n*|((\"*.*\")*('*.*'))(*SKIP)(*F)~";
		$ex= preg_split($rgxj, $fbody, -1, PREG_SPLIT_NO_EMPTY);
		$lines= json_decode($ex[0], true);
		$jr='';
		foreach($lines[0] as $k=>$li) $jr.=$k.",";
		foreach($lines as $line) {
		$jv='';
		foreach($line as $ky=>$el) $jv.=(is_numeric($el)?$el:"'".$el."'").",";
		$e[]="INSERT INTO ".$fname." (".substr($jr,0,-1).") VALUES (".substr($jv,0,-1).")";
		}
		return $e;
	}
	public function create_ro($db, $pn) {
		if(is_numeric(substr($pn,0,1))) $this->redir("5/".$db,array('err'=>"Not a valid name"));
		$roty= $this->post('roty');
		$rtn= "CREATE DEFINER=`".$_SESSION['user']."`@`".$_SESSION['host']."` $roty `".$pn."`";
		$rt2="(";
		$roc= count($this->post('ropty'));
		if($roty=='PROCEDURE') {
			$rc2=0;
			while($rc2 < $roc) {
			if($this->post('roppa',$rc2) =='') {
				$rt2.= ' ';
			} else {
				$rt2.=$this->post('ropin',$rc2)." `".$this->post('roppa',$rc2)."` ".$this->post('ropty',$rc2).($this->post('ropva',$rc2)!=''?"(".$this->post('ropva',$rc2).")":"");
				if(in_array($this->post('ropty',$rc2),$this->fieldtype['Numbers'])) {
				$rt2.=($this->post('rop1',$rc2)!=''?" ".$this->post('rop1',$rc2):"");
				}
				if(in_array($this->post('ropty',$rc2),$this->fieldtype['Strings'])) {
				$rt2.=($this->post('rop2',$rc2)!=''?" CHARSET ".$this->post('rop2',$rc2):"");
				}
				$rt2.=",";
			}
			++$rc2;
			}
			$rtn.=substr($rt2,0,-1).") ";
		} elseif($roty=='FUNCTION') {
			$rc3=0;
			while($rc3 < $roc) {
			$rt2.="`".$this->post('roppa',$rc3)."` ".$this->post('ropty',$rc3).($this->post('ropva',$rc3)!=''?"(".$this->post('ropva',$rc3).")":"");
			if(in_array($this->post('ropty',$rc3),$this->fieldtype['Numbers'])) {
			$rt2.=($this->post('rop1',$rc3)!=''?" ".$this->post('rop1',$rc3):"");
			}
			if(in_array($this->post('ropty',$rc3),$this->fieldtype['Strings'])) {
			$rt2.=($this->post('rop2',$rc3)!=''?" ".$this->post('rop2',$rc3):"");
			}
			$rt2.=",";
			++$rc3;
			}
			$rtn.=substr($rt2,0,-1).") RETURNS ".$this->post('rorty').($this->post('rorva','!e')?"(".$this->post('rorva').")":"");
			if(in_array($this->post('rorty'),$this->fieldtype['Numbers'])) {
			$rtn.=($this->post('rorop1','!e')?" ".$this->post('rorop1'):"");
			}
			if(in_array($this->post('rorty'),$this->fieldtype['Strings'])) {
			$rtn.=($this->post('rorop2','!e')?" CHARSET ".$this->post('rorop2'):"");
			}
		}
		$rtn.= " ".$this->sqlda[$this->post('rosda')].($this->post('rodet','i')?" DETERMINISTIC":"").($this->post('rosec')=='INVOKER'?" SQL SECURITY INVOKER":"").($this->post('rocom','!e')?" COMMENT '".$this->post('rocom')."'":"")."\n".$this->post('rodf','',1);
		return $this->con->query($rtn);
	}
}
$ed= new ED;
$head= '<!DOCTYPE html><html><head>
<title>EdMyAdmin</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge" /><![endif]-->
<style type="text/css">
* {margin:0;padding:0;font-size: 12px;color:#333;font-family:Arial}
html {-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}
html, textarea {overflow:auto}
.container {overflow:auto;overflow-y:hidden;-ms-overflow-y:hidden;white-space:nowrap;position:relative}
[hidden],.more div{display:none}
.more {position:relative}
.more div a {display:block;padding:0;width:100%}
.more div {position:absolute;z-index:8}
small {font-size:9px}
.clear {clear:both}
.cntr {text-align:center}
.left {float:left}
.left button {margin:0 1px}
.right, .link {float:right}
.link {padding:3px 0}
.pg * {padding:0 2px}
.active, caption {font-weight:bold}
.l2 ul {list-style:none}
.l2 li {float:left}
h3 {background:#cdf;border-top:1px solid #555;margin-top:1px;padding:2px 0}
a {color:#842;text-decoration:none;background-color:transparent}
a:hover {text-decoration:underline}
a,a:active,a:hover {outline:0}
table a,.l1 a,.l2 a,.col1 a {padding:0 3px}
table {border-collapse: collapse;border-spacing:0;border-bottom:1px solid #555}
td, th {padding:4px;vertical-align:top}
.dot {border: 1px dotted #842}
input[type=checkbox],input[type=radio]{position: relative;vertical-align: middle;bottom: 1px}
input[type=text],input[type=password],input[type=file],textarea,button,select {width:100%;padding:2px 0;border:1px solid #bbb;outline:none;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}
input[type=text],select {min-width:98px !important}
select {padding:1px 0}
optgroup option {padding-left:8px}
textarea, .he {min-height:90px}
.msg {position:absolute;top:0;right:0;z-index:9;position:fixed}
.ok, .err {padding:8px;font-weight:bold;font-size:13px}
.ok {background:#EFE;color:#080;border-bottom:2px solid #080}
.err {background:#FEE;color:#f00;border-bottom:2px solid #f00}
.l1, th, caption, button {background:#9be}
.l2,.c1,.col1 {background:#cdf}
.c2,.more div a {background:#fff}
.l3, tr:hover.r, button:hover {background:#fe3 !important}
.ok,.err,.col1,.col2 {display:inline-block;*display:inline;zoom:1}
.col1 textarea {position: relative;z-index:3}
.col1 button {margin-bottom:1px}
.col1 {vertical-align:top;padding-left:3px;padding-right:3px}
.col1 {padding-bottom: 1000%;margin-bottom: -1000%}
.col1, .dw {width:180px}
.col2 table {margin:3px 3px 0 3px}
.col3 table, .dw {margin:3px auto}
.dw div {padding-top:3px}

.auto button, .auto input, .auto select {width:auto}
.l1,.l2,.l3,.wi {width:100%}
.move,.bb,.msg,.a {cursor:pointer}
#tdbs,#privs,[class^=pa],[id^=px],.rou2 {display:none}
.bb * {font: 22px/18px Arial}
</style>
<script src="'.$jquery.'" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#host").focus();
$("noscript").remove();
'.((empty($_SESSION['ok']) && empty($_SESSION['err'])) ? '':'$("body").fadeIn("slow").prepend("'.
(!empty($_SESSION['ok']) ? '<div class=\"msg ok\">'.$_SESSION['ok'].'<\/div>':'').
(!empty($_SESSION['err']) ? '<div class=\"msg err\">'.$_SESSION['err'].'<\/div>':'').'");
setTimeout(function(){$(".msg").fadeOut("slow",function(){$(this).remove();});}, 7000);').'
$(".del").click(function(e){//confirm
e.preventDefault();
$(".msg").remove();
var but=$(this);
$("body").fadeIn("slow").prepend("<div class=\"msg\"><div class=\"ok\">Yes<\/div><div class=\"err\">No<\/div><\/div>");
$(".msg .ok").click(function(){window.location = but.prop("href");});
$(".msg .err").click(function(){$(".msg").remove();});
$(document).keyup(function(e){
if(e.which==89 || e.which==32) window.location=but.prop("href");
if(e.which==27 || e.which==78) $(".msg").remove();
});
});
$(".msg").dblclick(function(){$(this).hide()});
$(".more").hover(function(){$(".more div").fadeIn();},function(){$(".more div").fadeOut(100);});
if($("#one:checked").val()=="on"){$("#every,#evend").hide();}else{$("#every,#evend").show();}
$("#one").click(function(){if($("#one:checked").val()=="on"){$("#every,#evend").hide();}else{$("#every,#evend").show();}});//add event case
if($("#rou").val()=="FUNCTION"){$(".rou1").hide();$(".rou2").show();}else{$(".rou1").show();$(".rou2").hide();}
$("#rou").change(function(){//routine form
if($(this).val()=="FUNCTION"){$(".rou1").hide();$(".rou2").show();}else{$(".rou1").show();$(".rou2").hide();}
});
//load param rows
var rou_p= $("[id^=\"pty_\"]").length;
for (var i=1;i <= rou_p;i++) {
routine(i);
}
$(".up,.down").click(function(){//reorder
var row= $(this).parents("tr:first");
if($(this).is(".up")){row.insertBefore(row.prev());obj1=row.next().prop("id");obj2=row.prop("id");}else{
row.insertAfter(row.next());obj1=row.prop("id");obj2=row.prev().prop("id");
}
$.ajax({type: "POST", url:"'.$ed->path.'9/'.(empty($ed->sg[1])?"":$ed->sg[1]).'/'.(empty($ed->sg[2])?"":$ed->sg[2]).'", data:"n1="+obj1+"&n2="+obj2, success:function(){location.reload();}});
});
if($("#seldb:checked").length == 1){$("#tdbs").fadeIn();}else{$("#tdbs").hide();}
if($("#selpriv:checked").length == 1){$("#privs").fadeIn();}else{$("#privs").hide();}
});//end
function minus(el){//routine remove row
var crr=$("[id^=\"rr_\"]").length;
if(crr>1) $(el).closest("tr").remove();
}
function plus(el){//routine clone row
var cid=$(el).closest("tr").prop("id");
var cnt= $("[id^=\"rr_\"]").length + 1;
$("#"+cid).clone(true).insertAfter("#"+cid).prop("id","rr_"+cnt);
$("#rr_"+cnt).find("[id^=\"pty_\"]").prop("id","pty_"+cnt);
$("[id^=\"rr_\"]").each(function(i) {
$(this).prop("id", "rr_" + (i + 1));
});
$("[id^=\"pty_\"]").each(function(i) {
$(this).prop("id", "pty_" + (i + 1));
});
routine(cnt);
}
var ar1=["INT","TINYINT","SMALLINT","MEDIUMINT","BIGINT","DOUBLE","DECIMAL","FLOAT"];
var ar2=["VARCHAR","CHAR","TEXT","TINYTEXT","MEDIUMTEXT","LONGTEXT"];
function routine(id){
//function returns
var ej=$("#pty2"),ej1=$("#px1"),ej2=$("#px2");
routin2();
ej.change(function(){routin2();});
function routin2(){
if($.inArray(ej.val(),ar1)!= -1){ej1.show();ej2.hide();}else if($.inArray(ej.val(),ar2)!= -1){ej1.hide();ej2.show();}else{ej1.hide();ej2.hide();}
}
//parameters
function routin1(ix){
var el=$("#pty_"+ix).val(),el1=$("#rr_"+ix).find(".pa1"),el2=$("#rr_"+ix).find(".pa2");
if($.inArray(el,ar1)!= -1){el1.show();el2.hide();}else if($.inArray(el,ar2)!= -1){el1.hide();el2.show();}else{el1.hide();el2.hide();}
}
if(id === undefined) id=0;
routin1(id);
$("#pty_"+id).change(function(){routin1(id);});
}
function show(ex){$("#"+ex).fadeIn("slow");}
function hide(ex){$("#"+ex).fadeOut("slow");}
function selectall(cb,lb) {
var multi=document.getElementById(lb);
if(cb.checked) {
for(var i=0;i<multi.options.length;i++) multi.options[i].selected=true;
}else{
multi.selectedIndex=-1;
}
}
function toggle(cb, ey){
var cbox=document.getElementsByName(ey);
for(var i=0;i<cbox.length;i++) cbox[i].checked=cb.checked;
if(ey="fopt[]") opt();
}
function opt(){
var opt=document.getElementsByName("fopt[]");
for(var i=2;i<opt.length;i++){
if(opt[0].checked == false) opt[i].checked=false;
opt[i].parentElement.style.display=(opt[0].checked ? "block":"none");
}}
</script>
</head><body><noscript><h1 class="msg err">Please activate Javascript in your browser!</h1></noscript>
<div class="l1"><div class="left"><b><a href="https://github.com/edmondsql/edmyadmin">EdMyAdmin '.$version.'</a></b></div>'.(isset($ed->sg[0]) && $ed->sg[0]==50 ? "": '<div class="right"><div class="left more"><span class="a">More <small>&#9660;</small></span><div><a href="'.$ed->path.'60">Info</a><a href="'.$ed->path.'60/var">Variables</a><a href="'.$ed->path.'60/status">Status</a><a href="'.$ed->path.'60/process">Processes</a></div></div><a href="'.$ed->path.'52">Users</a><a href="'.$ed->path.'51">Logout ['.(isset($_SESSION['user']) ? $_SESSION['user']:"").']</a></div>').'<br class="clear"/></div>';
$stru= "<table><caption>TABLE STRUCTURE</caption><tr><th class='dot'>FIELD</th><th class='dot'>TYPE</th><th class='dot'>VALUE</th><th class='dot'>ATTRIBUTES</th><th class='dot'>NULL</th><th class='dot'>DEFAULT</th><th class='dot'>COLLATION</th><th class='dot'>AUTO <input type='radio' name='ex[]'/></th>".(isset($ed->sg[0]) && $ed->sg[0]==11?"<th class='dot'>POSITION</th>":"")."</tr>";
$inttype= array(''=>'&nbsp;','UNSIGNED'=>'unsigned','ZEROFILL'=>'zerofill','UNSIGNED ZEROFILL'=>'unsigned zerofill');

if(!isset($ed->sg[0])) $ed->sg[0]=0;
switch($ed->sg[0]) {
default:
case "": //show DBs
	$ed->check();
	echo $head.$ed->menu()."<div class='col1'>Create Database".$ed->form("2")."<input type='text' name='dbc' /><br/><button type='submit'>Create</button></form></div><div class='col2'><table><tr><th>DATABASES</th><th>TABLES</th><th>ACTIONS</th>";
	$q_db = $ed->con->query("SHOW DATABASES")->fetch(1);
	foreach($q_db as $r_db) {
	$r_db= $r_db[0];
	$bg=($bg==1)?2:1;
	$q_tbs= $ed->con->query("SHOW TABLES FROM ".$r_db);
	echo "<tr class='r c$bg'><td>".$r_db."</td><td>".$q_tbs->num_row()."</td><td>
	<a href='{$ed->path}31/".$r_db."'>Exp</a>".
	(in_array($r_db, $ed->deny) ? "":"<a class='del' href='{$ed->path}4/".$r_db."'>Drop</a>").
	"<a href='{$ed->path}5/".$r_db."'>Browse</a></td></tr>";
	}
	echo "</table>";
break;

case "2": //created DB
	$ed->check();
	if($ed->post('dbc','!e')) {
	$db= $ed->sanitize($ed->post('dbc'));
	$q_cc = $ed->con->query("CREATE DATABASE ".$db);
	if($q_cc) $ed->redir("",array('ok'=>"Created DB"));
	$ed->redir("",array('err'=>"Create DB failed"));
	}
	$ed->redir("",array('err'=>"DB name must not be empty"));
break;

case "3": //rename DB
	$ed->check(array(1));
	$db= $ed->sg[1];
	if($ed->post('rdb','!e') && $ed->sanitize($ed->post('rdb')) != $db) {
		$ndb = $ed->sanitize($ed->post('rdb'));
		$q_dbcheck= $ed->con->query("SHOW DATABASES LIKE '$ndb'")->fetch();
		if($q_dbcheck[0]) $ed->redir("",array('err'=>"Cannot rename, DB already exist"));
		$q_ren = $ed->con->query("CREATE DATABASE ".$ndb);//create DB
		if(!$q_ren) $ed->redir("",array('err'=>"Rename DB failed"));
		//table
		$q_tb = $ed->con->query("SELECT TABLE_NAME,TABLE_TYPE FROM information_schema.TABLES WHERE `TABLE_SCHEMA`='$db'");
		if($q_tb->num_row()) {
		foreach($q_tb->fetch(1) as $r_tb) {
			if($r_tb[1] != 'VIEW') {
			$ed->con->query("CREATE TABLE ".$ndb.".".$r_tb[0]." LIKE ".$db.".".$r_tb[0]);
			$ed->con->query("INSERT ".$ndb.".".$r_tb[0]." SELECT * FROM ".$db.".".$r_tb[0]);
			}
		}
		}
		//routine
		$ed->con->query("UPDATE mysql.proc SET db='$ndb' WHERE db='$db'");
		//event
		$ed->con->query("UPDATE mysql.event SET db='$ndb' WHERE db='$db'");
		//trigger
		$q_tg = $ed->con->query("SHOW TRIGGERS FROM ".$db);
		if($q_tg->num_row()) {
		foreach($q_tg->fetch(1) as $r_tg) {
		$ed->con->query('USE '.$ndb);
		$ed->con->query("CREATE TRIGGER `".$r_tg[0]."` ".$r_tg[4]." ".$r_tg[1]." ON `".$ndb."`.`".$r_tg[2]."` FOR EACH ROW ".$r_tg[3]);
		}
		}
		//view
		$q_viv = $ed->con->query("SELECT TABLE_NAME,VIEW_DEFINITION FROM information_schema.VIEWS WHERE `TABLE_SCHEMA`='$db'");
		if($q_viv->num_row()) {
		foreach($q_viv->fetch(1) as $r_vi) {
			$ed->con->query("CREATE VIEW `$ndb`.`".$r_vi[0]."` AS ".str_replace("`".$db."`", "`".$ndb."`", $r_vi[1]));
		}
		}
		//drop old DB
		$ed->con->query('DROP DATABASE '.$db);
		$ed->redir("",array('ok'=>"Successfully renamed"));
	}
	$ed->redir("5/".$db,array('err'=>"DB name must not be empty"));
break;

case "4": //Drop DB
	$ed->check(array(1));
	$db= $ed->sg[1];
	if(!in_array($db, $ed->deny)) {
	$q_drodb = $ed->con->query("DROP DATABASE ".$db);
	if($q_drodb) $ed->redir("",array('ok'=>"Succeful deleted DB"));
	}
	$ed->redir('',array('err'=>"Delete DB failed"));
break;

case "5": //Show Tables
	$ed->check(array(1));
	$db= $ed->sg[1];
	$q_tbs= $ed->con->query("SHOW TABLES FROM ".$db);
	$ttalr= $q_tbs->num_row();
	$tables= array();
	if($ttalr >0) {
	foreach($q_tbs->fetch(1) as $r_tbs) {
		$tables[] = $r_tbs[0];
	}
	}
	//paginate
	if($ttalr > 0) {
	$ttalpg= ceil($ttalr/$step);
	if(empty($ed->sg[2])) {
		$pg= 1;
	} else {
		$pg= $ed->sg[2];
		$ed->check(array(4),array('pg'=>$pg,'total'=>$ttalpg,'redir'=>'5/'.$db));
	}
	}
	echo $head.$ed->menu($db,'',1);
	if($ttalr > 0) {//start rows
	echo "<table><tr><th>TABLE</th><th>ROWS</th><th>ENGINE</th><th>COLLATE</th><th>COMMENTS</th><th>ACTIONS</th></tr>";
	$ofset= ($pg - 1) * $step;
	$max= $step + $ofset;
	while($ofset < $max) {
		if(!empty($tables[$ofset])) {
		$bg=($bg==1)?2:1;
		$q_com = $ed->con->query("SHOW TABLE STATUS FROM $db like '".$tables[$ofset]."'");
		foreach($q_com->fetch(2) as $r_cm) {
		$_vl= "/$db/".$tables[$ofset];
		if($r_cm['Comment']=='VIEW') {
			$lnk="40{$_vl}/view";
			$dro="49{$_vl}/view";
		} else {
			$lnk="10".$_vl;
			$dro="26".$_vl;
		}
		$q_rows[0]=0;
		if($r_cm['Engine'] || $r_cm['Comment']=='VIEW') {
		$q_t = $ed->con->query("SELECT COUNT(*) FROM ".$tables[$ofset]);
		if($q_t) $q_rows = $q_t->fetch();
		}
		echo "<tr class='r c$bg'><td>".$tables[$ofset]."</td><td>".$q_rows[0]."</td><td>".$r_cm['Engine']."</td><td>".$r_cm['Collation']."</td><td>".$r_cm['Comment']."</td><td><a href='".$ed->path.$lnk."'>Structure</a><a class='del' href='".$ed->path.$dro."'>Drop</a><a href='".$ed->path."20/$db/".$tables[$ofset]."'>Browse</a></td></tr>";
		}
		}
		++$ofset;
	}
	echo "</table>".$ed->pg_number($pg, $ttalpg);
	//spp
	$tsp ='';
	$spps = array('procedure','function');
	$q_sp = array();
	foreach($spps as $spp){
		$q_spp = $ed->con->query("SHOW {$spp} STATUS")->fetch(1);
		if($q_spp) {
		foreach($q_spp as $r_spp) {
		if($r_spp[0] == $db) {
			$tsp=1;
			$q_sp[] = $r_spp;
		}
		}
		}
	}
	if($tsp==1) {
		echo "<table><tr><th>ROUTINE</th><th>TYPE</th><th>COMMENTS</th><th>ACTIONS</th></tr>";
		foreach($q_sp as $r_sp){
			$bg=($bg==1)?2:1;
			if($r_sp[0]==$db) {
			echo "<tr class='r c$bg'><td>".$r_sp[1]."</td><td>".$r_sp[2]."</td><td>".$r_sp[7]."</td><td><a href='{$ed->path}42/".$r_sp[0]."/".$r_sp[1]."/".strtolower($r_sp[2])."'>Edit</a><a class='del' href='{$ed->path}49/".$r_sp[0]."/".$r_sp[1]."/".strtolower($r_sp[2])."'>Drop</a></td></tr>";
			}
		}
		echo "</table>";
	}
	//show triggers
	$q_trg=$ed->con->query("SHOW TRIGGERS FROM ".$db);
	if($q_trg->num_row()) {
		echo "<table><tr><th>TRIGGER</th><th>TABLE</th><th>TIMING</th><th>EVENT</th><th>ACTIONS</th></tr>";
		foreach($q_trg->fetch(1) as $r_tg) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg'><td>".$r_tg[0]."</td><td>".$r_tg[2]."</td><td>".$r_tg[4]."</td><td>".$r_tg[1]."</td><td><a href='{$ed->path}41/$db/".$r_tg[0]."/trigger'>Edit</a><a class='del' href='{$ed->path}49/$db/".$r_tg[0]."/trigger'>Drop</a></td></tr>";
		}
	echo "</table>";
	}
	//events
	if(!in_array($db,$ed->deny)) {
	$q_eve=$ed->con->query("SHOW EVENTS FROM ".$db);
	if($q_eve->num_row()) {
	echo "<table><tr><th>EVENT</th><th>SCHEDULE</th><th>START</th><th>END</th><th>ACTIONS</th></tr>";
	foreach($q_eve->fetch(2) as $r_eve) {
	$bg=($bg==1)?2:1;
	echo "<tr class='r c$bg'><td>".$r_eve['Name']."</td><td>".
	($r_eve['Type']=='RECURRING' ? "Every ".$r_eve['Interval value'].$r_eve['Interval field']."</td><td>".$r_eve['Starts']."</td><td>".$r_eve['Ends']:"AT </td><td>".$r_eve['Execute at']."</td><td>")."</td><td><a href='{$ed->path}43/$db/".$r_eve['Name']."/event'>Edit</a><a class='del' href='{$ed->path}49/$db/".$r_eve['Name']."/event'>Drop</a></td></tr>";
	}
	echo "</table>";
	}
	}
	}//end rows
break;

case "6": //Create table
	$ed->check(array(1));
	$db= $ed->sg[1];
	if($ed->post('ctab','!e') && !is_numeric(substr($ed->post('ctab'),0,1)) && $ed->post('nrf','!e') && is_numeric($ed->post('nrf')) && $ed->post('nrf')>0 ) {
		echo $head.$ed->menu($db,'',2);
		if($ed->post('crtb','i')) {
			$qry1 = "Create TABLE ".$ed->sanitize($ed->post('ctab'))."(";
			for ($nf=0;$nf<$ed->post('nrf');$nf++) {
				$c1=$ed->post('fi'.$nf); $c2=$ed->post('ty'.$nf);
				$c3=($ed->post('va'.$nf,'!e') ? "(".$ed->post('va'.$nf).")" : "");
				$c4=($ed->post('at'.$nf,'!e') ? " ".$ed->post('at'.$nf):"");
				$c5=$ed->post('nc'.$nf);
				$c6=($ed->post('de'.$nf,'!e') ? " default '".$ed->post('de'.$nf)."'":"");
				$c7=($ed->post('ex','!e') && $ed->post('ex',0)!='on' && $ed->post('ex',0)==$nf ? " AUTO_INCREMENT PRIMARY KEY":"");
				$c8=($ed->post('clls'.$nf,'!e') ? " collate ".$ed->post('clls'.$nf):"");
				$qry1 .= $c1." ".$c2.$c3.$c4." ".$c5.$c6.$c7.$c8.",";
			}
			$qry2 = substr($qry1,0,-1);
			$qry= $qry2.")".($ed->post('engs')==""?"":" ENGINE=".$ed->post('engs')).($ed->post('tcomm')!=""?" COMMENT='".$ed->post('tcomm')."'":"").";";
			echo "<p>".($ed->con->query($qry) ? "<b>OK!</b> $qry" : "<b>FAILED!</b> $qry")."</p>";
		} else {
			echo $ed->form("6/$db")."
			<input type='hidden' name='ctab' value='".$ed->sanitize($ed->post('ctab'))."'/>
			<input type='hidden' name='nrf' value='".$ed->post('nrf')."'/>".$stru;
			for ($nf=0;$nf<$ed->post('nrf');$nf++) {
				$bg=($bg==1)?2:1;
				echo "<tr class='c$bg'><td><input type='text' name='fi".$nf."' /></td>
				<td><select name='ty".$nf."'>".$ed->fieldtypes()."</select></td>
				<td><input type='text' name='va".$nf."' /></td><td><select name='at".$nf."'>";
				foreach($inttype as $intk=>$intt) {
				echo "<option value='$intk'>$intt</option>";
				}
				echo "</select></td>
				<td><select name='nc".$nf."'><option value='NOT NULL'>NOT NULL</option><option value='NULL'>NULL</option></select></td>
				<td><input type='text' name='de".$nf."' /></td><td><select name='clls".$nf."'><option value=''>&nbsp;</option>";
				$q_colls = $ed->con->query("SHOW COLLATION");
				foreach($q_colls->fetch(1) as $r_clls) {
					echo "<option value=".$r_clls[0].">".$r_clls[0]."</option>";
				}
				echo "</select></td><td><input type='radio' name='ex[]' value='$nf' /></td></tr>";
			}
			echo "<tr><td class='auto' colspan='8'>Engine <select name='engs'><option value=''>&nbsp;</option>";
			$q_eng= $ed->con->query("SELECT ENGINE FROM information_schema.ENGINES WHERE ENGINE IS NOT NULL AND SUPPORT<>'NO'")->fetch(1);
			foreach($q_eng as $r_eng) {
				echo "<option value='".$r_eng[0]."'>".$r_eng[0]."</option>";
			}
			echo "</select> Table Comment: <input type='text' maxlength='60' size='72' name='tcomm' /></td></tr>
			<tr><td colspan='8'><button type='submit' name='crtb'>Create Table</button></td></tr></table></form>";
		}
	} else {
		$ed->redir("5/".$db,array('err'=>"Create table failed"));
	}
break;

case "9":
	$ed->check(array(1,2));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	if($ed->post('cll','i')) {//change table collation
		$q_altcll = $ed->con->query('ALTER TABLE '.$db.'.'.$tb.' CONVERT TO CHARACTER SET '.strtok($ed->post('cll'),'_').' COLLATE '.$ed->post('cll'));
		if($q_altcll) $ed->redir("10/$db/".$tb, array('ok'=>"Successfully changed"));
		$ed->redir("10/$db/".$tb, array('err'=>"Change failed"));
	}
	if($ed->post('engs','i')) {//change table engine
		$q_engs = $ed->con->query('ALTER TABLE '.$db.'.'.$tb.' ENGINE='.$ed->post('engs'));
		if($q_engs) $ed->redir("5/$db/$tb", array('ok'=>"Successfully changed"));
		$ed->redir("10/$db/$tb", array('err'=>"Change failed"));
	}
	if($ed->post('copytab','!e')) {//copy table in new DB
		$ndb = $ed->post('copytab');
		$q_altchk = $ed->con->query("SELECT 1 FROM {$ndb}.{$tb}");
		if($q_altchk) $ed->redir("10/$db/".$tb, array('err'=>"Table already exists"));
		$q_altcrt = $ed->con->query("CREATE TABLE {$ndb}.{$tb} LIKE {$db}.{$tb}");
		$q_altins = $ed->con->query("INSERT {$ndb}.{$tb} SELECT * FROM {$db}.{$tb}");
		if($q_altcrt && $q_altins) $ed->redir("10/$db/".$tb, array('ok'=>"Successfully copied"));
		$ed->redir("10/$db/".$tb, array('err'=>"Copy table failed"));
	}
	if($ed->post('rtab','!e')) {//rename table
		$ntb = $ed->sanitize($ed->post('rtab'));
		if(is_numeric(substr($ntb,0,1))) $ed->redir("5/$db",array('err'=>"Not a valid table name"));
		$q_creatt = $ed->con->query("SELECT count(*) FROM ".$ntb);
		if(!$q_creatt) {//prevent create duplicate
			//create table
			$q_ttab = $ed->con->query("SELECT TABLE_NAME,TABLE_TYPE FROM information_schema.TABLES WHERE `TABLE_SCHEMA`='$db' AND `TABLE_NAME`='$tb'");
			$r_ttr = $q_ttab->fetch();
			$ed->con->query("CREATE TABLE ".$ntb." LIKE ".$r_ttr[0]);
			$ed->con->query("INSERT INTO ".$ntb." SELECT * FROM ".$r_ttr[0]);
			//rename table in view
			$q_vtb = $ed->con->query("SELECT TABLE_NAME,VIEW_DEFINITION FROM information_schema.VIEWS WHERE `TABLE_SCHEMA`='$db'");
			if($q_vtb->num_row()) {
			foreach($q_vtb->fetch(1) as $r_tv) {
				$ed->con->query("DROP VIEW IF EXISTS ".$db.".".$r_tv[0]);
				$ed->con->query("CREATE VIEW `$db`.`".$r_tv[0]."` AS ".str_replace("`".$tb."`", "`".$ntb."`", $r_tv[1]));
			}
			}
			//rename table in procedure
			$q_prc = $ed->con->query("SELECT name,body FROM mysql.proc WHERE db='$db'");
			if($q_prc->num_row()) {
			foreach($q_prc->fetch(1) as $r_pr) {
				$reptb = preg_replace("/\b(".$tb.")\b/i", $ntb, $r_pr[1]);
				$ed->con->query("UPDATE mysql.proc SET body='$reptb', body_utf8='$reptb' WHERE `name`='".$r_pr[0]."' AND `db`='$db'");
			}
			}
			//rename table in event
			$q_evn = $ed->con->query("SELECT name,body FROM mysql.event WHERE db='$db'");
			if($q_evn->num_row()) {
			foreach($q_evn->fetch(1) as $r_evn) {
				$ntt = preg_replace("/\b(".$tb.")\b/i", $ntb, $r_evn[1]);
				$ed->con->query("UPDATE mysql.event SET body='$ntt',body_utf8='$ntt' WHERE `name`='".$r_evn[0]."' AND `db`='$db'");
			}
			}
			//rename table in triggers
			$q_trg=$ed->con->query("SHOW TRIGGERS FROM ".$db);
			if($q_trg->num_row()) {
			foreach($q_trg->fetch(1) as $r_trg) {
			$ed->con->query("DROP TRIGGER IF EXISTS ".$db.".".$r_trg[0]);
			$ed->con->query("CREATE TRIGGER `".$r_trg[0]."` ".$r_trg[4]." ".$r_trg[1]." ON `".$ntb."` FOR EACH ROW ".$r_trg[3]);
			}
			}
			//drop table
			$ed->con->query("DROP TABLE ".$tb);
			$ed->redir("5/$db",array('ok'=>"Successfully renamed"));
		} else $ed->redir("5/$db",array('err'=>"Table already exist"));
	}
	if($ed->post('n1','!e') && $ed->post('n2','!e')) {//reorder
		$q_fel= $ed->con->query("SHOW FULL FIELDS FROM {$tb} LIKE '".$ed->post('n1')."'");
		foreach($q_fel->fetch(2) as $r_fel) {
		if(empty($r_fel['Extra'])) $ed->con->query("ALTER TABLE $tb MODIFY COLUMN ".$ed->post('n1')." ".$r_fel['Type']." AFTER ".$ed->post('n2'));
		}
		exit;
	}
	if($ed->post('idx','!e') && is_array($ed->post('idx'))) {//create index
		$idx= '`'.implode('`,`',$ed->post('idx')).'`';
		$idxn= implode('_',$ed->post('idx'));
		if($ed->post('primary','i')) {
			$q = $ed->con->query("ALTER TABLE $tb DROP PRIMARY KEY, ADD PRIMARY KEY($idx)");
			if(!$q) $ed->con->query("ALTER TABLE $tb ADD PRIMARY KEY($idx)");
		} elseif($ed->post('unique','i')) {
			$ed->con->query("ALTER TABLE $tb ADD UNIQUE KEY($idx)");
		} elseif($ed->post('index','i')) {
			$ed->con->query("ALTER TABLE $tb ADD INDEX($idx)");
		} elseif($ed->post('fulltext','i')) {
			$ed->con->query("ALTER TABLE $tb ADD FULLTEXT INDEX($idx)");
		}
		$ed->redir("10/$db/$tb",array('ok'=>"Successfully created"));
	}
	if(isset($ed->sg[3])) {//drop index
		if($ed->sg[3] == "PRIMARY") {
			$q_alt= $ed->con->query("ALTER TABLE `".$tb."` DROP PRIMARY KEY");
		} else {
			$q_key = $ed->con->query("SHOW KEYS FROM ".$tb);
			if($q_key) {
			foreach($q_key->fetch(2) as $r_key) {
			if($r_key['Key_name'] == $ed->sg[3]) $keys= $r_key['Key_name'];
			}
			$q_alt= $ed->con->query("ALTER TABLE `".$tb."` DROP INDEX ".$keys);
			}
		}
		if($q_alt) $ed->redir("10/$db/".$tb,array('ok'=>"Successfully dropped"));
		else $ed->redir("10/$db/".$tb,array('err'=>"Drop index failed"));
	}
	$ed->redir("5/".$db,array('err'=>"Action failed"));
break;

case "10": //table structure
	$ed->check(array(1,2));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	echo $head.$ed->menu($db, $tb, 1);
	echo $ed->form("9/$db/$tb")."<table><tr><th colspan='8'>TABLE STRUCTURE</th></tr><tr><th><input type='checkbox' onclick='toggle(this,\"idx[]\")' /></th><th class='dot'>FIELD</th><th class='dot'>TYPE</th><th class='dot'>NULL</th><th class='dot'>COLLATION</th><th class='dot'>DEFAULT</th><th class='dot'>EXTRA</th><th class='dot'>ACTIONS</th></tr><tbody id='allord'>";
	$q_fi= $ed->con->query("SHOW FULL FIELDS FROM ".$tb);
	$r_filds= $q_fi->num_row();
	$h=1;
	foreach($q_fi->fetch(2) as $r_fi) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg' id='".$r_fi['Field']."'><td><input type='checkbox' name='idx[]' value='".$r_fi['Field']."' /></td><td class='dot'>".$r_fi['Field']."</td><td class='dot'>".$r_fi['Type']."</td><td class='dot'>".$r_fi['Null']."</td>";
		echo "<td class='dot'>".($r_fi['Collation']!='NULL' ? $r_fi['Collation']:'')."</td>";
		echo "<td class='dot'>".$r_fi['Default']."</td><td class='dot'>".$r_fi['Extra']."</td><td class='dot'><a href='{$ed->path}12/$db/$tb/".$r_fi['Field']."'>change</a><a class='del' href='{$ed->path}13/$db/$tb/".$r_fi['Field']."'>drop</a><a href='{$ed->path}11/$db/$tb/".$r_fi['Field']."'>add</a>";
		if($r_filds>1){
		$up= "<a class='move up' title='Up'>&#9650;</a>";
		$down= "<a class='move down' title='Down'>&#9660;</a>";
		if($h == 1) echo $down;
		elseif($h == $r_filds) echo $up;
		else echo $up.$down;
		}
		echo "</td></tr>";
		++$h;
	}
	echo "</tbody><tr><td class='auto' colspan='8'><div class='left'><button type='submit' name='primary'>Primary</button><button type='submit' name='index'>Index</button><button type='submit' name='unique'>Unique</button><button type='submit' name='fulltext'>Fulltext</button></div><div class='link'><a href='{$ed->path}27/$db/$tb/analyze'>Analyze</a><a href='{$ed->path}27/$db/$tb/optimize'>Optimize</a><a href='{$ed->path}27/$db/$tb/check'>Check</a><a href='{$ed->path}27/$db/$tb/repair'>Repair</a></div></td></tr></table></form>
	<table><tr><th colspan='4'>TABLE INDEX</th></tr><tr><th class='dot'>KEY NAME</th><th class='dot'>FIELD</th><th class='dot'>TYPE</th><th class='dot'>ACTIONS</th></tr>";
	$q_idx= $ed->con->query("SHOW KEYS FROM ".$tb);
	if($q_idx->num_row()) {
	foreach($q_idx->fetch(2) as $r_idx) {
		if($r_idx['Index_type']=="BTREE") {
			if($r_idx['Non_unique']==1) $idxtyp="INDEX";
			elseif($r_idx['Key_name']=="PRIMARY") $idxtyp="PRIMARY";
			else $idxtyp="UNIQUE";
		}elseif($r_idx['Index_type']=="FULLTEXT") {
			$idxtyp="FULLTEXT";
		}
		$idxs[$r_idx['Key_name']]['type']=$idxtyp;
		$idxs[$r_idx['Key_name']]['column'][]=$r_idx['Column_name'];
	}
	if(count($idxs) > 0) {
	foreach($idxs as $idxnam=>$idxcol) {
	$bg=($bg==1)?2:1;
	echo "<tr class='r c$bg'><td class='dot'>".$idxnam."</td><td class='dot'>";
	foreach($idxcol['column'] as $col) echo $col."<br/>";
	echo "</td><td class='dot'>".$idxcol['type'];
	echo "</td><td class='dot'><a class='del' href='{$ed->path}9/$db/$tb/".$idxnam."'>drop</a></td></tr>";
	}
	}
	}
	echo "</table><table class='c1'><tr><td>Rename Table<br/>".$ed->form("9/$db/$tb")."<input type='text' name='rtab' /><br/><button type='submit'>Rename</button></form><br/>Copy Table to Database<br/>".$ed->form("9/$db/$tb")."<select name='copytab'>";
	$q_ldb = $ed->con->query("SHOW DATABASES");
	foreach($q_ldb->fetch(1) as $r_ldb) {
		echo "<option value='".$r_ldb[0]."'".($r_ldb[0]==$db?" selected":"").">".$r_ldb[0]."</option>";
	}
	echo "</select><br/><button type='submit'>Copy</button></form><br/>";
	$q_cll= $ed->con->query("SHOW TABLE STATUS FROM {$db} like '{$tb}'");
	$r_cll= $q_cll->fetch(2);
	$q_cl = $ed->con->query("SHOW COLLATION");
	echo "Change Table Collation<br/>".$ed->form("9/$db/$tb")."<select name='cll'><option value=''>&nbsp;</option>";
	foreach($q_cl->fetch(1) as $r_cl) {
		echo "<option value='".$r_cl[0]."'".($r_cl[0] == $r_cll[0]['Collation']?" selected":"").">".$r_cl[0]."</option>";
	}
	echo "</select><br/><button type='submit'>Change</button></form><br/>
	Change Table Engine<br/>".$ed->form("9/$db/$tb")."<select name='engs'>";
	$q_def= $ed->con->query("SELECT ENGINE FROM information_schema.TABLES WHERE `TABLE_SCHEMA`='$db' AND `TABLE_NAME`='$tb'")->fetch();
	$q_eng= $ed->con->query("SELECT ENGINE FROM information_schema.ENGINES WHERE ENGINE IS NOT NULL AND SUPPORT<>'NO'")->fetch(1);
	foreach($q_eng as $r_eng) {
		echo "<option value='".$r_eng[0]."'".($q_def[0]==$r_eng[0]?" selected":"").">".$r_eng[0]."</option>";
	}
	echo "</select><br/><button type='submit'>Change</button></form></td></tr></table>";
break;

case "11": //Add field
	$ed->check(array(1,2,3),array('redir'=>10));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$id= $ed->sg[3];
	if($ed->post('fi','!e') && $ed->post('ty','!e') && !is_numeric(substr($ed->post('fi'),0,1))) {
		$va=($ed->post('va','!e') ? "(".$ed->post('va').")" : "");
		$at=($ed->post('at')!=0 ? " ".$ed->post('at'):"");
		$def=($ed->post('de','!e') ? " default '".$ed->post('de')."' ":"");
		$clls=($ed->post('clls','!e') ? " collate ".$ed->post('clls'):"");
		$ex= ($ed->post('ex','!e') && $ed->post('ex',0)==1 ? " AUTO_INCREMENT PRIMARY KEY":"");
		$col=($ed->post('col')=="FIRST" ? " FIRST":" AFTER ".$ed->post('col'));
		$e= $ed->con->query("ALTER TABLE $tb ADD ".$ed->sanitize($ed->post('fi'))." ".$ed->post('ty').$va.$at." ".$ed->post('nc').$def.$clls.$ex.$col);
		if($e) $ed->redir("10/$db/".$tb,array('ok'=>"Successfully added"));
		else $ed->redir("10/$db/".$tb,array('err'=>"Add field failed"));
	} else {
		echo $head.$ed->menu($db,$tb,2).$ed->form("11/$db/$tb/$id").$stru.
		"<tr><td><input type='text' name='fi' /></td><td><select name='ty'>".$ed->fieldtypes()."</select></td><td><input type='text' name='va' /></td><td><select name='at'>";
		foreach($inttype as $ke=>$ar) {
		echo "<option value='$ke'>$ar</option>";
		}
		echo "</select></td>
		<td><select name='nc'><option value='NOT NULL'>NOT NULL</option><option value='NULL'>NULL</option></select></td>
		<td><input type='text' name='de' /></td><td><select name='clls'><option value=''>&nbsp;</option>";
		$q_cls = $ed->con->query("SHOW COLLATION");
		foreach($q_cls->fetch(1) as $r_cls) {
			echo "<option value='".$r_cls[0]."'>".$r_cls[0]."</option>";
		}
		echo "</select></td><td><input type='radio' name='ex[]' value='1' /></td>
		<td><select name='col'><option value='".$id."'>after: ".$id."</option><option value='FIRST'>first</option></select></td>
		</tr><tr><td colspan='9'><button type='submit'>Add</button></td></tr></table></form>";
	}
break;

case "12": //structure change
	$ed->check(array(1,2,3),array('redir'=>10));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	if($ed->post('fi','!e') && $ed->post('ty','!e') && !is_numeric(substr($ed->post('fi'),0,1))) {//structure update
		$fi=$ed->sanitize($ed->post('fi'));
		$fi_=$ed->post('fi_');
		$va= ($ed->post('va','e') ? "":"(".$ed->post('va','',1).")");
		$at= ($ed->post('at','e') ? "":" ".$ed->post('at'));
		$def=($ed->post('de','e') ? "":" default '".$ed->post('de')."'");
		$clls=($ed->post('clls','e') ? "":" collate ".$ed->post('clls'));
		if($ed->post('ex','!e') && $ed->post('ex',0)==1) {
			$ex=" AUTO_INCREMENT";
			$q_pri= $ed->con->query("SHOW KEYS FROM {$db}.".$tb);
			if($q_pri->num_row()) {
			foreach($q_pri->fetch(2) as $r_pri) {
				if($r_pri['Key_name'] != "PRIMARY" && $r_pri['Column_name'] != $fi_) {
				$ex .= " PRIMARY KEY";
				}
			}
			} else $ex .= " PRIMARY KEY";
		} else $ex="";
		$ok= $ed->con->query("ALTER TABLE $tb CHANGE ".$fi_." ".$ed->sanitize($ed->post('fi'))." ".$ed->post('ty').$va.$at." ".$ed->post('nc').$def.$clls.$ex);
		if($ok) {
		//replace field in view
		$q_vw = $ed->con->query("SELECT TABLE_NAME,VIEW_DEFINITION FROM information_schema.VIEWS WHERE `TABLE_SCHEMA`='$db'");
		if($q_vw->num_row()) {
		foreach($q_vw->fetch(1) as $r_vw) {
			if(strrpos($r_vw[1],"`$db`.`$tb`")==true) {
			$ed->con->query("DROP VIEW IF EXISTS ".$r_vw[0]);
			$ed->con->query("CREATE VIEW `".$r_vw[0]."` AS ".str_replace("`".$fi_."`", "`".$fi."`", $r_vw[1]));
			}
		}
		}
		//repalce field in trigger
		$q_tge = $ed->con->query("SHOW TRIGGERS FROM $db WHERE `Table`='$tb'");
		if($q_tge->num_row()) {
		foreach($q_tge->fetch(1) as $r_tge) {
			if($r_tge[2] == $tb) {
			$stt= preg_replace("/\b(".$fi_.")\b/i", $fi, $r_tge[3]);
			$ed->con->query("DROP TRIGGER IF EXISTS ".$db.".".$r_tge[0]);
			$ed->con->query("CREATE TRIGGER `".$r_tge[0]."` ".$r_tge[4]." ".$r_tge[1]." ON `".$r_tge[2]."` FOR EACH ROW ".$stt);
			}
		}
		}
		//replace field in procedure (function not depend by the table)
		$q_pf = $ed->con->query("SELECT db,name,param_list,body FROM mysql.proc WHERE db='$db'");
		if($q_pf->num_row()) {
		foreach($q_pf->fetch(1) as $r_pf) {
			if(strrpos($r_pf[3],$tb)==true) {
			$plist= str_replace("`".$fi_."`", "`".$fi."`", $r_pf[2]);
			$body = preg_replace("/\b".$fi_."\b/i", $fi, $r_pf[3]);
			$ed->con->query("UPDATE mysql.proc SET `param_list`='{$plist}', `body`='{$body}', `body_utf8`='{$body}' WHERE `db`='$db' AND `name`='".$r_pf[1]."'");
			}
		}
		}
		//replace field in event
		$q_evtn = $ed->con->query("SELECT db,name,body FROM mysql.event WHERE db='$db'");
		if($q_evtn->num_row()) {
		foreach($q_evtn->fetch(1) as $r_evtn) {
			if(strrpos($r_evtn[2],$tb)==true) {
			$bdy = preg_replace("/\b(".$fi_.")\b/i", $fi, $r_evtn[2]);
			$ed->con->query("UPDATE mysql.event SET body='$bdy',body_utf8='$bdy' WHERE db='$db' AND name='".$r_evtn[1]."'");
			}
		}
		}
		}
		$ed->redir("10/$db/".$tb,array('ok'=>"Successfully changed"));
	} else {//structure form
	echo $head.$ed->menu($db,$tb,2);
	echo $ed->form("12/$db/$tb/".$ed->sg[3]).$stru;
	$r_fe = $ed->con->query("SHOW FULL FIELDS FROM {$db}.{$tb} LIKE '".$ed->sg[3]."'")->fetch();
	$fe_type= preg_split("/[()]+/", $r_fe[1], -1, PREG_SPLIT_NO_EMPTY);
	echo "<tr><td><input type='hidden' name='fi_' value='".$r_fe[0]."'/><input type='text' name='fi' value=".$r_fe[0]." /></td>
	<td><select name='ty'>".$ed->fieldtypes(strtoupper($fe_type[0]))."</select></td>
	<td><input type='text' name='va' value=\"".(isset($fe_type[1])?$fe_type[1]:"")."\" /></td><td><select name='at'>";
	$fe_atr=substr($r_fe[1], strpos($r_fe[1], " ")+1);
	$big= strtoupper($fe_atr);
	foreach($inttype as $b=>$b2) echo "<option value='$b'".($b==$big ? " selected":"").">".$b2."</option>";
	echo "</select></td><td><select name='nc'>";
	$cc = array('NOT NULL','NULL');
	foreach ($cc as $c) echo("<option value='$c'".(($r_fe[3]=="YES" && $c=="NULL")?" selected":"").">$c</option>");
	echo "</select></td><td><input type='text' name='de' value='".$r_fe[5]."' /></td><td><select name='clls'><option value=''>&nbsp;</option>";
	$q_colls = $ed->con->query("SHOW COLLATION");
	foreach($q_colls->fetch(1) as $r_cl) {
		echo "<option value='".$r_cl[0]."'".($r_fe[2]==$r_cl[0] ?" selected":"").">".$r_cl[0]."</option>";
	}
	echo "</select></td><td><input type='radio' name='ex[]' value='1' ".($r_fe[6]=="auto_increment" ? "checked":"")." /></td>
	</tr><tr><td colspan='8'><button type='submit'>Change field</button></td></tr></table></form>";
	}
break;

case "13": //Drop field
	$ed->check(array(1,2,3),array('redir'=>10));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$fi= $ed->sg[3];
	//drop view if have field
	$q_vi = $ed->con->query("SHOW TABLE STATUS FROM $db");
	foreach($q_vi->fetch(2) as $r_vi) {
	if($r_vi['Comment']=='VIEW') {
	$q_sv= $ed->con->query("SHOW CREATE VIEW {$db}.".$r_vi['Name']);
	$r_sv= $q_sv->fetch();
	if(strpos($r_sv[1],"`$tb`.`$fi`")!==false) $ed->con->query("DROP VIEW {$db}.".$r_vi['Name']);
	}
	}
	$q_drofd = $ed->con->query("ALTER TABLE $tb DROP ".$fi);
	if($q_drofd) $ed->redir("10/$db/".$tb, array('ok'=>"Successfully deleted"));
	$ed->redir("10/$db/".$tb, array('err'=>"Field delete failed"));
break;

case "20": //table browse
	$ed->check(array(1,2));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$ed->con->query("SET NAMES utf8");
	$where=(!empty($_SESSION['_sqlsearch_'.$db.'_'.$tb])?" ".$_SESSION['_sqlsearch_'.$db.'_'.$tb] : "");
	//paginate
	$q_resul= $ed->con->query("SELECT COUNT(*) FROM ".$tb.$where)->fetch();
	$totalr= $q_resul[0];
	$totalpg= ceil($totalr/$step);
	if(empty($ed->sg[3])) {
		$pg = 1;
	} else {
		$pg= $ed->sg[3];
		$ed->check(array(1,4),array('pg'=>$pg,'total'=>$totalpg,'redir'=>"20/$db/$tb"));
	}
	$q_vic = $ed->con->query("SHOW TABLE STATUS FROM $db like '".$tb."'")->fetch();//17-comment=view
	echo $head.$ed->menu($db, ($q_vic[17]=='VIEW'?'':$tb), 1,($q_vic[17]=='VIEW'?array('view',$tb):''));
	echo "<table><tr>";
	if($q_vic[17]!='VIEW'){
	echo "<th>ACTIONS</th>";
	}
	$q_bro= $ed->con->query("SHOW FIELDS FROM ".$tb);
	$r_cl= $q_bro->num_row();
	$coln= array();//field
	$colt= array();//type
	$select= array();
	foreach($q_bro->fetch(2) as $r_brw) {
		$select[] = (stristr($r_brw['Type'],'bit')?"BIN(".$r_brw['Field']." + 0) AS ".$r_brw['Field']:'`'.$r_brw['Field'].'`');
		$coln[]= $r_brw['Field'];
		$colt[]= $r_brw['Type'];
		echo "<th>".$r_brw['Field']."</th>";
	}
	echo "</tr>";
	$offset = ($pg - 1) * $step;
	$q_res= $ed->con->query("SELECT ".implode(",",$select)." FROM {$tb}{$where} LIMIT $offset, $step");
	foreach($q_res->fetch(1) as $r_rw) {
		$bg=($bg==1)?2:1;
		$nu = $coln[0];
		$rw0= base64_encode($r_rw[0]);
		echo "<tr class='r c$bg'>";
		if($q_vic[17]!='VIEW'){
		echo "<td><a href='".$ed->path."22/$db/$tb/$nu/$rw0'>Edit</a><a class='del' href='".$ed->path."23/$db/$tb/$nu/$rw0'>Delete</a></td>";
		}
		for($i=0;$i<$r_cl;$i++) {
			$val= $ed->clean($r_rw[$i]);
			echo "<td class='dot'>";
			if(stristr($colt[$i],"blob") == true && !in_array($db,$ed->deny)) {
				$le= strlen($r_rw[$i]);
				echo "[blob] ";
				if($le > 4) {
				echo "<a href='".$ed->path."33/$db/$tb/$nu/$rw0/".$coln[$i]."'>".number_format(($le/1024),2)." KB</a>";
				} else {
				echo number_format(($le/1024),2)." KB";
				}
			} elseif(strlen($val) > 100) {
				echo substr($val,0,100)."(...)";
			} else {
				echo stripslashes($val);
			}
			echo "</td>";
		}
		echo "</tr>";
	}
	echo "</table>".$ed->pg_number($pg, $totalpg);
break;

case "21": //table insert
	$ed->check(array(1,2));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$q_col= $ed->con->query("SHOW COLUMNS FROM ".$tb);
	$coln= array();//field
	$colt= array();//type
	$colu= array();//null
	foreach($q_col->fetch(2) as $r_brw) {
		$coln[]= $r_brw['Field'];
		$colt[]= $r_brw['Type'];
		$colu[]= $r_brw['Null'];
	}
	if($ed->post('save','i') || $ed->post('save2','i')) {
		$q_res= $ed->con->query("SELECT * FROM ".$tb);
		$nrcol= $q_res->num_col();
		$qr1="INSERT INTO $tb (";
		$qr2="";
		$qr3="VALUES(";
		$qr4="";
		$n= 0;
		while($n<$nrcol) {
			$qr2.=$coln[$n].",";
			if(stristr($colt[$n],"blob") == true) {
				if(!empty($_FILES["r".$n]['tmp_name'])) {
				$qr4.= "'".addslashes(file_get_contents($_FILES["r".$n]['tmp_name']))."',";
				} else {
				$qr4.= "'',";
				}
			} elseif(stristr($colt[$n],'bit')==true) {
				preg_match("/\((.*)\)/", $colt[$n], $mat);
				$qr4.= "b'".(($mat[1] > 1)?$ed->post('r'.$n):$ed->post('r'.$n,0))."',";
			} else {
				if(!empty($_FILES['r'.$n]['tmp_name'])) {
				$blb = addslashes(file_get_contents($_FILES['r'.$n]['tmp_name']));
				$qr4.= "'{$blb}',";
				} else {
				$qr4.= (($ed->post('r'.$n,'e') && $colu[$n]=='YES')? "NULL":"'".html_entity_decode($ed->post('r'.$n))."'").",";
				}
			}
			++$n;
		}
		$qr2=substr($qr2,0,-1).") ";
		$qr4=substr($qr4,0,-1).")";
		$q_rins = $ed->con->query($qr1.$qr2.$qr3.$qr4);
		if($ed->post('save2','i')) $rr=21;
		else $rr=20;
		if($q_rins) $ed->redir("$rr/$db/$tb",array('ok'=>"Successfully inserted"));
		else $ed->redir("$rr/$db/$tb",array('err'=>"Insert failed"));
	} else {
		echo $head.$ed->menu($db, $tb, 1).$ed->form("21/$db/$tb",1)."<table><caption>Insert Row</caption>";
		$q_res= $ed->con->query("SELECT * FROM ".$tb);
		$r_col= $q_res->num_col();
		$j=0;
		while($j<$r_col) {
			echo "<tr><td>".$coln[$j]."</td><td>";
			if(stristr($colt[$j],"enum") == true OR stristr($colt[$j],"set") == true) {//enum
			$enums = explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2", $colt[$j]));
			echo "<select name='r{$j}'>";
			foreach($enums as $enm) {
			echo "<option value='{$enm}'>".$enm."</option>";
			}
			echo "</select>";
			} elseif(substr($colt[$j],0,3)=='bit') {//bit
				preg_match("/\((.*)\)/", $colt[$j], $mat);
				if($mat[1] > 1) {
				echo "<input type='text' name='r{$j}' />";
				} else {
				foreach($bbs as $kj=>$bb) {
				echo "<input type='radio' name='r{$j}[]' value='$kj' /> $bb ";
				}
				}
			} elseif(stristr($colt[$j],"blob") == true && !in_array($db,$ed->deny)) {//blob
			echo "<input type='file' name='r{$j}'/>";
			} elseif(stristr($colt[$j],"text") == true) {//text
			echo "<textarea name='r{$j}'></textarea>";
			} else {
			echo "<input type='text' name='r{$j}' />";
			}
			++$j;
		}
		echo "<tr><td class='c1'><button type='submit' name='save'>Save</button></td><td class='c1'><button type='submit' name='save2'>Save &amp; Insert Next</button></td></tr></table></form>";
	}
break;

case "22": //table edit row
	$ed->check(array(1,2,3),array('redir'=>'20'));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$nu= $ed->sg[3];
	$id= base64_decode($ed->sg[4]);
	$q_col= $ed->con->query("SHOW COLUMNS FROM ".$tb);
	$coln= array();//field
	$colt= array();//type
	$colu= array();//null
	$select= array();
	foreach($q_col->fetch(2) as $r_brw) {
		$select[] = (stristr($r_brw['Type'],'bit')?"BIN(".$r_brw['Field']." + 0) AS ".$r_brw['Field']:'`'.$r_brw['Field'].'`');
		$coln[]= $r_brw['Field'];
		$colt[]= $r_brw['Type'];
		$colu[]= $r_brw['Null'];
	}
	if($ed->post('edit','i') && $id!=null) {//update
	$q_re2= $ed->con->query("SELECT * FROM ".$tb);
	$r_co= $q_re2->num_col();
		$qr1="UPDATE $tb SET ";
		$qr2="";
		for ($p=0;$p<$r_co;$p++) {
			if(stristr($colt[$p],"blob") == true) {
				if(!empty($_FILES["te".$p]['tmp_name'])) {
				$blb = addslashes(file_get_contents($_FILES["te".$p]['tmp_name']));
				$qr2.= $coln[$p]."='".$blb."',";
				}
			} elseif(stristr($colt[$p],'bit') == true) {
				preg_match("/\((.*)\)/", $colt[$p], $mat);
				$qr2.= $coln[$p]."=b'".(($mat[1] > 1)?$ed->post("te".$p):$ed->post("te".$p,0))."',";
			} else {
				$qr2.= $coln[$p]."=".(($ed->post('te'.$p,'e') && $colu[$p]=='YES')? "NULL":"'".html_entity_decode($ed->post('te'.$p))."'").",";
			}
		}
		$qr2=substr($qr2,0,-1);
		$qr3=" WHERE $nu='$id' LIMIT 1";
		$q_upd = $ed->con->query($qr1.$qr2.$qr3);
		if($q_upd) $ed->redir("20/{$db}/".$tb,array('ok'=>"Successfully updated"));
		else $ed->redir("20/{$db}/".$tb,array('err'=>"Update failed"));
	} else {//edit form
		$q_flds = $ed->con->query("SHOW COLUMNS FROM ".$tb);
		$r_fnr = $q_flds->num_row();
		$q_rst = $ed->con->query("SELECT ".implode(",",$select)." FROM `$tb` WHERE `$nu`='$id'");
		if($q_rst->num_row() < 1) $ed->redir("20/$db/".$tb,array('err'=>"Edit failed"));
		$r_rx = $q_rst->fetch();
		echo $head.$ed->menu($db, $tb, 1).$ed->form("22/$db/$tb/$nu/".base64_encode($r_rx['0']),1)."<table><caption>Edit Row</caption>";
		for ($k=0;$k<$r_fnr;$k++) {
			echo "<tr><td>".$coln[$k]."</td><td>";
			if(stristr($colt[$k],"enum") == true OR stristr($colt[$k],"set") == true) {//enum
				$enums = explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2", $colt[$k]));
				echo "<select name='te{$k}'>";
				foreach($enums as $enm) {
				echo "<option value='{$enm}'".($r_rx[$k]==$enm ? " selected":"").">".$enm."</option>";
				}
				echo "</select>";
			} elseif(stristr($colt[$k],'bit') == true) {//bit
				preg_match("/\((.*)\)/", $colt[$k], $mat);
				if($mat[1] > 1) {
				echo "<input type='text' name='te{$k}' value='".$r_rx[$k]."' />";
				} else {
				foreach($bbs as $kk=>$bb) {
				echo "<input type='radio' name='te{$k}[]' value='$kk'".($r_rx[$k]==$kk ? " checked":"")." /> $bb ";
				}
				}
			} elseif(stristr($colt[$k],"blob") == true && !in_array($db,$ed->deny)) {//blob
			echo "[blob] ".number_format((strlen($r_rx[$k])/1024),2)." KB<br/><input type='file' name='te{$k}'/>";
			} elseif(stristr($colt[$k],"text") == true) {//text
			echo "<textarea name='te{$k}'>".html_entity_decode($r_rx[$k])."</textarea>";
			} else {
			echo "<input type='text' name='te{$k}' value='".stripslashes($ed->clean($r_rx[$k]))."' />";
			}
			echo "</td></tr>";
		}
	echo "<tr class='c1'><td><a class='del link' href='".$ed->path."23/$db/$tb/$nu/".$ed->sg[4]."'>Delete</a></td><td><button type='submit' name='edit'>Update</button></td></tr></table></form>";
	}
break;

case "23": //table delete row
	$ed->check(array(1,2,3),array('redir'=>'20'));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$nu= $ed->sg[3];
	$id= base64_decode($ed->sg[4]);
	$q_delro = $ed->con->query("DELETE FROM `$tb` WHERE `$nu`='$id' LIMIT 1");
	if($q_delro->last()) $ed->redir("20/$db/".$tb,array('ok'=>"Successfully deleted"));
	else $ed->redir("20/$db/$tb",array('err'=>"Delete row failed"));
break;

case "24": //search
	$ed->check(array(1,2));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	unset($_SESSION['_sqlsearch_'.$db.'_'.$tb]);
	if(!empty($ed->sg[3]) && $ed->sg[3]=='reset') {
	$ed->redir("20/$db/$tb",array('ok'=>"Reset search"));
	}
	$q_se= $ed->con->query("SHOW COLUMNS FROM ".$tb)->fetch(2);
	$cond1=array('=','&lt;','&gt;','&lt;=','&gt;=','!=','LIKE','NOT LIKE','REGEXP','NOT REGEXP');
	$cond2=array('BETWEEN','NOT BETWEEN');
	$cond3=array('IN','NOT IN');
	$cond4=array('IS NULL','IS NOT NULL');
	$cond= array_merge($cond1,$cond2,$cond3,$cond4);
	//post
	if($ed->post('search','i')) {
	$search_cond=array();
	foreach($q_se as $r_se) {
		if($ed->post($r_se['Field'],'!e') || in_array($ed->post('cond__'.$r_se['Field']),$cond4)) {
		$fd= $r_se['Field'];
		$cd= $ed->post('cond__'.$fd);
		$po= $ed->post($fd);
		if(in_array($cd,$cond2)) {
		$sl= preg_split("/[,]+/", $po);
		$sl2= (!empty($sl[1])?$sl[1]:$sl[0]);
		$search_cond[]= $fd." ".$cd." '".$sl[0]."' AND '".$sl2."'";
		}
		elseif(in_array($cd,$cond3)) $search_cond[]= $fd." ".$cd." ('".$po."')";
		elseif(in_array($cd,$cond4)) $search_cond[]= $fd." ".$cd;
		else $search_cond[]= $fd." ".html_entity_decode($ed->post('cond__'.$fd))." '".$po."'";
		}
	}
	$se_str = ($search_cond?"WHERE ":"").implode(" AND ", $search_cond).($ed->post('order_field','!e')?" ORDER BY ".$ed->post('order_field')." ".$ed->post('order_ord')." ":"");
	$_SESSION['_sqlsearch_'.$db.'_'.$tb]= $se_str;
	$ed->redir("20/$db/$tb");
	}

	echo $head.$ed->menu($db,$tb,1).$ed->form("24/$db/$tb")."<table><caption>Search</caption>";
	$conds="";
	foreach($cond as $cnd) $conds .= "<option value='".$cnd."'>".$cnd."</option>";
	$fields="<option value=''>&nbsp;</option>";
	foreach($q_se as $r_se) {
	$fl=$r_se['Field'];
	$fields .= "<option value='$fl'>".$fl."</option>";
	echo "<tr><td>".$fl."</td><td><select name='cond__".$fl."'>".$conds."</select></td><td><input type='text' name='".$fl."'/></td></tr>";
	}
	echo "<tr class='c1'><td>ORDER</td><td><select name='order_field'>".$fields."</select></td><td><select name='order_ord'><option value='ASC'>ASC</option><option value='DESC'>DESC</option></select></td></tr>
	<tr><td class='c1' colspan='3'><button type='submit' name='search'>Search</button></td></tr></table></form>";
break;

case "25": //table empty
	$ed->check(array(1,2));
	$ed->con->query("TRUNCATE TABLE ".$ed->sg[2]);
	$ed->redir("20/".$ed->sg[1]."/".$ed->sg[2],array('ok'=>"Table is empty"));
break;

case "26": //table drop
	$ed->check(array(1,2));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$ed->con->query("DROP TABLE ".$tb);
	//drop view
	$q_rw = $ed->con->query("SELECT TABLE_SCHEMA, TABLE_NAME, VIEW_DEFINITION FROM information_schema.views WHERE `TABLE_SCHEMA`='$db'");
	if($q_rw->num_row()) {
	foreach($q_rw->fetch(2) as $r_rw) {
		$q = $ed->con->query($r_rw['VIEW_DEFINITION']);
		if(!$q) $ed->con->query("DROP VIEW ".$r_rw['TABLE_NAME']);
	}
	}
	//drop procedure (function not depend by the table)
	$q_rp = $ed->con->query("SELECT name, body FROM mysql.proc WHERE `db`='$db' AND `type`='PROCEDURE'");
	if($q_rp) {
	foreach($q_rp->fetch(2) as $r_rp) {
		if(preg_match('/\b'.$tb.'\b/',$r_rp['body'])) $ed->con->query("DROP PROCEDURE ".$r_rp['name']);
	}
	}
	//drop event
	$q_evn = $ed->con->query("SELECT EVENT_NAME,EVENT_DEFINITION FROM information_schema.events WHERE `EVENT_SCHEMA`='$db'");
	foreach($q_evn->fetch(2) as $r_evn) {
		if(preg_match('/\b'.$tb.'\b/',$r_evn['EVENT_DEFINITION'])) $ed->con->query("DROP EVENT {$db}.".$r_evn['EVENT_NAME']);
	}
	$ed->redir("5/".$db,array('ok'=>"Successfully dropped"));
break;

case "27": //optimize, analyze, check, repair
	$ed->check(array(1,2));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$op= $ed->sg[3];
	$ops= array('optimize','analyze','check','repair');
	if(in_array($db,$ed->deny)) $ed->redir("10/$db/$tb",array('err'=>"Action restricted on this table"));
	if(!empty($op) && in_array($op, $ops)) {
	$q_op= $ed->con->query($op." TABLE ".$tb);
	if($op == 'check' || $op == 'repair') {
		$r_op= $q_op->fetch();
		if($r_op[3]=='OK') $ed->redir("10/$db/$tb",array('ok'=>"Successfully {$op}ed"));
		else $ed->redir("10/$db/$tb",array('err'=>$r_op[3]));
	}
	$ed->redir("10/$db/$tb",array('ok'=>"Successfully {$op}d"));
	} else $ed->redir("10/$db/$tb",array('err'=>"Action {$op} failed"));
break;

case "30"://import
	$ed->check(array(1));
	$db= $ed->sg[1];
	$ed->con->query("SET NAMES utf8");
	$out="";
	$q=0;
	set_time_limit(0);
	if($ed->post()) {
		$e='';
		$rgex ="~^\xEF\xBB\xBF|^\xFE\xFF|^\xFF\xFE|DELIMITER.*?[^ ]|(\#|--).*|(\/\*).*(\*\/;*)|([\$].*[^\$])|(?-m)\(([^)]*\)*(\"*.*\")*('*.*'))(*SKIP)(*F)|(?s)(BEGIN.*?END)(*SKIP)(*F)|(?<=;)(?![ ]*$)~im";
		if($ed->post('qtxt','!e')) {//in textarea
			$e= preg_split($rgex, $ed->post('qtxt','',1), -1, PREG_SPLIT_NO_EMPTY);
		} elseif($ed->post('send','i') && $ed->post('send') == "ja") {//from file
			if(empty($_FILES['importfile']['tmp_name'])) {
			$ed->redir("5/$db",array('err'=>"No file to upload"));
			} else {
			$tmp= $_FILES['importfile']['tmp_name'];
			$file= pathinfo($_FILES['importfile']['name']);
			$ext= strtolower($file['extension']);
			if($ext == 'sql') {//sql file
				$fi= $ed->utf(file_get_contents($tmp));
				$e= preg_split($rgex, $fi, -1, PREG_SPLIT_NO_EMPTY);
			} elseif($ext == 'csv') {//csv file
				$e= $ed->imp_csv($file['filename'], $tmp);
			} elseif($ext == 'json') {//json file
				$e= $ed->imp_json($file['filename'], $tmp);
			} elseif($ext == 'gz') {//gz file
				if(($fgz = fopen($tmp, 'r')) !== FALSE) {
					if(@fread($fgz, 3) != "\x1F\x8B\x08") {
					$ed->redir("5/$db",array('err'=>"Not a valid GZ file"));
					}
					fclose($fgz);
				}
				if(@function_exists('gzopen')) {
					$gzfile = @gzopen($tmp, 'rb');
					if (!$gzfile) {
					$ed->redir("5/$db",array('err'=>"Open GZ failed"));
					}
					$e='';
					while (!gzeof($gzfile)) {
					$e .= gzgetc($gzfile);
					}
					gzclose($gzfile);
					$entr= pathinfo($file['filename']);
					$e_ext= $entr['extension'];
					if($e_ext == 'sql') $e= preg_split($rgex, $ed->utf($e), -1, PREG_SPLIT_NO_EMPTY);
					elseif($e_ext =='csv') $e= $ed->imp_csv($entr['filename'], $e);
					elseif($e_ext =='json') $e= $ed->imp_json($entr['filename'], $e);
					else $ed->redir("5/$db",array('err'=>"Disallowed extension"));
				} else {
					$ed->redir("5/$db",array('err'=>"Open GZ failed"));
				}
			} elseif($ext == 'zip') {//zip file
				if(($fzip = fopen($tmp, 'r')) !== FALSE) {
					if(@fread($fzip, 4) != "\x50\x4B\x03\x04") {
					$ed->redir("5/$db",array('err'=>"Not a valid ZIP file"));
					}
					fclose($fzip);
				}
				$e='';
				$zip = zip_open($tmp);
				if(is_resource($zip)) {
					$zip_entry = zip_read($zip);
					if(zip_entry_open($zip, $zip_entry, "rb")) {
					$zentry= zip_entry_name($zip_entry);
					if($file['filename']==$zentry) {
					$buf= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
					preg_match("/^(.*)\.(sql|csv|json)$/i",$zentry,$zn);
					if(!empty($zn[2]) && $zn[2]=='sql') $e= preg_split($rgex, $ed->utf($buf), -1, PREG_SPLIT_NO_EMPTY);
					elseif(!empty($zn[2]) && $zn[2]=='csv') $e= $ed->imp_csv($zn[1], $buf);
					elseif(!empty($zn[2]) && $zn[2]=='json') $e= $ed->imp_json($zn[1], $buf);
					else $ed->redir("5/$db",array('err'=>"Disallowed extension"));
					zip_entry_close($zip_entry);
					}
					}
					zip_close($zip);
				}
			} else {
				$ed->redir("5/$db",array('err'=>"Disallowed extension"));
			}
			}
		} else {
			$ed->redir("5/$db",array('err'=>"Query failed"));
		}
		if(is_array($e)) {
			$ed->con->begin();
			foreach($e as $qry) {
				$qry= trim($qry);
				if(!empty($qry)) {
					$exc = $ed->con->query($qry);
					$op= array('insert','update','delete');
					$p_qry= strtolower(substr($qry,0,6));
					if(in_array($p_qry, $op) && $exc) $exc= $exc->last();
					if($exc) ++$q;
					else $out .= "<p><b>FAILED!</b> $qry</p>";
				}
			}
			$ed->con->commit();
		}
	}
	echo $head.$ed->menu($db)."<div class='col2'><p>Successfully executed: <b>".$q." quer".($q>1?'ies':'y')."</b></p>".$out;
break;

case "31": //export form
	$ed->check(array(1));
	$db= $ed->sg[1];
	$q_tables= $ed->con->query("SHOW TABLES FROM ".$db);
	if($q_tables->num_row()) {
	echo $head.$ed->menu($db,'',2).$ed->form("32/$db")."<div class='dw'><h3 class='l1'>Export</h3><div><h3>Select table(s)</h3>
	<p><input type='checkbox' onclick='selectall(this,\"tables\")' /> Select/Deselect</p>
	<select class='he' id='tables' name='tables[]' multiple='multiple'>";
	foreach($q_tables->fetch(1) as $r_tts) {
	echo "<option value='".$r_tts[0]."'>".$r_tts[0]."</option>";
	}
	echo "</select></div><div>
	<h3><input type='checkbox' onclick='toggle(this,\"fopt[]\")' /> Options</h3>";
	$opts = array('structure'=>'Structure','data'=>'Data','cdb'=>'Create DB','auto'=>'Auto Increment','drop'=>'Drop if exist','ifnot'=>'If not exist','trigger'=>'Triggers','procfunc'=>'Routines','event'=>'Events');
	foreach($opts as $k => $opt) {
	echo "<p><input type='checkbox' name='fopt[]' value='{$k}'".($k=='structure' ? ' onclick="opt()" checked':'')." /> ".$opt."</p>";
	}
	echo "</div><div><h3>File format</h3>";
	$ffo = array('sql'=>'SQL','csv1'=>'CSV,','csv2'=>'CSV;','json'=>'JSON','xls'=>'Excel Spreadsheet','doc'=>'Word 2000');
	foreach($ffo as $k => $ff) {
	echo "<p><input type='radio' name='ffmt[]' value='{$k}'".($k=='sql' ? ' checked':'')." /> {$ff}</p>";
	}
	echo "</div><div><h3>File type</h3>";
	$fty = array('plain'=>'Plain','gzip'=>'GZ','zip'=>'Zip');
	foreach($fty as $k => $ft) {
	echo "<p><input type='radio' name='ftype[]' value='{$k}'".($k=='plain' ? ' checked':'')." /> {$ft}</p>";
	}
	echo "</div><div><button type='submit' name='exp'>Export</button></div></div></form>";
	} else {
	$ed->redir("5/".$db,array("err"=>"No export empty DB"));
	}
break;

case "32": //export
	if($ed->post('exp','i')) {
	$ed->check(array(1));
	$db= $ed->sg[1];
	$tbs= array();
	$vws= array();
	if($ed->post('tables')=='') {
		$ed->redir("31/".$db,array('err'=>"You didn't select any table"));
	} else {//selected tables
		$tabs = $ed->post('tables');
		foreach($tabs as $tab) {
			$r_com = $ed->con->query("SHOW TABLE STATUS FROM {$db} like '{$tab}'")->fetch();
			if($r_com[17] == 'VIEW') {
			array_push($vws, $r_com[0]);
			} else {
			array_push($tbs, $r_com[0]);
			}
		}
	}
	//check export options
	if($ed->post('fopt')=='') {
		$ed->redir("31/".$db,array('err'=>"You didn't select any option"));
	} else {
		$fopt=$ed->post('fopt');
	}
	$ftype= $ed->post('ftype');
	$ffmt= $ed->post('ffmt');
	if($ffmt[0]=='sql') {//sql format
		$ffty= "text/plain"; $ffext= ".sql"; $fname= $db.$ffext;
		$sql="-- EdMyAdmin SQL Dump\n-- version ".$version."\n\n-- ".date('Y-m-d H:i:s')."\n\n";
		if(in_array('cdb',$fopt) && in_array('structure',$fopt)) {//check option create db
			$sql .= "CREATE DATABASE ";
			if(in_array('ifnot',$fopt)) {//check option if not exist
			$sql .= "IF NOT EXISTS ";
			}
			$sql .= $db.";\nUse $db;\n\n";
		}
		foreach($tbs as $tb) {
			$r_st= $ed->con->query("SHOW TABLE STATUS FROM {$db} like '{$tb}'")->fetch();
			if(in_array('structure',$fopt)) {//structure
				if(in_array('drop',$fopt)) {//check option drop
					$sql .= "\nDROP TABLE IF EXISTS `$tb`;";
				}
				$q_ex= $ed->con->query("SHOW FULL FIELDS FROM ".$tb);
				$ifnot='';
				if(in_array('ifnot',$fopt)) {//check option if not exist
					$ifnot .= "IF NOT EXISTS ";
				}
				$sq="\nCREATE TABLE ".$ifnot."`".$tb."` (";
				foreach($q_ex->fetch(2) as $r_ex) {
					$trans = array("PRI" => "PRIMARY KEY","UNI"=>"UNIQUE KEY","MUL"=>"KEY");
					$nul=($r_ex['Null']=='YES' ? "NULL" : "NOT NULL");
					$def=($r_ex['Default']!='' ? " default '".$r_ex['Default']."'" : "");
					$clls=(($r_ex['Collation']!='' && $r_ex['Collation']!='NULL' && $r_ex['Collation']!=$r_st[14]) ? " COLLATE '".$r_ex['Collation']."'" : "");
					$xtr=($r_ex['Extra']!='' ? " ".$r_ex['Extra'] : "");
					$sq.="\n `".$r_ex['Field']."` ".$r_ex['Type']." ".$nul.$clls.
					$def.$xtr.",";
				}
				$idx1= array();$idx2= array();$idx3= array();$idx4= array();
				$q_sidx= $ed->con->query("SHOW KEYS FROM ".$tb);
				foreach($q_sidx->fetch(2) as $r_sidx) {
				if($r_sidx['Key_name']=='PRIMARY') $idx1[]=$r_sidx['Column_name'];
				elseif($r_sidx['Index_type']=='FULLTEXT') $idx4[$r_sidx['Key_name']][]= $r_sidx['Column_name'];
				elseif($r_sidx['Non_unique']==1) $idx2[$r_sidx['Key_name']][]= $r_sidx['Column_name'];
				elseif($r_sidx['Non_unique']==0) $idx3[$r_sidx['Key_name']][]= $r_sidx['Column_name'];
				}
				$sq.= (count($idx1) > 0 ? "\n PRIMARY KEY(`".implode("`,`",$idx1)."`),":"");
				foreach($idx2 as $k2=>$q2) {
				if(is_array($q2)) $sq.="\n KEY `".$k2."` (`".implode("`,`",$q2)."`),";
				else $sq.="\n KEY `".$k2."` (`".$q2."`),";
				}
				foreach($idx3 as $k3=>$q3) {
				if(is_array($q3)) $sq.="\n UNIQUE KEY `".$k3."` (`".implode("`,`",$q3)."`),";
				else $sq.="\n UNIQUE KEY `".$k3."` (`".$q3."`),";
				}
				foreach($idx4 as $k4=>$q4) {
				if(is_array($q4)) $sq.="\n FULLTEXT INDEX `".$k4."` (`".implode("`,`",$q4)."`),";
				else $sq.="\n FULLTEXT INDEX `".$k4."` (`".$q4."`),";
				}
				$sql.= substr($sq,0,-1)."\n)";
				$co = ($r_st[17]=='' ? "":" COMMENT='".$r_st[17]."'");
				$auto = (in_array('auto',$fopt) ? " AUTO_INCREMENT=".$r_st[10] : "");//check auto option
				$sql.= ($r_st[17]!='VIEW' ? " ENGINE=".$r_st[1]." DEFAULT CHARSET=".strtok($r_st[14],'_').$co.$auto:"").";\n";
			}//end structure

			if(in_array('data',$fopt)) {//check option data
				$q_fil= $ed->con->query("SHOW FIELDS FROM ".$tb);
				$cols= $q_fil->num_row();
				$r_fil=$q_fil->fetch(1);
				$q_rx= $ed->con->query("SELECT * FROM ".$tb);
				if($q_rx->num_row()) {
					if($r_st[17] != 'VIEW') {
					$sql.="\n";
					foreach($q_rx->fetch(1) as $r_rx) {
						$ins="INSERT INTO `".$tb."` VALUES (";
						$inn="";
						for($e=0;$e<$cols;$e++) {
							$bi = $r_fil[$e][1];//blob
							if(stristr($bi,"blob") == true) {
								if(empty($r_rx[$e])) {
								$inn .= "'', ";
								} elseif(strpos($r_rx[$e], "\0")===true) {
								$inn .= "0x".bin2hex($r_rx[$e]).", ";
								} else {
								$inn .= "'".addslashes($r_rx[$e])."', ";
								}
							} elseif(is_numeric($r_rx[$e])){
							$inn .= $r_rx[$e].", ";
							} else {
							$inn .= "'".($r_rx[$e])."', ";
							}
						}
						$ins.=substr($inn,0,-2);
						$sql.=$ins.");\n";
					}
					$sql.= "\n";
					}
				}
			}//end option data
		}

		if($vws != '') {//export views
		foreach($vws as $vw) {
			$q_rw = $ed->con->query("SHOW CREATE VIEW ".$vw);
			if($q_rw->num_row()) {
			if(in_array('drop',$fopt)) {//check option drop
			$sql .= "\nDROP VIEW IF EXISTS `$vw`;\n";
			}
			foreach($q_rw->fetch(1) as $r_rr) {
			$sql .= $r_rr[1].";\n";
			}
			$sql .= "\n";
			}
		}
		}
		$sqs='';
		if(in_array('trigger',$fopt)) {//check option trigger
			$q_trg=$ed->con->query("SELECT TRIGGER_NAME,ACTION_TIMING,EVENT_MANIPULATION,EVENT_OBJECT_TABLE,ACTION_STATEMENT FROM information_schema.triggers WHERE TRIGGER_SCHEMA='".$db."'");
			if($q_trg->num_row()) {
			$sqs.="\n";
			foreach($q_trg->fetch(1)as $r_row) {
				if(in_array($r_row[3], $tbs)) {
				if(in_array('drop',$fopt)) {//check option drop
				$sqs .= "DROP TRIGGER IF EXISTS `".$r_row[0]."`;\n";
				}
				$sqs .= "CREATE TRIGGER `".$r_row[0]."` ".$r_row[1]." ".$r_row[2]." ON `".$r_row[3]."` FOR EACH ROW\n".$r_row[4]."$$\n\n";
				}
			}
			}
		}
		if(in_array('procfunc',$fopt)) {//check option proc
			$q_pr = $ed->con->query("SELECT ROUTINE_TYPE, ROUTINE_NAME FROM information_schema.routines WHERE ROUTINE_SCHEMA='".$db."'");
			if($q_pr->num_row()) {
			$sqs.="\n";
			foreach($q_pr->fetch(1) as $r_px) {
				if(in_array('drop',$fopt)) {//check option drop
				$sqs .= "DROP ".$r_px[0]." IF EXISTS `".$r_px[1]."`;\n";
				}
				$q_rs = $ed->con->query("SHOW CREATE ".$r_px[0]." ".$r_px[1]);
				foreach($q_rs->fetch(1) as $r_rs) {
				$sqs .= $r_rs[2]."$$\n\n";
				}
			}
			}
		}
		if(in_array('event',$fopt)) {//check option event
			$q_eev= $ed->con->query("SELECT EVENT_NAME FROM information_schema.EVENTS WHERE `EVENT_SCHEMA`='$db'");
			if($q_eev->num_row()) {
			$sqs.="\n";
			foreach($q_eev->fetch(1) as $r_eev) {
				if(in_array('drop',$fopt)) {//check option drop
				$sqs .= "DROP EVENT IF EXISTS `".$r_eev[0]."`;\n";
				}
				$q_rev = $ed->con->query("SHOW CREATE EVENT ".$r_eev[0])->fetch(1);
				foreach($q_rev as $r_rev) {
				$sqs .= $r_rev[3]."$$\n\n";
				}
			}
			}
		}
		$sql .= (trim($sqs)!=''?"\nDELIMITER $$\n".$sqs."DELIMITER ;\n":"");
	} elseif($ffmt[0]=='csv1' || $ffmt[0]=='csv2') {//csv format
		$tbs= array_merge($tbs, $vws);
		$ffty= "text/csv"; $ffext= ".csv"; $fname= $db.$ffext;
		$sql= array();
		if(count($tbs)==1) {
			$tbs= array($tbs[0]);
			$fname= $tbs[0].$ffext;
		}
		$sign=($ffmt[0]=='csv1'?',':';');
		if(empty($tbs[0])) $ed->redir("31/".$db,array('err'=>"Select a table/view"));
		foreach($tbs as $tb) {
			$sq='';
			$q_csv= $ed->con->query("SHOW FIELDS FROM ".$tb)->fetch(2);
			foreach($q_csv as $r_csv) $sq.= $r_csv['Field'].$sign;
			$sq=substr($sq,0,-1)."\n";
			$q_rs=$ed->con->query("SELECT * FROM ".$tb)->fetch(1);
			foreach($q_rs as $r_rs) {
				foreach($r_rs as $r_r) $sq.= (is_numeric($r_r) ? $r_r : "\"".str_replace('"','""',$r_r)."\"").$sign;
				$sq=substr($sq,0,-1)."\n";
			}
			$sql[$tb.$ffext]= $sq;
		}
		if(count($tbs)==1) $sql= $sql[$fname];
	} elseif($ffmt[0]=='json') {//json format
		$tbs= array_merge($tbs, $vws);
		$ffty= "text/json"; $ffext= ".json"; $fname=$db.$ffext;
		$sql= array();
		if(count($tbs)==1) {
			$tbs= array($tbs[0]);
			$fname= $tbs[0].$ffext;
		}
		foreach($tbs as $tb) {
			$sq="// EdMyAdmin JSON Dump\n// version ".$version."\n\n// ".date('Y-m-d H:i:s')."\n\n";
			$q_jso=$ed->con->query("SELECT * FROM ".$tb);
			if($q_jso->num_row() > 0) {
			$sq.= '[';
			foreach($q_jso->fetch(2) as $k_jso=>$r_jso) {
			$jh= '{';
			foreach($r_jso as $k_jo=>$r_jo) $jh.= '"'.$k_jo.'":'.(is_numeric($r_jo)?$r_jo:'"'.htmlspecialchars($r_jo).'"').',';
			$sq.= substr($jh,0,-1).'},';
			}
			$sq= substr($sq,0,-1).']';
			}
			$sql[$tb.$ffext]= $sq;
		}
		if(count($tbs)==1) $sql= $sql[$fname];
	} elseif($ffmt[0]=='doc') {//doc format
		$tbs= array_merge($tbs, $vws);
		$ffty= "application/msword"; $ffext= ".doc"; $fname=$db.$ffext;
		$sql ='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:word" 	xmlns="http://www.w3.org/TR/REC-html40"><!DOCTYPE html><html><head><meta http-equiv="Content-type" content="text/html;charset=utf-8"></head><body>';
		foreach($tbs as $tb) {
		$q_doc = $ed->con->query("SHOW FIELDS FROM ".$tb)->fetch(2);
		if(in_array('structure',$fopt)) {
			$wh ='<table border=1 cellpadding=0 cellspacing=0 style="border-collapse: collapse"><tr bgcolor="#eeeeee">';
			foreach($q_doc[0] as $r_k=>$r_doc) $wh .='<th>'.$r_k.'</th>';
			$wh .='</tr>';
			foreach($q_doc as $r_doc) {
				$wh .='<tr>';
				foreach($r_doc as $r_d1) $wh .='<td>'.$r_d1.'</td>';
				$wh .='</tr>';
			}
			$wh .='</table><br>';
		}
		if(in_array('data',$fopt)) {
			$wb ='<table border=1 cellpadding=0 cellspacing=0 style="border-collapse: collapse"><tr>';
			foreach($q_doc as $r_dc) $wb .= '<th>'.$r_dc['Field'].'</th>';
			$wb .= "</tr>";
			$q_dc2 = $ed->con->query("SELECT * FROM ".$tb)->fetch(1);
			foreach($q_dc2 as $r_dc2) {
			$wb .= "<tr>";
			foreach($r_dc2 as $r_d2) $wb .='<td>'.$r_d2.'</td>';
			$wb .= "</tr>";
			}
			$wb .='</table><br>';
		}
		$sql.= $wh.$wb;
		}
		$sql .='</body></html>';
	} elseif($ffmt[0]=='xls') {//xls format
		$tbs= array_merge($tbs, $vws);
		$ffty= "application/excel"; $ffext= ".xls"; $fname=$db.$ffext;
		$sql ='<?xml version="1.0"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">';
		foreach($tbs as $tb) {
			$xh= '<Worksheet ss:Name="'.$tb.'"><Table><Row>';
			$q_xl1 = $ed->con->query("SHOW FIELDS FROM ".$tb)->fetch(2);
			foreach($q_xl1 as $r_xl1) {
			$xh .= '<Cell><Data ss:Type="String">'.$r_xl1['Field'].'</Data></Cell>';
			}
			$xh .='</Row>';
			$q_xl2 = $ed->con->query("SELECT * FROM ".$tb)->fetch(1);
			foreach($q_xl2 as $r_xl2) {
			$xh .='<Row>';
			foreach($r_xl2 as $r_x2) $xh .= '<Cell><Data ss:Type="'.(is_numeric($r_x2)?'Number':'String').'">'.$r_x2.'</Data></Cell>';
			$xh .='</Row>';
			}
			$sql .=$xh.'</Table></Worksheet>';
		}
		$sql .='</Workbook>';
	}

	if(in_array("gzip", $ftype)) {//gzip
		$zty = "application/x-gzip"; $zext= ".gz";
		ini_set('zlib.output_compression','Off');
		if(is_array($sql) && count($sql)>1) {
		$sq='';
		foreach($sql as $qname=>$sqa) {
			$tmpf= tmpfile();
			$len= strlen($sqa);
			$ctxt= pack("a100a8a8a8a12a12", $qname, 644, 0, 0, decoct($len), decoct(time()));
			$checksum = 8*32;
			for($i=0; $i < strlen($ctxt); $i++) $checksum += ord($ctxt[$i]);
			$ctxt .= sprintf("%06o", $checksum)."\0 ";
			$ctxt .= str_repeat("\0", 512 - strlen($ctxt));
			$ctxt .= $sqa;
			$ctxt .= str_repeat("\0", 511 - ($len + 511) % 512);
			fwrite($tmpf, $ctxt);
			fseek($tmpf, 0);
			$fs = fstat($tmpf); 
			$sq.= fread($tmpf, $fs['size']);
			fclose($tmpf);
		}
		$fname= $db.".tar";
		$sql= $sq.pack('a1024','');
		}
		$sql = gzencode($sql, 9);
		header('Content-Encoding: gzip');
	} elseif(in_array("zip", $ftype)) {//zip
		$zty = "application/x-zip";
		$zext= ".zip";
		$info = array();
		$ctrl_dir = array();
		$eof = "\x50\x4b\x05\x06\x00\x00\x00\x00";
		$old_offset = 0;
		if(is_array($sql)) $sqlx=$sql;
		else $sqlx[$fname]=$sql;
		foreach($sqlx as $qname=>$sqa) {
		$ti = getdate();
		if($ti['year'] < 1980) {
		$ti['year'] = 1980;$ti['mon'] = 1;$ti['mday'] = 1;$ti['hours'] = 0;$ti['minutes'] = 0;$ti['seconds'] = 0;
		}
		$time = (($ti['year'] - 1980) << 25) | ($ti['mon'] << 21) | ($ti['mday'] << 16) | ($ti['hours'] << 11) | ($ti['minutes'] << 5) | ($ti['seconds'] >> 1);
		$dtime = substr("00000000" . dechex($time), -8);
		$hexdtime = '\x'.$dtime[6].$dtime[7].'\x'.$dtime[4].$dtime[5].'\x'.$dtime[2].$dtime[3].'\x'.$dtime[0].$dtime[1];
		eval('$hexdtime = "'.$hexdtime.'";');
		$fr = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00".$hexdtime;
		$unc_len = strlen($sqa);
		$crc = crc32($sqa);
		$zdata = gzcompress($sqa);
		$zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		$c_len = strlen($zdata);
		$fr .= pack('V', $crc).pack('V', $c_len).pack('V', $unc_len).pack('v', strlen($qname)).pack('v', 0).$qname.$zdata;
		$info[] = $fr;
		$cdrec = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00".$hexdtime.
		pack('V', $crc).pack('V', $c_len).pack('V', $unc_len).pack('v', strlen($qname)).
		pack('v', 0).pack('v', 0).pack('v', 0).pack('v', 0).pack('V', 32).pack('V', $old_offset);
		$old_offset += strlen($fr);
		$cdrec .= $qname;
		$ctrl_dir[] = $cdrec;
		}
		$ctrldir = implode('', $ctrl_dir);
		$end = $ctrldir.$eof.pack('v', sizeof($ctrl_dir)).pack('v', sizeof($ctrl_dir)).pack('V', strlen($ctrldir)).pack('V', $old_offset)."\x00\x00";
		$datax = implode('', $info);
		$sql = $datax.$end;
	}
	header("Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0");
	header("Content-Type: ".(in_array("plain", $ftype) ? $ffty."; charset=utf-8" : $zty));
	header("Content-Length: ".strlen($sql));
	header("Content-Disposition: attachment; filename=".$fname.(in_array("plain", $ftype) ? "":$zext));
	die($sql);
	}
break;

case "33": //blob download
	$ed->check(array(1,2,3),array('redir'=>'20'));
	$db= $ed->sg[1];
	$tb= $ed->sg[2];
	$nu= $ed->sg[3];
	$id= base64_decode($ed->sg[4]);
	$ph= $ed->sg[5];
	$q_ph = $ed->con->query("SELECT {$ph} FROM {$tb} WHERE {$nu} LIKE '".$id."' LIMIT 1")->fetch();
	$len= strlen($q_ph[0]);
	if($len >= 2 && $q_ph[0][0] == chr(0xff) && $q_ph[0][1] == chr(0xd8)) {$tp= 'image/jpeg';$xt='.jpg';}
	elseif($len >= 3 && substr($q_ph[0], 0, 3) == 'GIF') {$tp= 'image/gif';$xt='.gif';}
	elseif($len >= 4 && substr($q_ph[0], 0, 4) == "\x89PNG") {$tp= 'image/png';$xt='.png';}
	else {$tp= 'application/octet-stream';$xt='.bin';}
	header("Content-type: ".$tp);
	header("Content-Length: ".$len);
	header("Content-Disposition: attachment; filename=".$tb."-data".$xt);
	die($q_ph[0]);
break;

case "40": //view
	if(!isset($ed->sg[2]) && !isset($ed->sg[3])) {//add
		$ed->check(array(1));
		$db= $ed->sg[1];
		$r_uv= array(0=>'',1=>'');
		if($ed->post('uv1','!e') && $ed->post('uv2','!e')) {
			$tb= $ed->sanitize($ed->post('uv1'));
			$exi= $ed->con->query("SELECT 1 FROM ".$tb);
			if($exi) $ed->redir("5/".$db,array('err'=>"This name exist"));
			$vstat= $ed->post('uv2','',1);
			$stat= $ed->con->query($vstat);
			if(!$stat) $ed->redir("5/".$db,array('err'=>"Wrong statement"));
			$v_cre= $ed->con->query("CREATE VIEW ".$tb." AS ".$vstat);
			if($v_cre) $ed->redir("5/".$db,array('ok'=>"Successfully created"));
			else $ed->redir("5/".$db,array('err'=>"Create view failed"));
		}
		echo $head.$ed->menu($db,'',2).$ed->form("40/$db");
		$b_lbl="Create";
	} else {//edit
		$ed->check(array(1,5));
		$db= $ed->sg[1];$sp= $ed->sg[2];$ty= $ed->sg[3];
		$r_uv= $ed->con->query("SELECT TABLE_NAME,VIEW_DEFINITION FROM information_schema.VIEWS WHERE `TABLE_SCHEMA`='$db' AND `TABLE_NAME`='$sp'")->fetch();
		if($ed->post('uv1','!e') && $ed->post('uv2','!e')) {
			$tb= $ed->sanitize($ed->post('uv1'));
			if(is_numeric(substr($tb,0,1))) $ed->redir("5/".$db,array('err'=>"Not a valid name"));
			$exi= $ed->con->query("SELECT 1 FROM ".$tb);
			if($exi && $tb!=$r_uv[0]) $ed->redir("5/".$db,array('err'=>"This name exist"));
			$vstat= $ed->post('uv2','',1);
			$stat= $ed->con->query($vstat);
			if(!$stat) $ed->redir("5/".$db,array('err'=>"Wrong statement"));
			$ed->con->query("DROP VIEW IF EXISTS ".$sp);
			$ed->con->query("CREATE VIEW ".$tb." AS ".$vstat);
			$ed->redir("5/".$db,array('ok'=>"Successfully updated"));
		}
		echo $head.$ed->menu($db,'',2,array($ty,$sp)).$ed->form("40/$db/$sp/$ty");
		$b_lbl="Edit";
	}
	echo "<table><tr><th colspan='2'>{$b_lbl} View</th></tr>
	<tr><td>Name</td><td><input type='text' name='uv1' value='".$r_uv[0]."'/></td></tr>
	<tr><td>Statement</td><td><textarea name='uv2'>".$r_uv[1]."</textarea></td></tr>
	<tr><td class='c1' colspan='2'><button type='submit'>Save</button></td></tr></table></form>";
break;

case "41": //trigger
	if(!isset($ed->sg[2]) && !isset($ed->sg[3])) {//add
		$ed->check(array(1));
		$db= $ed->sg[1];
		$r_tge= array(0=>'',1=>'',2=>'',3=>'',4=>'');
		if($ed->post('utg1','!e') && $ed->post('utg5','!e')) {
		$utg1= $ed->sanitize($ed->post('utg1'));
		if(is_numeric(substr($t_nm,0,1))) $ed->redir("41/".$db,array('err'=>"Not a valid name"));
		$utg2= $ed->post('utg2');$utg3= $ed->post('utg3');$utg4= $ed->post('utg4');$utg5= $ed->post('utg5','',1);
		$q_tgcrt= $ed->con->query("CREATE TRIGGER `".$utg1."` ".$utg2." ".$utg3." ON `".$utg4."` FOR EACH ROW ".$utg5);
		if($q_tgcrt) $ed->redir("5/".$db,array('ok'=>"Successfully created"));
		else $ed->redir("5/".$db,array('err'=>"Create failed"));
		}
		echo $head.$ed->menu($db,'',2).$ed->form("41/$db");
		$t_lbl="Create";
	} else {//edit
		$ed->check(array(1,5));
		$db= $ed->sg[1];$sp= $ed->sg[2];$ty= $ed->sg[3];
		if($ed->post('utg1','!e') && $ed->post('utg5','!e')) {
			$utg1= $ed->sanitize($ed->post('utg1'));
			$utg2= $ed->post('utg2');$utg3= $ed->post('utg3');$utg4= $ed->post('utg4');$utg5= $ed->post('utg5','',1);
			if(is_numeric(substr($utg1,0,1))) $ed->redir("5/".$db,array('err'=>"Not a valid name"));
			$sess= $ed->con->query("SHOW CREATE ".$ty." {$db}.".$sp)->fetch();
			$_SESSION['t_tmp']= $sess[2];
			$ed->con->query("DROP {$ty} IF EXISTS {$db}.".$sp);
			$q_tgcrt= $ed->con->query("CREATE TRIGGER `".$utg1."` ".$utg2." ".$utg3." ON `".$utg4."` FOR EACH ROW ".$utg5);
			if($q_tgcrt) {
			unset($_SESSION["t_tmp"]);
			$ed->redir("5/".$db,array('ok'=>"Successfully updated"));
			} else {
			$ed->con->query($_SESSION["t_tmp"]);
			unset($_SESSION["t_tmp"]);
			$ed->redir("41/$db/$sp/$ty",array('err'=>"Update failed"));
			}
		}
		$r_tge= $ed->con->query("SELECT TRIGGER_NAME,EVENT_OBJECT_TABLE,ACTION_TIMING,EVENT_MANIPULATION,ACTION_STATEMENT FROM information_schema.TRIGGERS WHERE `TRIGGER_SCHEMA`='$db' AND `TRIGGER_NAME`='$sp'")->fetch();
		echo $head.$ed->menu($db,'',2,array($ty,$sp)).$ed->form("41/$db/$sp/$ty");
		$t_lbl="Edit";
	}
	$tgtb= array();//list tables
	$q_trgt = $ed->con->query("SHOW TABLE STATUS FROM ".$db)->fetch(2);
	foreach($q_trgt as $r_trgt) {
	if($r_trgt['Comment']!='VIEW') {
	$tgtb[] = $r_trgt['Name'];
	}
	}
	echo "<table><tr><th colspan='2'>{$t_lbl} Trigger</th></tr>
	<tr><td>Trigger Name</td><td><input type='text' name='utg1' value='".$r_tge[0]."'/></td></tr>
	<tr><td>Table</td><td><select name='utg4'>";
	foreach($tgtb as $tgt) echo "<option value='".$tgt."'".($r_tge[1]==$tgt? " selected":"").">".$tgt."</option>";
	echo "</select></td></tr><tr><td>Time</td><td><select name='utg2'>";
	$tm= array('BEFORE','AFTER');
	foreach($tm as $tn) echo "<option value='$tn'".($r_tge[2]==$tn?" selected":"").">$tn</option>";
	echo "</select></td></tr>
	<tr><td>Event</td><td><select name='utg3'>";
	$evm= array('INSERT','UPDATE','DELETE');
	foreach($evm as $evn) echo "<option value='$evn'".($r_tge[3]==$evn?" selected":"").">$evn</option>";
	echo "</select></td></tr>
	<tr><td>Definition</td><td><textarea name='utg5'>".$r_tge[4]."</textarea></td></tr>
	<tr><td class='c1' colspan='2'><button type='submit'>Save</button></td></tr></table></form>";
break;

case "42": //routine
	if(!isset($ed->sg[2]) && !isset($ed->sg[3])) {//add
		$ed->check(array(1));
		$db= $ed->sg[1];
		$r_rou= array(0=>'',1=>'',4=>'',5=>'NO',6=>'',7=>'',8=>'');$plist=1;
		if($ed->post('ronme','!e') && $ed->post('rodf','!e')) {
			$r_new= $ed->sanitize($ed->post('ronme'));
			$crea= $ed->create_ro($db,$r_new);
			if($crea) $ed->redir("5/$db",array('ok'=>"Created routine"));
			else $ed->redir("5/$db",array('err'=>"Create failed"));
		}
		echo $head.$ed->menu($db,'',2).$ed->form("42/$db");
		$t_lbl="Create";
	} else {//edit
		$ed->check(array(1,5));
		$db= $ed->sg[1];$sp= $ed->sg[2];$ty= $ed->sg[3];
		if($ed->post('ronme','!e') && $ed->post('rodf','!e')) {
			$r_new= $ed->sanitize($ed->post('ronme'));
			$r_tmp= $r_new.'_'.uniqid(mt_rand());
			$crea= $ed->create_ro($db,$r_tmp);
			$ed->con->query("DROP ".$ty." IF EXISTS ".$r_tmp);
			if($crea) {
			$ed->con->query("DROP ".$ty." IF EXISTS ".$sp);
			$ed->create_ro($db,$r_new);
			$ed->redir("5/$db",array('ok'=>"Updated routine"));
			} else $ed->redir("42/$db/$sp/$ty",array('err'=>"Update failed"));
		}
		$r_rou= $ed->con->query("SELECT name,type,param_list,returns,body,is_deterministic,security_type,sql_data_access,comment FROM mysql.proc WHERE `db`='$db' AND `name`='$sp'")->fetch();
		//function return
		$r_f1=(stripos($r_rou[3],') ')?'\s*[\(](.*)[\)]':'');
		$r_f2=(stripos($r_rou[3],' CHARSET ')?'(CHARSET)\s+':'');
		preg_match('/^(\w+)'.$r_f1.'\s+'.$r_f2.'(.*)/', $r_rou[3], $retrn);
		//param_list
		$plist= preg_split("/\(.*?\)(*SKIP)(*F)|,/", $r_rou[2]);
		echo $head.$ed->menu($db,'',2,array($ty,$sp)).$ed->form("42/$db/$sp/$ty");
		$t_lbl="Edit";
	}
	if(empty($retrn)) $retrn= array(1=>'',2=>'',3=>'');
	$swcl= "<option value=''>&nbsp;</option>";
	$q_swcl= $ed->con->query("SHOW CHARACTER SET")->fetch(1);
	$pfs= array('PROCEDURE','FUNCTION');
	echo "<table><tr><th colspan='2'>{$t_lbl} Routine</th></tr>
	<tr><td>Name</td><td><input type='text' name='ronme' value='".$r_rou[0]."' /></td></tr>
	<tr><td>Type</td><td><select id='rou' name='roty'>";
	foreach($pfs as $pf) echo "<option value='$pf'".($pf==$r_rou[1]?" selected":"").">$pf</option>";
	echo "</select></td></tr>
	<tr><td>Parameters</td><td>
	<table>
	<tr><th class='rou1'>Direction</th><th>Name</th><th>Type</th><th>Values</th><th>Options</th><th></th></tr>";
	$p=1;
	$p_f1=($r_rou[1]=='PROCEDURE' ? '(\w+)\s+':'');
	while($p<=count($plist)) {
	$p_curr= $plist[$p-1];
	$p_f2=(stripos($p_curr,')')?'\s*[\(](.*)[\)]':'');
	$p_f3=(stripos($p_curr,' CHARSET ')?'(CHARSET)\s+':'');
	preg_match('/'.$p_f1.'`(.*)`\s+(.*)'.$p_f2.$p_f3.'(.*)/', $p_curr, $pre);
	if(isset($r_rou[1]) && $r_rou[1]=='PROCEDURE') array_shift($pre);
	if(empty($pre)) $pre=array(0=>'',1=>'',2=>'',3=>'',4=>'');
	echo "<tr id='rr_{$p}'><td class='rou1'>
		<select name='ropin[]'>";
		$inouts= array('IN','OUT','INOUT');
		foreach($inouts as $inout) echo "<option value='$inout'".($inout==trim($pre[0])?" selected":"").">$inout</option>";
		echo "</select>
		</td><td><input type='text' name='roppa[]' value='".$pre[1]."' /></td><td>
		<select id='pty_{$p}' name='ropty[]'>".$ed->fieldtypes(trim($pre[2]))."</select>
		</td><td><input type='text' name='ropva[]' value='".($pre[3]!='CHARSET'?$pre[3]:'')."'/></td><td>
		<select class='pa1' name='rop1[]'>";
		foreach($inttype as $itk=>$itt) {
		echo "<option value='$itk'".(trim($pre[4])==$itk?" selected":"").">$itt</option>";
		}
		foreach($q_swcl as $r_rocl) $swcl .= "<option value='".$r_rocl[0]."'".($pre[4]==$r_rocl[0]?" selected":"").">".$r_rocl[0]."</option>";
		echo "</select><select class='pa2' name='rop2[]'>".$swcl."</select>
		</td><td class='bb'><span onclick='plus(this)'>+</span> <span onclick=\"minus(this)\">-</span></td></tr>";
	++$p;
	}

	echo "</table></td></tr><tr class='rou2 auto'><td>Return type</td><td><select id='pty2' name='rorty'>".(isset($retrn[1])?$ed->fieldtypes(strtoupper($retrn[1])):$ed->fieldtypes())."</select>
	<input type='text' name='rorva' value='".((isset($retrn[2]) && $retrn[2]!='CHARSET')?$retrn[2]:"")."'/>
	<select id='px1' name='rorop1'>";
	foreach($inttype as $itk=>$itt) {
	echo "<option value='$itk'".($retrn[3]==$itt?" selected":"").">$itt</option>";
	}
	echo "</select><select id='px2' name='rorop2'>";
	foreach($q_swcl as $r_rocl) {
	echo "<option value='".$r_rocl[0]."'".($retrn[3]==$r_rocl[0]?" selected":"").">".$r_rocl[0]."</option>";
	}
	echo "</select></td></tr>
	<tr><td>Definition</td><td><textarea name='rodf'>".$r_rou[4]."</textarea></td></tr>
	<tr><td>Deterministic</td><td><input type='checkbox' name='rodet'".($r_rou[5]=="NO"?"":" checked")."/></td></tr>
	<tr><td>Security type</td><td><select name='rosec'>";
	$dfns= array('DEFINER','INVOKER');
	foreach($dfns as $dfn) echo "<option value='$dfn'".($r_rou[6]==$dfn?" selected":"").">$dfn</option>";
	echo "</select></td></tr>
	<tr><td>SQL data access</td><td><select name='rosda'>";
	foreach($ed->sqlda as $sdk=>$sda) echo "<option value='$sdk'".($r_rou[7]==$sdk?" selected":"").">$sda</option>";
	echo "</select></td></tr><tr><td>Comment</td><td><input type='text' name='rocom' value='".$r_rou[8]."'/></td></tr>
	<tr><td class='c1' colspan='2'><button type='submit'>Save</button></td></tr></table></form>";
break;

case "43": //event
	if(!isset($ed->sg[2]) && !isset($ed->sg[3])) {//add
		$ed->check(array(1));
		$db= $ed->sg[1];
		$r_eve= array(0=>'',1=>'',2=>'',3=>'',4=>'',5=>'',7=>'',8=>'',9=>'',10=>'');
		if($ed->post('evnme','!e') && $ed->post('evstat','!e')) {
			$evn= $ed->sanitize($ed->post('evnme'));
			if(is_numeric(substr($evn,0,1))) $ed->redir("43/".$db,array('err'=>"Not a valid name"));
			$q_evcrt = $ed->con->query("CREATE EVENT `".$evn."` ON SCHEDULE ".($ed->post('evpre','i')? "AT '".$ed->post('evsta')."'":"EVERY '".$ed->post('evevr1')."' ".$ed->post('evevr2')." STARTS '".$ed->post('evsta')."' ENDS '".$ed->post('evend')."'")." ON COMPLETION".($ed->post('evpre','i')?"":" NOT")." PRESERVE ".$ed->post('evendi')." COMMENT '".$ed->post('evcom')."' DO ".$ed->post('evstat','',1));
			if($q_evcrt) $ed->redir("5/".$db,array('ok'=>"Successfully created"));
			else $ed->redir("5/".$db,array('err'=>"Create event failed"));
		}
		echo $head.$ed->menu($db,'',2).$ed->form("43/$db");
		$t_lbl="Create";
	} else {//edit
		$ed->check(array(1,5));
		$db= $ed->sg[1];$sp= $ed->sg[2];$ty= $ed->sg[3];
		if($ed->post('evnme','!e') && $ed->post('evstat','!e')) {
			$evn= $ed->sanitize($ed->post('evnme'));
			if(is_numeric(substr($evn,0,1))) $ed->redir("5/".$db,array('err'=>"Not a valid name"));
			$q_evcrt = $ed->con->query("ALTER EVENT `".$sp."` ON SCHEDULE ".
			($ed->post('evone','!e') ? "AT '".$ed->post('evsta')."'":"EVERY '".$ed->post('evevr1')."' ".$ed->post('evevr2')." STARTS '".$ed->post('evsta')."' ENDS '".$ed->post('evend')."'").
			" ON COMPLETION".($ed->post('evpre','!e')?"":" NOT")." PRESERVE ".$ed->post('evendi')." COMMENT '".$ed->post('evcom')."' DO ".$ed->post('evstat','',1));
			if(!$q_evcrt) $ed->redir("5/".$db,array('err'=>"Update event failed"));
			if($sp != $evn) {
			$q_evren = $ed->con->query("ALTER EVENT `".$sp."` RENAME TO ".$evn);
			if(!$q_evren) $ed->redir("5/".$db,array('err'=>"Rename event failed"));
			}
			$ed->redir("5/".$db,array('ok'=>"Updated event"));
		}
		$r_eve= $ed->con->query("SELECT EVENT_NAME,STARTS,ENDS,EVENT_TYPE,INTERVAL_VALUE,INTERVAL_FIELD,EXECUTE_AT,STATUS,EVENT_COMMENT,ON_COMPLETION,EVENT_DEFINITION FROM information_schema.EVENTS WHERE `EVENT_SCHEMA`='$db' AND `EVENT_NAME`='$sp'")->fetch();
		echo $head.$ed->menu($db,'',2,array($ty,$sp)).$ed->form("43/$db/$sp/$ty");
		$t_lbl="Edit";
	}

	echo "<table><tr><th colspan='2'>{$t_lbl} Event</th></tr>
	<tr><td>Name</td><td><input type='text' name='evnme' value='".$r_eve[0]."'/></td></tr>
	<tr><td>Start</td><td><input type='text' name='evsta' value='".($r_eve[3]=='ONE TIME'?$r_eve[6]:$r_eve[1])."'/></td></tr>
	<tr id='evend'><td>End</td><td><input type='text' name='evend' value='".$r_eve[2]."'/></td></tr>
	<tr><td>One time</td><td><input type='checkbox' id='one' name='evone'".($r_eve[3]=='ONE TIME'?" checked":"")."/></td></tr>
	<tr id='every'><td>Every</td><td class='auto'><input type='text' name='evevr1' size='3' value='".$r_eve[4]."'/><select name='evevr2'>";
	$evr= array('YEAR','QUARTER','MONTH','DAY','HOUR','MINUTE','WEEK','SECOND','YEAR_MONTH','DAY_HOUR','DAY_MINUTE','DAY_SECOND','HOUR_MINUTE','HOUR_SECOND','MINUTE_SECOND');
	foreach($evr as $vr) echo "<option value='$vr'".($r_eve[5]==$vr?" selected":"").">$vr</option>";
	echo "</select></td></tr><tr><td>Status</td><td><select name='evendi'>";
	$stv= array('ENABLED'=>'ENABLE','DISABLED'=>'DISABLE','SLAVESIDE_DISABLED'=>'DISABLE ON SLAVE');
	foreach($stv as $ktv=>$tv) echo "<option value='$tv'".($r_eve[7]==$ktv?" selected":"").">$tv</option>";
	echo "</select></td></tr>
	<tr><td>Comment</td><td><input type='text' name='evcom' value='".$r_eve[8]."'/></td></tr>
	<tr><td>On completion preserve</td><td><input type='checkbox' name='evpre'".($r_eve[9]=='PRESERVE'?' checked':'')."/></td></tr>
	<tr><td>Statement</td><td><textarea name='evstat'>".$r_eve[10]."</textarea></td></tr>
	<tr><td class='c1' colspan='2'><button type='submit'>Save</button></td></tr></table></form>";
break;

case "49": //drop sp
	$ed->check(array(1,5));
	$q_drosp = $ed->con->query("DROP ".$ed->sg[3]." ".$ed->sg[1].".".$ed->sg[2]);
	if($q_drosp) $ed->redir("5/".$ed->sg[1],array('ok'=>"Successfully dropped"));
break;

case "50": //login
	if($ed->post('lhost','!e') && $ed->post('username','!e') && $ed->post('password','i')) {
		$_SESSION['user']= $ed->post('username');
		$_SESSION['host']= $ed->post('lhost');
		$_SESSION['token']= $ed->enco($ed->post('password'));
		$ed->redir();
	}
	session_unset();
	session_destroy();
	echo $head.$ed->menu('','',2).$ed->form("50")."<div class='dw'><h3>LOGIN</h3>
	<div>Host<br/><input type='text' id='host' name='lhost' value='localhost' /></div>
	<div>Username<br/><input type='text' name='username' value='root' /></div>
	<div>Password<br/><input type='password' name='password' /></div>
	<div><button type='submit'>Login</button></div></div></form>";
break;

case "51": //logout
	session_unset();
	session_destroy();
	$ed->redir();
break;

case "52": //users
	$ed->check();
	echo $head.$ed->menu(1,'',2)."<table><tr><th>USER</th><th>HOST</th><th><a href='{$ed->path}53'>ADD</a></th></tr>";
	$q_usr = $ed->con->query("SELECT User, Host FROM mysql.user");
	foreach($q_usr->fetch(1) as $r_usr) {
	$bg=($bg==1)?2:1;
	echo "<tr class='r c$bg'><td class='dot'>".$r_usr[0]."</td><td class='dot'>".$r_usr[1]."</td><td><a class='del' href='{$ed->path}55/".$r_usr[0]."/".base64_encode($r_usr[1])."'>Drop</a><a href='{$ed->path}54/".$r_usr[0]."/".base64_encode($r_usr[1])."'>Edit</a></td></tr>";
	}
	echo "</table>";
break;

case "53": //add user
	$ed->check();
	if($ed->post('username','i') && $ed->post('host','!e')) {
	$user = $ed->sanitize($ed->post('username'));
	$passwd = ($ed->post('password','e') ? "":" IDENTIFIED BY '".$ed->post('password')."'");
	$host = $ed->post('host');
	$q_exist = $ed->con->query("SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user='{$user}' AND host='{$host}')")->fetch();
	if($q_exist[0]) $ed->redir("52",array('err'=>"Username already exist"));
	$ed->con->query("CREATE USER '{$user}'@'{$host}'{$passwd}");
	$alldb = $ed->post('dbs');
	$allpri = $ed->post('pri');
	$grant = ($ed->post('ogrant','!e') ? " GRANT OPTION":"");
	$with = " WITH".$grant." MAX_QUERIES_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
	if($allpri[0] == 'on') {//selected priv
		array_shift($allpri);
		$allprivs = implode(", ",$allpri);
	}
	if($alldb[0] == 'all' && $allpri[0] == 'all') {//all priv, all db
		$ed->con->query("GRANT USAGE ON *.* TO '{$user}'@'{$host}'".$passwd.$with);
		$ed->con->query("GRANT ALL PRIVILEGES ON *.* TO '{$user}'@'{$host}'");
	}
	if($alldb[0] == 'all' && $allpri[0] != 'all') {
		$ed->con->query("GRANT $allprivs ON *.* TO '{$user}'@'{$host}'".$passwd.$with);
	}
	if($alldb[0] == 'on') {//selected db
	array_shift($alldb);
	foreach($alldb as $adb) {
		if($allpri[0] == 'all') {//all priv
		$ed->con->query("GRANT USAGE ON {$adb}.* TO '{$user}'@'{$host}'".$passwd.$with);
		$ed->con->query("GRANT ALL PRIVILEGES ON {$adb}.* TO '{$user}'@'{$host}'");
		} else {//selected priv
		$ed->con->query("GRANT $allprivs ON {$adb}.* TO '{$user}'@'{$host}'".$passwd.$with);
		}
	}
	}
	$ed->con->query("FLUSH PRIVILEGES");
	$ed->redir("52",array('ok'=>"Added user"));
	}

	echo $head.$ed->menu(1,'',2).$ed->form("53")."<table><tr><th colspan='2'>Add User</th></tr>
	<tr><td>Host </td><td><input type='text' name='host' value='localhost' /></td></tr>
	<tr><td>Name </td><td><input type='text' name='username' /></td></tr>
	<tr><td>Password </td><td><input type='password' name='password' /></td></tr>
	<tr><td>Allow access to </td><td><input type='radio' onclick='hide(\"tdbs\")' name='dbs[]' value='all' checked /> All Databases</td></tr>
	<tr><td></td><td><input type='radio' onclick='show(\"tdbs\")' name='dbs[]' /> Selected Databases</td></tr>
	<tr><td></td><td id='tdbs' class='c1'>
	<p><input type='checkbox' onclick='selectall(\"dbs\",\"sel2\")' id='sel2' /> Select/Deselect</p>
	<select class='he' id='dbs' name='dbs[]' multiple='multiple'>";
	$q_dbs = $ed->con->query("SHOW DATABASES");
	foreach($q_dbs->fetch(1) as $r_dbs) {
	if(!in_array($r_dbs[0],$ed->deny)) {
	echo "<option value='".$r_dbs[0]."'>".$r_dbs[0]."</option>";
	}
	}
	echo "</select></td></tr>
	<tr><td>Privileges</td><td><input type='radio' onclick='hide(\"privs\")' name='pri[]' value='all' checked /> All Privileges</td></tr>
	<tr><td></td><td><input type='radio' onclick='show(\"privs\")' name='pri[]' /> Selected Privileges</td></tr>
	<tr><td></td><td id='privs' class='c1'>";
	$q_prvs= $ed->con->query("SHOW PRIVILEGES")->fetch(1);
	foreach($q_prvs as $r_prvs) {
	if($r_prvs[0]!='Grant option' && $r_prvs[0]!='Usage')
	echo "<p><input type='checkbox' name='pri[]' value='".$r_prvs[0]."' /> ".$r_prvs[0]."</p>";
	}
	echo "</td></tr>
	<tr><td>Options</td><td><input type='checkbox' name='ogrant' value='GRANT OPTION' /> Grant Option</td></tr>
	<tr><td class='c1' colspan='2'><button type='submit'>Create</button></td></tr></table></form>";
break;

case "54": //edit-update user
	$ed->check(array(6));
	if(empty($ed->sg[2])) {
	$hst= base64_decode($ed->sg[1]); $usr='';
	} else {
	$hst= base64_decode($ed->sg[2]); $usr= $ed->sg[1];
	}
	if($ed->post('userupd','i')) {
		if($_SESSION['user']!=$usr) {//no owner
		$ed->con->query("REVOKE ALL PRIVILEGES ON *.* FROM '$usr'@'$hst'");
		$ed->con->query("REVOKE GRANT OPTION ON *.* FROM '$usr'@'$hst'");
		$ed->con->query("DELETE FROM mysql.db WHERE `User`='$usr' AND `Host`='$hst'");
		$alldb= $ed->post('dbs');
		$allpri= $ed->post('pri');
		$grant= ($ed->post('ogrant','!e') ? " GRANT OPTION":"");
		$with= " WITH".$grant." MAX_QUERIES_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
		$passwd= ($ed->post('password','e') ? "":" IDENTIFIED BY '".$ed->post('password')."'");
		if($allpri[0] == 'on') {//selected priv
			array_shift($allpri);
			$allprivs = implode(",",$allpri);
		}
		if($alldb[0] == 'all' && $allpri[0] == 'all') {//all db, all priv
			$ed->con->query("GRANT ALL PRIVILEGES ON *.* TO '{$usr}'@'{$hst}'".$passwd.$with);
		}
		if($alldb[0] == 'all' && $allpri[0] != 'all') {//all db, select priv
			$ed->con->query("GRANT $allprivs ON *.* TO '{$usr}'@'{$hst}'".$passwd.$with);
		}
		if($alldb[0] == 'on') {//selected db
		array_shift($alldb);
		foreach($alldb as $adb) {
			$ed->con->query("GRANT USAGE ON `{$adb}`.* TO '$usr'@'$hst'".$passwd.$with);
			if($allpri[0] == 'all') {//all priv
			$ed->con->query("GRANT ALL PRIVILEGES ON `{$adb}`.* TO '$usr'@'$hst'");
			} else {//selected priv
			$ed->con->query("GRANT $allprivs ON `{$adb}`.* TO '$usr'@'$hst'");
			}
		}
		}
		}
		if($ed->post('password','!e')) {
		$ed->con->query("SET PASSWORD FOR '$usr'@'$hst' = PASSWORD('".$ed->post('password')."')");
		}
		if($ed->post('host','!e') || $ed->post('username','!e')) {
		$comma= (($ed->post('host','e') && $ed->post('username','e'))?"":",");
		$ed->con->query("UPDATE mysql.user SET ".($ed->post('host','e')?"":"host='".$ed->post('host')."'").$comma.($ed->post('username','e')?"":"user='".$ed->post('username')."'")." WHERE host='$hst' AND user='$usr'");
		}
		$ed->con->query("FLUSH PRIVILEGES");
		$ed->redir("52",array("ok"=>"Changed user privileges"));
	}

	$dbarr= array();//if selected db
	$dbprv= array();//db priv
	$q_dbpri = $ed->con->query("SELECT * FROM mysql.db WHERE `User`='{$usr}' AND `Host`='{$hst}'");
	foreach($q_dbpri->fetch(2) as $r_dbpri) {
	$dbarr[]= $r_dbpri['Db'];
	foreach($r_dbpri as $k_dbp=>$r_dbp) {
	if(substr($k_dbp, -5) == "_priv" && $r_dbp=='Y')
	$dbprv[]= strtoupper(substr($k_dbp,0,-5));
	}
	}
	$q_uu = $ed->con->query("SHOW GRANTS FOR '{$usr}'@'{$hst}'");//general priv
	$showgr=$q_uu->fetch();
	$grprivs= preg_replace('~GRANT\s(.*?)\sON(.*)~s','\1',$showgr[0]);
	if(count($dbarr) > 0) $grprivs2=$dbprv;//priv on db
	else $grprivs2= explode(", ",$grprivs);

	echo $head.$ed->menu(1,'',2).$ed->form("54/$usr/".base64_encode($hst))."<table><tr><th colspan='2'>Edit User</th></tr>
	<tr><td>Host </td><td><input type='text' name='host' value='{$hst}' /></td></tr>
	<tr><td>Name </td><td><input type='text' name='username' value='{$usr}' /></td></tr>
	<tr><td>Password </td><td><input type='password' name='password' /></td></tr>
	<tr><td>Allow access to </td><td><input type='radio' onclick='hide(\"tdbs\")' name='dbs[]' value='all'".(empty($dbarr)?" checked":"")." /> All Databases</td></tr>
	<tr><td></td><td><input type='radio' id='seldb' onclick='show(\"tdbs\")' name='dbs[]'".(!empty($dbarr)?" checked":"")." /> Selected Databases</td></tr>
	<tr><td></td><td id='tdbs' class='c1'>
	<p><input type='checkbox' onclick='selectall(\"dbs\",\"sel2\")' id='sel2' /> Select/Deselect</p>
	<select class='he' id='dbs' name='dbs[]' multiple='multiple'>";
	$q_dbs = $ed->con->query("SHOW DATABASES");
	foreach($q_dbs->fetch(1) as $r_dbs) {
	if(!in_array($r_dbs[0],$ed->deny)) {
	echo "<option value='".$r_dbs[0]."'".(in_array($r_dbs[0],$dbarr)?" selected ":"").">".$r_dbs[0]."</option>";
	}
	}
	echo "</select></td></tr>
	<tr><td>Privileges</td><td><input type='radio' onclick='hide(\"privs\")' name='pri[]' value='all'".($grprivs=="ALL PRIVILEGES"?" checked":"")." /> All Privileges</td></tr>
	<tr><td></td><td><input type='radio' id='selpriv' onclick='show(\"privs\")' name='pri[]'".($grprivs!="ALL PRIVILEGES"?" checked":"")." /> Selected Privileges</td></tr>
	<tr><td></td><td id='privs' class='c1'>";
	$q_prs= $ed->con->query("SHOW PRIVILEGES")->fetch(1);
	foreach($q_prs as $r_prs) {
	if($r_prs[0]!='Grant option' && $r_prs[0]!='Usage')
	echo "<p><input type='checkbox' name='pri[]' value='".$r_prs[0]."'".(in_array(strtoupper($r_prs[0]),$grprivs2)? " checked":"")." /> ".$r_prs[0]."</p>";
	}
	echo "</td></tr>
	<tr><td>Options</td><td><input type='checkbox' name='ogrant' value='GRANT OPTION'".((strpos($showgr[0],"GRANT OPTION") || in_array('GRANT',$dbprv))? " checked":"")." /> Grant Option</td></tr>
	<tr><td class='c1' colspan='2'><button type='submit' name='userupd'>Save</button></td></tr></table></form>";
break;

case "55": //drop user
	$ed->check(array(6));
	if(empty($ed->sg[2])) {
	$hh= base64_decode($ed->sg[1]); $uu='';
	} else {
	$hh= base64_decode($ed->sg[2]); $uu= $ed->sg[1];
	}
	if($uu == $_SESSION["user"]) {
	$ed->redir("52",array('err'=>"You can't delete your own user"));
	}
	$ed->con->query("REVOKE ALL PRIVILEGES ON *.* FROM '$uu'@'$hh'");
	$ed->con->query("REVOKE GRANT OPTION ON *.* FROM '$uu'@'$hh'");
	$dpr = array('columns_priv','tables_priv','db','user');
	foreach($dpr as $dp) {
	$ed->con->query("DELETE FROM mysql.{$dp} WHERE `User`='$uu' AND `Host`='$hh'");
	}
	$ed->con->query("DROP USER '$uu'@'$hh'");
	$ed->con->query("FLUSH PRIVILEGES");
	$ed->redir("52",array('ok'=>"Successfully deleted"));
break;

case "60": //info
	$ed->check();
	echo $head.$ed->menu(1,'',2)."<table>";
	if(empty($ed->sg[1])) {
		$use=(extension_loaded('mysqli')?'mysqli':'pdo_mysql');
		echo "<tr><th colspan='2'>INFO</th></tr>";
		$q_var= array('Use extension'=>$use,'PHP'=>PHP_VERSION,'Software'=>$_SERVER['SERVER_SOFTWARE']);
		foreach($q_var as $r_k=>$r_var) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg'><td class='dot'>$r_k</td><td class='dot'>$r_var</td></tr>";
		}
	} elseif($ed->sg[1]=='var') {
		echo "<tr><th>VARIABLE</th><th>VALUE</th></tr>";
		$q_var = $ed->con->query("SHOW VARIABLES")->fetch(1);
		foreach($q_var as $r_var) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg'><td class='dot'>".$r_var[0]."</td><td class='dot'>".htmlspecialchars($r_var[1])."</td></tr>";
		}
	} elseif($ed->sg[1]=='status') {
		echo "<tr><th>VARIABLE</th><th>VALUE</th></tr>";
		$q_sts = $ed->con->query("SHOW STATUS")->fetch(1);
		foreach($q_sts as $r_sts) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg'><td class='dot'>".$r_sts[0]."</td><td class='dot'>".$r_sts[1]."</td></tr>";
		}
	} elseif($ed->sg[1]=='process') {
		if($ed->post('killp','i')) $ed->con->query("KILL ".$ed->post('pid'));
		$q_prs = $ed->con->query("SHOW FULL PROCESSLIST")->fetch(2);
		$kys= array_keys($q_prs[0]);
		echo "<tr><th>&nbsp;</th>";
		foreach($kys as $ky) echo "<th>$ky</th>";
		echo "</tr>";
		foreach($q_prs as $r_prs) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg'><td>".$ed->form("60/process")."<input type='hidden' name='pid' value='".$r_prs['Id']."'/><button type='submit' name='killp'>Kill</button></form></td>";
		foreach($kys as $ky) echo "<td>".$r_prs[$ky]."</td>";
		echo "</tr>";
		}
	}
	echo "</table>";
break;
}//End Switch
unset($_POST);
unset($_SESSION["ok"]);
unset($_SESSION["err"]);
?></div></div><div class="l1 cntr"><a href="http://edmondsql.github.io">edmondsql</a></div></body></html>