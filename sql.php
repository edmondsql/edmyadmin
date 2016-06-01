<?php
error_reporting(E_ALL ^E_DEPRECATED);
if(version_compare(PHP_VERSION, '4.3.0', '<')) die('Require PHP 4.3 or higher');
if(!extension_loaded('mcrypt')) die('Install php_mcrypt extension!');
session_start();
$step=15;
$pg_lr=8;
$salt="#123#";
$bg="";
$del=" onclick=\"return confirm('are you sure?')\"";
$version="2.0";
$deny= array('mysql','information_schema','performance_schema');
$pi= (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO'));
$sg= preg_split('!/!', $pi,-1,PREG_SPLIT_NO_EMPTY);
$scheme= 'http'.(empty($_SERVER['HTTPS']) === true || $_SERVER['HTTPS'] === 'off' ? '' : 's').'://';
$r_uri= isset($_SERVER['PATH_INFO']) === true ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
$script= $_SERVER['SCRIPT_NAME'];
$path= $scheme.$_SERVER['HTTP_HOST'].(strpos($r_uri, $script) === 0 ? $script : rtrim(dirname($script),'/.\\')).'/';
$bbs= array('False','True');
//$iv= mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB));
$iv= pack('H*',"00000000000000000000000000000000");
$head= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"><html><head>
<title>EdMyAdmin</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<style type="text/css">
* {margin:0;padding:0;color:#333;font-size: 12px;font-family:Arial}
a {color:#842;text-decoration:none}
a:hover {text-decoration:underline}
a,a:active,a:hover {outline: 0}
textarea, .he {min-height:90px}
table {border-collapse: collapse}
.mrg {margin-top:3px}
.box {padding:3px}
.a, .a1 {border:1px solid #555}
.a1 {margin:3px auto}
.c2 {background:#fff}
td, th {padding:4px;vertical-align:top}
.th {border-top:1px solid #555;font-weight:bold}
.scroll {overflow-x:auto}
td.pro,th.pro {border: 1px dotted #842}
.l1,.l2,l3,.wi {width:100%}
input[type=text],input[type=password],input[type=file],textarea,button,select {width:100%;padding:2px 0;border:1px solid #bbb;outline:none;
-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;-khtml-border-radius:4px;
-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box}
select {padding:1px 0}
.div button, .div input, .div select {width:auto}
input[type=checkbox],input[type=radio]{position: relative;vertical-align: middle;bottom: 1px}
.l1, th, caption, button {background:#9be}
.l2,.c1 {background:#cdf}
.l3, tr:hover.r, button:hover {background:#fe3 !important}
#tdbs,#privs,[class^=pa],[id^=px],.rou2 {display:none}
.move,.bb {cursor:pointer;cursor:hand}
.lgn, .msg{position:absolute;top:0;right:0}
.msg {padding:8px;font-weight:bold;font-size:13px;z-index:1}
.ok {background:#EFE;color:#080;border-bottom:2px solid #080}
.err {background:#FEE;color:#f00;border-bottom:2px solid #f00}
.left *, input[type=password] {width:196px;position: relative;z-index:1}
input[type=text],select {min-width:98px !important}
optgroup option {padding-left:8px}
.bb {font: 18px/12px Arial}
</style>
<script src="http://emon/jq/jquery.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$("#username").focus();
'.((empty($_SESSION['ok']) && empty($_SESSION['err'])) ? '':'$("body").fadeIn("slow").prepend("'.
(!empty($_SESSION['ok']) ? '<div class=\"msg ok\">'.$_SESSION['ok'].'<\/div>':'').
(!empty($_SESSION['err']) ? '<div class=\"msg err\">'.$_SESSION['err'].'<\/div>':'').'");
setTimeout(function(){$(".msg").fadeOut("slow",function(){$(this).remove();});}, 7000);').'
$(".msg").dblclick(function(){$(this).hide()});
$("#one").click(function(){if($("#one:checked").val()=="on"){$("#every,#evend").hide();}else{$("#every,#evend").show();}});//add event case
$("#rou").change(function(){//routine form
if($(this).val()=="FUNCTION"){$(".rou1").hide();$(".rou2").show();}else{$(".rou1").show();$(".rou2").hide();}
});
routine();
$("#plus").click(function(){//routine clone row
var curr=$("[id^=\"rr_\"]").length;
var cnt="rr_" + (curr + 1);
$("#rr_"+curr).clone(true).insertAfter("#rr_"+curr).attr("id",cnt);
routine(curr);
});
$("#minus").click(function(){//routine remove row
var crr=$("[id^=\"rr_\"]").length;
if(crr>1) $("#rr_"+crr).remove();
});
//$(".left textarea").click(function(){$(this).width(580+"px")}).blur(function(){$(this).width(100+"%")});
$(".up,.down").click(function(){//reorder
var row= $(this).parents("tr:first");
if($(this).is(".up")){row.insertBefore(row.prev());obj1=row.next().attr("id");obj2=row.attr("id");}else{
row.insertAfter(row.next());obj1=row.attr("id");obj2=row.prev().attr("id");
}
$.ajax({type: "POST", url:"'.$path.'9/'.(empty($sg[1])?"":$sg[1]).'/'.(empty($sg[2])?"":$sg[2]).'", data:"n1="+obj1+"&n2="+obj2, success:function(){location.reload();}});
});
if($("#seldb:checked").length == 1){$("#tdbs").fadeIn();}else{$("#tdbs").hide();}
if($("#selpriv:checked").length == 1){$("#privs").fadeIn();}else{$("#privs").hide();}
});//end
function routine(id){
var ar1=["INT","TINYINT","SMALLINT","MEDIUMINT","BIGINT","DOUBLE","DECIMAL","FLOAT"];
var ar2=["VARCHAR","CHAR","TEXT","TINYTEXT","MEDIUMTEXT","LONGTEXT"];
var ej=$("#pty2"),ej1=$("#px1"),ej2=$("#px2");
if($.inArray(ej.val(),ar1)!= -1){ej1.show();ej2.hide();}else if($.inArray(ej.val(),ar2)!= -1){ej1.hide();ej2.show();}else{ej1.hide();ej2.hide();}
ej.change(function(){
if($.inArray(ej.val(),ar1)!= -1){ej1.show();ej2.hide();}else if($.inArray(ej.val(),ar2)!= -1){ej1.hide();ej2.show();}else{ej1.hide();ej2.hide();}
});
if(id === undefined) id=0;
var el=$(".pty1").eq(id),el1=$(".pa1").eq(id),el2=$(".pa2").eq(id);
if($.inArray(el.val(),ar1)!= -1){el1.show();el2.hide();}else if($.inArray(el.val(),ar2)!= -1){el1.hide();el2.show();}else{el1.hide();el2.hide();}
$(".pty1").change(function(){
var ix= $(".pty1").index(this);
var el=$(".pty1").eq(ix),el1=$(".pa1").eq(ix),el2=$(".pa2").eq(ix);
if($.inArray(el.val(),ar1)!= -1){el1.show();el2.hide();}else if($.inArray(el.val(),ar2)!= -1){el1.hide();el2.show();}else{el1.hide();el2.hide();}
});
}
function show(ex){$("#"+ex).fadeIn("slow");}
function hide(ex){$("#"+ex).fadeOut("slow");}
function selectall(lb, cb) {
var cb=document.getElementById(cb);
if(cb.checked) {
var multi=document.getElementById(lb);
for(var i=0;i<multi.options.length;i++) {
multi.options[i].selected=true;
}
}else{
var multi=document.getElementById(lb);
multi.selectedIndex=-1;
}
}
function toggle(cb, ey){
var cbox=document.getElementsByName(ey);
for(var i=0;i<cbox.length;i++){
cbox[i].checked=cb.checked;
if(ey="fopt[]") opt();
}}
function opt(){
var opt=document.getElementsByName("fopt[]");
for(var i=2;i<opt.length;i++){
if(opt[0].checked == false) opt[i].checked=false;
opt[i].parentElement.style.display=(opt[0].checked ? "block":"none");
}}
</script>
</head><body><div class="l1"><b><a href="https://github.com/edmondsql/edmyadmin">EdMyAdmin '.$version.'</a></b>'.(isset($sg[0]) && $sg[0]==50 ? "": '<div class="lgn"><a href="'.$path.'52">Users</a> | <a href="'.$path.'51">Logout ['.(isset($_SESSION['user']) ? $_SESSION['user']:"").']</a>&nbsp;</div>').'</div>';
function clean($el, $cod='') {
if(get_magic_quotes_gpc()) {
$el= stripslashes($el);
}
if($cod==1) {
return trim(str_replace(array(">","<","\\","\r\n","\r"), array("&gt;","&lt;","\\\\","\n","\n"), $el));//between quota
} else {
return trim(str_replace(array(">","<","\\","'",'"',"\r\n","\r"), array("&gt;","&lt;","\\\\","&#039;","&quot;","\n","\n"), $el));
}
}
function post($idxk='', $op='', $clean='') {
if($idxk === '' && !empty($_POST)) {
return ($_SERVER['REQUEST_METHOD'] === 'POST' ? TRUE : FALSE);
}
if(!isset($_POST[$idxk])) return FALSE;
if(is_array($_POST[$idxk])) {
if(isset($op) && is_numeric($op)) {
return clean($_POST[$idxk][$op],$clean);
} else {
$aout= array();
foreach($_POST[$idxk] as $key=>$val) {
if($val !='') $aout[$key]= clean($val,$clean);
}
}
} else $aout= clean($_POST[$idxk],$clean);
if($op=='i') return isset($aout);
if($op=='e') return empty($aout);
if($op=='!i') return !isset($aout);
if($op=='!e') return !empty($aout);
return $aout;
}
function form($url, $enc='') {
global $path;
return "<form action='".$path.$url."' method='post'".($enc==1 ? " enctype='multipart/form-data'":"").">";
}
function menu($db, $tb="",$left="",$sp=array()) {
global $path;
$f=1;$nrf_op='';
while($f<100) {
$nrf_op.= "<option value='$f'>$f</option>";
++$f;
}
$str= "<div class='l2'><a href='{$path}'>List DBs</a> | <a href='{$path}31/$db'>Export</a> | <a href='{$path}5/$db'>List Tables</a>".
($tb==""?"</div>":" || <a href='{$path}10/$db/$tb'>Structure</a> | <a href='{$path}21/$db/$tb'>Browse</a> | <a href='{$path}26/$db/$tb'>Empty</a> | <a href='{$path}27/$db/$tb'>Drop</a> | <a href='{$path}28/$db/$tb'>Optimize</a></div>").
"<div class='l3'>DB: <b>$db</b>".($tb==""?"":" || Table: <b>$tb</b>").(count($sp) >1 ?" || ".$sp[0].": <b>".$sp[1]."</b>":"")."</div><div class='scroll'>";
if($left==1) $str .= "<table><tr><td class='c1 left'>
<table><tr><td class='th'>Query</td></tr><tr><td>".form("30/$db")."<textarea name='qtxt'></textarea><br/><button type='submit'>Do</button></form></td></tr>
<tr><td class='th'>Import SQL, CSV</td></tr>
<tr><td>".form("30/$db",1)."<input type='file' name='importfile' />
<input type='hidden' name='send' value='ja' /><br/><button type='submit'>Do</button></form></td></tr>
<tr><td class='th'>Create Table</td></tr>
<tr><td>".form("7/$db")."Table Name<br/><input type='text' name='ctab' /><br/>
Number of fields<br/><select name='nrf'>".$nrf_op."</select><br/><button type='submit'>Create</button></form></td></tr>
<tr><td class='th'>Rename DB</td></tr><tr><td>".form("3/$db")."<input type='text' name='rdb' /><br/><button type='submit'>Rename</button></form></td></tr>".(version_compare(PHP_VERSION,'5.0.0','<')?"":"
<tr><td class='th'>Create</td></tr><tr><td><a href='{$path}40/$db'>View</a> | <a href='{$path}41/$db'>Trigger</a> | <a href='{$path}42/$db'>Routine</a> | <a href='{$path}43/$db'>Event</a></td></tr>")."</table></td><td>";
return $str;
}
$stru= "<table class='a1'><tr><th colspan=".(isset($sg[0]) && $sg[0]==12?9:8).">TABLE STRUCTURE</th></tr><tr><th class='pro'>FIELD</th><th class='pro'>TYPE</th><th class='pro'>VALUE</th><th class='pro'>ATTRIBUTES</th><th class='pro'>NULL</th><th class='pro'>DEFAULT</th><th class='pro'>COLLATION</th><th class='pro'>AUTO <input type='radio' name='ex[]'/></th>".(isset($sg[0]) && $sg[0]==12?"<th class='pro'>POSITION</th>":"")."</tr>";
$inttype= array(''=>'&nbsp;','UNSIGNED'=>'UNSIGNED','ZEROFILL'=>'ZEROFILL','UNSIGNED ZEROFILL'=>'UNSIGNED ZEROFILL');
$prvs= array('SELECT','INSERT','UPDATE','DELETE','CREATE','DROP','REFERENCES','INDEX','ALTER','CREATE TEMPORARY TABLES','LOCK TABLES','FILE','EXECUTE','RELOAD','SHUTDOWN','PROCESS','SHOW DATABASES','SUPER','REPLICATION SLAVE','REPLICATION CLIENT');
$fieldtype= array('Numbers'=>array('INT','TINYINT','SMALLINT','MEDIUMINT','BIGINT','DOUBLE','DECIMAL','FLOAT'),'Strings'=>array('VARCHAR','CHAR','TEXT','TINYTEXT','MEDIUMTEXT','LONGTEXT'),'DateTime'=>array('DATE','DATETIME','TIME','TIMESTAMP','YEAR'),'Binary'=>array('BIT','BLOB','TINYBLOB','MEDIUMBLOB','LONGBLOB'),'Lists'=>array('ENUM','SET'));
function fieldtype($slct='') {
	global $fieldtype;
	$ft='';
	foreach($fieldtype as $fdk=>$fdtype) {
	if(is_array($fdtype)) {
	$ft .= "<optgroup label='$fdk'>";
	foreach($fdtype as $fdty) $ft .= "<option value='$fdty'".(($slct!='' && $fdty==$slct)?" selected":"").">$fdty</option>";
	$ft .= "</optgroup>";
	}
	}
	return $ft;
}
function redir($way='', $msg=array()) {
	global $path;
	if(count($msg) > 0) {
	foreach($msg as $ks=>$ms) $_SESSION[$ks]= $ms;
	}
	header('Location: '.$path.$way);exit;
}
function sanitize($el) {
	return preg_replace(array('/[^A-Za-z0-9]/'),'_',trim($el));
}
function check($level=array(), $param=array()) {
	global $sg, $con, $v2, $salt, $iv, $u_db, $u_pr, $u_pri;
	if(!empty($_SESSION['token']) && !empty($_SESSION['user'])) {//check login
		$token = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, md5($salt.$_SERVER['HTTP_USER_AGENT']), base64_decode($_SESSION['token']), MCRYPT_MODE_ECB, $iv);
		$usr = $_SESSION['user'];
		list($ho, $pwd) = explode("*#*", $token);
		$pwd = trim($pwd);
		$con = mysql_connect($ho, $usr, $pwd);
		if($con) {
		if(version_compare(PHP_VERSION,'5.1.0','>=')) {
			session_regenerate_id(true);
		} else {
			$token= $_SESSION['token'];
			$hho= $_SESSION['host'];
			session_unset();
			session_destroy();
			session_start();
			session_regenerate_id();
			$_SESSION['user']= $usr;
			$_SESSION['host']= $hho;
			$_SESSION['token']= $token;
		}
		} else {
		redir("50",array('err'=>"Wrong user account, try again"));
		}
	} else {
		redir("50");
	}
	$v1= mysql_get_server_info();
	$vv2= preg_split("/[\-]+/", $v1, -1, PREG_SPLIT_NO_EMPTY);//mysql version
	$v2= $vv2[0];
	if(version_compare($v1, '4.1.1', '<')) die('Require MySQL 4.1.1 or higher');
	
	//privileges
	$u_pr=array();$u_db=array();
	$q_upri = mysql_query("SHOW GRANTS FOR '{$usr}'@'{$ho}'");
	while($r_upri= mysql_fetch_row($q_upri)) {
	preg_match('/^GRANT\s(.*)\sON\s(.*)\./i', $r_upri[0], $upr);
	$u_pr[]= $upr[1];$u_db[]= $upr[2];
	}
	array_shift($u_db);
	$us_db=array();
	foreach($u_db as $udb) {
	$us_db[]= str_replace("`","",$udb);
	}
	$u_db=$us_db;//user dbs
	if(!empty($u_pr[1])) $u_pri= explode(",",$u_pr[1]);
	
	$u_root= array(52,53,54,55);//restrict user management
	if($u_pr[0] == "USAGE" && in_array($sg[0],$u_root) && !in_array('CREATE USER',$u_pri)) redir('',array("err"=>"Access denied"));

	if(isset($sg[1])) $db= $sg[1];
	if(in_array(1,$level)) {//check db
		if($u_pr[0] == "USAGE") {//limited user
		if(!in_array($db,$u_db)) redir();
		}
		$s = mysql_select_db($db, $con);
		if(!$s) redir();
	}
	if(in_array(2,$level)) {//check table
		$q_com = mysql_query("SHOW TABLE STATUS FROM $db like '".$sg[2]."'");
		while($r_com = mysql_fetch_assoc($q_com)) {
		if($r_com['Comment']=='VIEW' && $sg[0] != 21) redir("5/".$db);//prevent to show view as table
		}
		$q_ = mysql_query("SELECT count(*) FROM ".$sg[2]);
		if(!$q_) redir("5/".$db);
	}
	if(in_array(3,$level)) {//check field
		$tb = $sg[2];
		$qr = mysql_query("SHOW FIELDS FROM {$db}.{$tb} LIKE '".$sg[3]."'");
		if(!mysql_num_rows($qr)) redir($param['redir']."/$db/".$tb);
	}
	if(in_array(4,$level)) {//check paginate
		if(!is_numeric($param['pg']) || $param['pg'] > $param['total'] || $param['pg'] < 1) redir($param['redir']);
	}
	if(in_array(5,$level)) {//check spp
		$q_com = mysql_query("SHOW TABLE STATUS FROM $db like '".$sg[2]."'");
		if(mysql_num_rows($q_com)) {
		while($r_com = mysql_fetch_assoc($q_com)) {
		if($r_com['Comment']!='VIEW' && $sg[0] != 21) redir("5/".$db);//prevent to show table
		}
		}
		$sp= array('view','trigger','procedure','function','event');
		if(!in_array($sg[3],$sp)) redir("5/".$db);//check type of routine
		$tb = $sg[2];
		if($sg[3] == $sp[0]) {//check view
			$q = mysql_query("SELECT count(*) FROM ".$tb);
			if(!$q) redir("5/".$db);
		} elseif($sg[3] == $sp[1]) {//check trigger
			$q = mysql_query("SHOW TRIGGERS FROM $db WHERE `Trigger`='$tb'");
			$r = mysql_fetch_row($q);
			if($tb != $r[0]) redir("5/".$db);
		} elseif($sg[3] == $sp[4]) {//check event
			$q = mysql_query("SHOW EVENTS FROM $db LIKE '$tb'");
			$r = mysql_fetch_row($q);
			if($tb != $r[1]) redir("5/".$db);
		} else {//check proc, func
			$q = mysql_query("SHOW ".$sg[3]." STATUS WHERE `Db`='$db' AND `Name`='$tb'");
			$r = mysql_fetch_row($q);
			if($tb != $r[1]) redir("5/".$db);
		}
	}
	if(in_array(6,$level)) {//check user
	if(empty($sg[2])) {
	$u1='';$h1=base64_decode($sg[1]);
	} else {
	$u1=$sg[1];$h1=base64_decode($sg[2]);
	}
	$q_exist = mysql_query("SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user='$u1' AND host='$h1');");
	$r_exist = mysql_fetch_row($q_exist);
	if($r_exist[0] != 1) redir("52");
	}
}
function pg_number($pg, $totalpg) {
	global $path, $sg, $pg_lr;
	if($totalpg > 1) {
	$kl= ($pg > $pg_lr ? $pg-$pg_lr:1);//left pg
	$kr= (($pg > $totalpg-$pg_lr) ? $totalpg:$pg+$pg_lr);//right pg
	if($sg[0]==21) $link= $path."21/".$sg[1]."/".$sg[2];
	elseif($sg[0]==5) $link= $path."5/".$sg[1];
	$pgs='';
	while($kl <= $kr) {
		$pgs .= (($kl == $pg) ? " <b>".$kl."</b> | " : " <a href='$link/$kl'>$kl</a> | ");
		++$kl;
	}
	$lft= ($pg>1?"<a href='$link/1'>First</a> | <a href='$link/".($pg-1)."'>Prev</a> |":"");
	$rgt= ($pg < $totalpg?"<a href='$link/".($pg+1)."'>Next</a> | <a href='$link/$totalpg'>Last</a>":"");
	return $lft.$pgs.$rgt;
	}
}

if(!isset($sg[0])) $sg[0]=0;
switch($sg[0]) {
default:
case "": //show DBs
	check();
	echo $head."<div class='scroll'><table><tr><td class='c1'>
	Create Database".form("2")."<input type='text' class='a1' name='dbc' /><br/>
	<button type='submit'>Create</button></form></td><td>
	<table class='a'><tr><th>DATABASES</th><th>TABLES</th><th>ACTIONS</th>";
	if($u_pr[0] == 'USAGE') {
		sort($u_db);
		foreach($u_db as $r_db) {
		$bg=($bg==1)?2:1;
		$q_tbs= mysql_query("SHOW TABLES FROM ".$r_db);
		echo "<tr class='r c$bg'><td>".$r_db."</td><td>".mysql_num_rows($q_tbs)."</td><td>
		<a href='{$path}31/".$r_db."'>Exp</a> | ".
		(in_array($r_db, $deny) ? "":"<a".$del." href='{$path}4/".$r_db."'>Drop</a> | ").
		"<a href='{$path}5/".$r_db."'>Browse</a></td></tr>";
		}
	} else {
	$q_db = mysql_query("SHOW DATABASES");
	while($r_db= mysql_fetch_row($q_db)) {
		$bg=($bg==1)?2:1;
		$q_tbs= mysql_query("SHOW TABLES FROM ".$r_db[0]);
		echo "<tr class='r c$bg'><td>".$r_db[0]."</td><td>".mysql_num_rows($q_tbs)."</td><td>
		<a href='{$path}31/".$r_db[0]."'>Exp</a> | ".
		(in_array($r_db[0], $deny) ? "":"<a".$del." href='{$path}4/".$r_db[0]."'>Drop</a> | ").
		"<a href='{$path}5/".$r_db[0]."'>Browse</a></td></tr>";
	}
	}
	echo "</table></td></tr></table>";
break;

case "2": //created DB
	check();
	if(post('dbc','!e')) {
	$db= sanitize(post('dbc'));
	$q_cc = mysql_query("CREATE DATABASE ".$db);
	if($q_cc) redir("",array('ok'=>"Created DB"));
	redir("",array('err'=>"Create DB failed"));
	}
	redir("",array('err'=>"DB name must not be empty"));
break;

case "3": //rename DB
	check(array(1));
	$db= $sg[1];
	if(post('rdb','!e') && sanitize(post('rdb')) != $db) {
		$ndb = sanitize(post('rdb'));
		$q_dbcheck= mysql_query("SHOW DATABASES LIKE '$ndb'");
		$r_dbcheck= mysql_fetch_row($q_dbcheck);
		if($r_dbcheck[0]) redir("",array('err'=>"Cannot rename, DB already exist"));
		$q_ren = mysql_query("CREATE DATABASE ".$ndb);//create DB
		if(!$q_ren) redir("",array('err'=>"Rename DB failed"));
		if(version_compare($v2,'5.0.45','<')) {
			$q_dbt = mysql_query("SHOW TABLES FROM ".$db);
			if($q_dbt) {
			while ($r_dbt = mysql_fetch_row($q_dbt)) {//move tables in new DB
				mysql_query('ALTER TABLE '.$db.'.'.$r_dbt[0].' RENAME '.$ndb.'.'.$r_dbt[0]);
			}
			}
		} else {
		//table
		$q_tb = mysql_query("SELECT TABLE_NAME,TABLE_TYPE FROM information_schema.TABLES WHERE `TABLE_SCHEMA`='$db'");
		if(mysql_num_rows($q_tb)) {
		while($r_tb = mysql_fetch_row($q_tb)) {
			if($r_tb[1] != 'VIEW') {
			mysql_query("CREATE TABLE ".$ndb.".".$r_tb[0]." LIKE ".$db.".".$r_tb[0]);
			mysql_query("INSERT ".$ndb.".".$r_tb[0]." SELECT * FROM ".$db.".".$r_tb[0]);
			}
		}
		}
		//routine
		mysql_query("UPDATE mysql.proc SET db='$ndb' WHERE db='$db'");
		//event
		if(version_compare($v2,'5.1.6','>=')) {
		mysql_query("UPDATE mysql.event SET db='$ndb' WHERE db='$db'");
		}
		//trigger
		$q_tg = mysql_query("SHOW TRIGGERS FROM ".$db);
		if(mysql_num_rows($q_tg)) {
		while($r_tg = mysql_fetch_row($q_tg)) {
		mysql_select_db($ndb, $con);
		mysql_query("CREATE TRIGGER `".$r_tg[0]."` ".$r_tg[4]." ".$r_tg[1]." ON `".$r_tg[2]."` FOR EACH ROW ".$r_tg[3]);
		}
		}
		//view
		$q_vi = mysql_query("SELECT TABLE_NAME,VIEW_DEFINITION FROM information_schema.VIEWS WHERE `TABLE_SCHEMA`='$db'");
		if(mysql_num_rows($q_vi)) {
		while($r_vi = mysql_fetch_row($q_vi)) {
			mysql_query("CREATE VIEW `$ndb`.`".$r_vi[0]."` AS ".str_replace("`".$db."`", "`".$ndb."`", $r_vi[1]));
		}
		}
		}
		//drop old DB
		mysql_query('DROP DATABASE '.$db);
		redir("",array('ok'=>"Successfully renamed"));
	}
	redir("5/".$db,array('err'=>"DB name must not be empty"));
break;

case "4": //Drop DB
	check(array(1));
	$db= $sg[1];
	if(!in_array($db, $deny)) {
	$q_drodb = mysql_query("DROP DATABASE ".$db);
	if($q_drodb) redir("",array('ok'=>"Succeful deleted DB"));
	}
	redir("",array('err'=>"Can't delete DB"));
break;

case "5": //Show Tables
	check(array(1));
	$db= $sg[1];
	$q_tbs= mysql_query("SHOW TABLES FROM ".$db);
	//paginate
	$ttalr = mysql_num_rows($q_tbs);
	if($ttalr > 0) {
	$ttalpg = ceil($ttalr/$step);
	if(empty($sg[2])) {
		$pg = 1;
	} else {
		$pg = $sg[2];
		check(array(4),array('pg'=>$pg,'total'=>$ttalpg,'redir'=>'5/'.$db));
	}
	}

	echo $head.menu($db,'',1);
	if($ttalr > 0) {//start rows
	echo "<table class='a'><tr><th>TABLE</th><th>RECORDS</th><th>ENGINE</th><th>COLLATE</th><th>COMMENTS</th><th>ACTIONS</th></tr>";
	$tables = array();
	while($r_tbs = mysql_fetch_row($q_tbs)) {
		$tables[] = $r_tbs[0];
	}

	$ofset= ($pg - 1) * $step;
	$max= $step + $ofset;
	while($ofset < $max) {
		if(!empty($tables[$ofset])) {
		$bg=($bg==1)?2:1;
		$q_tab = mysql_query("SELECT count(*) FROM ".$tables[$ofset]);
		if($q_tab) {
		$q_tabnr = mysql_fetch_row($q_tab);
		$r_nr = $q_tabnr[0];
		} else $r_nr = 0;

		$q_com = mysql_query("SHOW TABLE STATUS FROM $db like '".$tables[$ofset]."'");
		while($r_cm = mysql_fetch_assoc($q_com)) {
		$_vl = "/$db/".$tables[$ofset];
		if($r_cm['Comment']=='VIEW') {
			$lnk="45{$_vl}/view";
			$dro="49{$_vl}/view";
		} else {
			$lnk="10".$_vl;
			$dro="27".$_vl;
		}
		echo "<tr class='r c$bg'><td>".$tables[$ofset]."</td><td>".$r_nr."</td><td>".$r_cm['Engine']."</td><td>".$r_cm['Collation']."</td><td>".$r_cm['Comment']."</td><td><a href='".$path.$lnk."'>Structure</a> | <a href='{$path}$dro'>Drop</a> | <a href='{$path}21/$db/".$tables[$ofset]."'>Browse</a></td></tr>";
		}
		}
		++$ofset;
	}
	echo "</table>".pg_number($pg, $ttalpg);

	if(version_compare($v2,'5.0.45','>=')) {//sp start
	$tsp ='';
	$spps = array('procedure','function');
	$q_sp = array();
	foreach($spps as $spp){
		$q_spp = mysql_query("SHOW {$spp} STATUS");
		while($r_spp = mysql_fetch_row($q_spp)) {
		if($r_spp[0] == $db) {
			$tsp=1;
			$q_sp[] = $r_spp;
		}
		}
	}
	if($tsp==1) {
		echo "<table class='a mrg'><tr><th>ROUTINE</th><th>TYPE</th><th>COMMENTS</th><th>ACTIONS</th></tr>";
		foreach($q_sp as $r_sp){
			$bg=($bg==1)?2:1;
			if($r_sp[0]==$db) {
			echo "<tr class='r c$bg'><td>".$r_sp[1]."</td><td>".$r_sp[2]."</td><td>".$r_sp[7]."</td><td><a href='{$path}45/".$r_sp[0]."/".$r_sp[1]."/".strtolower($r_sp[2])."'>Edit</a> | <a href='{$path}49/".$r_sp[0]."/".$r_sp[1]."/".strtolower($r_sp[2])."'>Drop</a></td></tr>";
			}

		}
		echo "</table>";
	}
	$q_trg=mysql_query("SHOW TRIGGERS FROM ".$db);//show triggers
	if(mysql_num_rows($q_trg)) {
		echo "<table class='a mrg'><tr><th>TRIGGER</th><th>TABLE</th><th>TIMING</th><th>EVENT</th><th>ACTIONS</th></tr>";
		while($r_tg = mysql_fetch_row($q_trg)) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg'><td>".$r_tg[0]."</td><td>".$r_tg[2]."</td><td>".$r_tg[4]."</td><td>".$r_tg[1]."</td><td><a href='{$path}45/$db/".$r_tg[0]."/trigger'>Edit</a> | <a href='{$path}49/$db/".$r_tg[0]."/trigger'>Drop</a></td></tr>";
		}
	echo "</table>";
	}
	}//sp end
	if(version_compare($v2,'5.1.6','>=')) {//events
	$q_eve=mysql_query("SHOW EVENTS FROM ".$db);
	if(mysql_num_rows($q_eve)) {
	echo "<table class='a mrg'><tr><th>EVENT</th><th>SCHEDULE</th><th>START</th><th>END</th><th>ACTIONS</th></tr>";
	while($r_eve = mysql_fetch_assoc($q_eve)) {
	$bg=($bg==1)?2:1;
	echo "<tr class='r c$bg'><td>".$r_eve['Name']."</td><td>".
	($r_eve['Type']=='RECURRING' ? "Every ".$r_eve['Interval value'].$r_eve['Interval field']."</td><td>".$r_eve['Starts']."</td><td>".$r_eve['Ends']:"AT </td><td>".$r_eve['Execute at']."</td><td>")."</td><td><a href='{$path}45/$db/".$r_eve['Name']."/event'>Edit</a> | <a href='{$path}49/$db/".$r_eve['Name']."/event'>Drop</a></td></tr>";
	}
	echo "</table>";
	}
	}

	}//end rows
	echo "</td></tr></table>";
break;

case "7": //Create table
	check(array(1));
	$db= $sg[1];
	if(post('ctab','!e') && post('nrf','!e') && is_numeric(post('nrf')) && post('nrf')>0 ) {
		echo $head.menu($db);
		if(post('crtb','i')) {
			$qry1 = "Create TABLE ".sanitize(post('ctab'))."(";
			for ($nf=0;$nf<post('nrf');$nf++) {
				$c1=post('fi'.$nf); $c2=post('ty'.$nf);
				$c3=(post('va'.$nf,'!e') ? "(".post('va'.$nf).")" : "");
				$c4=(post('at'.$nf,'!e') ? " ".post('at'.$nf):"");
				$c5=post('nc'.$nf);
				$c6=(post('de'.$nf,'!e') ? " default '".post('de'.$nf)."'":"");
				$c7=(post('ex','!e') && post('ex',0)!='on' && post('ex',0)==$nf ? " AUTO_INCREMENT PRIMARY KEY":"");
				$c8=(post('clls'.$nf,'!e') ? " collate ".post('clls'.$nf):"");
				$qry1 .= $c1." ".$c2.$c3.$c4." ".$c5.$c6.$c7.$c8.",";
			}
			$qry2 = substr($qry1,0,-1);
			$qry = $qry2.")".(post('tcomm')!="" ? " COMMENT='".post('tcomm')."'":"").";";
			echo "<p class='box'>".(mysql_query($qry) ? "<b>OK!</b> $qry" : "<b>FAILED!</b> $qry")."</p>";
		} else {
			echo form("7/$db")."
			<input type='hidden' name='ctab' value='".sanitize(post('ctab'))."'/>
			<input type='hidden' name='nrf' value='".post('nrf')."'/>".$stru;
			for ($nf=0;$nf<post('nrf');$nf++) {
				$bg=($bg==1)?2:1;
				echo "<tr class='c$bg'><td><input type='text' name='fi".$nf."' /></td>
				<td><select name='ty".$nf."'>".fieldtype()."</select></td>
				<td><input type='text' name='va".$nf."' /></td><td><select name='at".$nf."'>";
				foreach($inttype as $intk=>$intt) {
				echo "<option value='$intk'>$intt</option>";
				}
				echo "</select></td>
				<td><select name='nc".$nf."'><option value='NOT NULL'>NOT NULL</option><option value='NULL'>NULL</option></select></td>
				<td><input type='text' name='de".$nf."' /></td><td><select name='clls".$nf."'><option value=''>&nbsp;</option>";
				$q_colls = mysql_query("SHOW COLLATION");
				while($r_clls = mysql_fetch_row($q_colls)) {
					echo "<option value=".$r_clls[0].">".$r_clls[0]."</option>";
				}
				echo "</select></td><td><input type='radio' name='ex[]' value='$nf' /></td></tr>";
			}
			echo "<tr><td class='div' colspan=8>Table Comment: <input type='text' maxlength='60' size='72' name='tcomm' /></td></tr><tr><td colspan=9><button type='submit' name='crtb'>Create Table</button></td></tr></table></form>";
		}
	} else {
		redir("5/".$db,array('err'=>"Create table failed"));
	}
break;

case "9":
	check(array(1,2));
	$db= $sg[1];
	$tb= $sg[2];
	if(post('cll','i')) {//change table collation
		$q_altcll = mysql_query('ALTER TABLE '.$db.'.'.$tb.' CONVERT TO CHARACTER SET '.strtok(post('cll'),'_').' COLLATE '.post('cll'));
		if($q_altcll) redir("10/$db/".$tb, array('ok'=>"Successfully changed"));
		redir("10/$db/".$tb, array('err'=>"Can't change Collate"));
	}
	if(post('copytab','!e')) {//copy table in new DB
		$ndb = post('copytab');
		$q_altcrt = mysql_query("CREATE TABLE ".$ndb.".".$tb." LIKE ".$db.".".$tb);
		$q_altins = mysql_query("INSERT ".$ndb.".".$tb." SELECT * FROM ".$db.".".$tb);
		if($q_altcrt && $q_altins) redir("10/$db/".$tb, array('ok'=>"Successfully copied"));
		redir("10/$db/".$tb, array('err'=>"Copy table failed"));
	}
	if(post('rtab','!e')) {//rename table
		$ntb = sanitize(post('rtab'));
		$q_creatt = mysql_query("SELECT count(*) FROM ".$ntb);
		if(!$q_creatt) {//prevent create duplicate
		if(version_compare($v2,'5.0.45','<')) {
			mysql_query('ALTER TABLE '.$tb.' RENAME '.$ntb);
		} else {
		//create table
		$q_ttab = mysql_query("SELECT TABLE_NAME,TABLE_TYPE FROM information_schema.TABLES WHERE `TABLE_SCHEMA`='$db' AND `TABLE_NAME`='$tb'");
		$r_ttr = mysql_fetch_row($q_ttab);
		mysql_query("CREATE TABLE ".$ntb." LIKE ".$r_ttr[0]);
		mysql_query("INSERT INTO ".$ntb." SELECT * FROM ".$r_ttr[0]);
		//rename table in view
		$q_vtb = mysql_query("SELECT TABLE_NAME,VIEW_DEFINITION FROM information_schema.VIEWS WHERE `TABLE_SCHEMA`='$db'");
		if(mysql_num_rows($q_vtb)) {
		while($r_tv = mysql_fetch_row($q_vtb)) {
			mysql_query("DROP VIEW IF EXISTS ".$db.".".$r_tv[0]);
			mysql_query("CREATE VIEW `$db`.`".$r_tv[0]."` AS ".str_replace("`".$tb."`", "`".$ntb."`", $r_tv[1]));
		}
		}
		//rename table in procedure
		$q_prc = mysql_query("SELECT body FROM mysql.proc WHERE db='$db'");
		if(mysql_num_rows($q_prc)) {
		while($r_pr = mysql_fetch_row($q_prc)) {
			$reptb = str_replace(" ".$tb." ", " ".$ntb." ", $r_pr[0]);
			mysql_query("UPDATE mysql.proc SET body='$reptb'".(version_compare($v2,'5.1.6','>=')?",body_utf8='$reptb'":"")." WHERE `db`='$db'");
		}
		}
		if(version_compare($v2,'5.1.6','>=')) {
		//rename table in event
		$q_evn = mysql_query("SELECT body FROM mysql.event WHERE db='$db'");
		if(mysql_num_rows($q_evn)) {
		while($r_evn = mysql_fetch_row($q_evn)) {
			$ntt = str_replace(" ".$tb." ", " ".$ntb." ", $r_evn[0]);
			mysql_query("UPDATE mysql.event SET body='$ntt',body_utf8='$ntt' WHERE `db`='$db'");
		}
		}
		}
		//rename table in triggers
		$q_trg=mysql_query("SHOW TRIGGERS FROM ".$db);
		if(mysql_num_rows($q_trg)) {
		while($r_trg = mysql_fetch_row($q_trg)) {
		mysql_query("DROP TRIGGER IF EXISTS ".$db.".".$r_trg[0]);
		mysql_query("CREATE TRIGGER `".$r_trg[0]."` ".$r_trg[4]." ".$r_trg[1]." ON `".$ntb."` FOR EACH ROW ".$r_trg[3]);
		}
		}
		//drop table
		mysql_query("DROP TABLE ".$tb);
		}
		} else redir("5/$db",array('err'=>"Table already exist"));
	}
	if(post('n1','!e') && post('n2','!e')) {//reorder
		$q_fel= mysql_query("SHOW FULL FIELDS FROM {$tb} LIKE '".post('n1')."'");
		while($r_fel = mysql_fetch_assoc($q_fel)) {
		if(empty($r_fel['Extra'])) mysql_query("ALTER TABLE $tb MODIFY COLUMN ".post('n1')." ".$r_fel['Type']." AFTER ".post('n2'));
		}
		exit;
	}
	if(post('idx','!e') && is_array(post('idx'))) {//create index
		$idx= '`'.implode('`,`',post('idx')).'`';
		$idxn= implode('_',post('idx'));
		if(post('primary','i')) {
			$q = mysql_query("ALTER TABLE $tb DROP PRIMARY KEY, ADD PRIMARY KEY($idx)");
			if(!$q) mysql_query("ALTER TABLE $tb ADD PRIMARY KEY($idx)");
		} elseif(post('unique','i')) {
			mysql_query("ALTER TABLE $tb ADD UNIQUE KEY($idx)");
		} elseif(post('index','i')) {
			mysql_query("ALTER TABLE $tb ADD INDEX($idx)");
		} elseif(post('fulltext','i')) {
			mysql_query("ALTER TABLE $tb ADD FULLTEXT INDEX($idx)");
		}
		redir("10/$db/$tb",array('ok'=>"Successfully created"));
	}
	if(isset($sg[3])) {//drop index
		if($sg[3] == "PRIMARY") {
			mysql_query("ALTER TABLE `".$tb."` DROP PRIMARY KEY");
		} else {
			$q_key = mysql_query("SHOW KEYS FROM ".$tb);
			if($q_key) {
			while($r_key = mysql_fetch_assoc($q_key)) {
			if($r_key['Key_name'] == $sg[3]) mysql_query('ALTER TABLE '.$tb.' DROP INDEX '.$r_key['Key_name']);
			}
			}
		}
		redir("10/$db/".$tb,array('ok'=>"Successfully dropped"));
	}
	redir("5/".$db);
break;

case "10": //table structure
	check(array(1,2));
	$db= $sg[1];
	$tb= $sg[2];
	echo $head.menu($db, $tb, 1);
	echo form("9/$db/$tb")."<table class='a'><tr><th colspan=8>TABLE STRUCTURE</th></tr><tr><th><input type='checkbox' onclick='toggle(this,\"idx[]\")' /></th><th class='pro'>FIELD</th><th class='pro'>TYPE</th><th class='pro'>NULL</th><th class='pro'>COLLATION</th><th class='pro'>DEFAULT</th><th class='pro'>EXTRA</th><th class='pro'>ACTIONS</th></tr><tbody id='allord'>";
	$q_fi= mysql_query("SHOW FULL FIELDS FROM ".$tb);
	$r_filds= mysql_num_rows($q_fi);
	$h=1;
	while($r_fi = mysql_fetch_assoc($q_fi)) {
		$bg=($bg==1)?2:1;
		echo "<tr class='r c$bg' id='".$r_fi['Field']."'><td><input type='checkbox' name='idx[]' value='".$r_fi['Field']."' /></td><td class='pro'>".$r_fi['Field']."</td><td class='pro'>".$r_fi['Type']."</td><td class='pro'>".$r_fi['Null']."</td>";
		echo "<td class='pro'>".($r_fi['Collation']!='NULL' ? $r_fi['Collation']:'')."</td>";
		echo "<td class='pro'>".$r_fi['Default']."</td><td class='pro'>".$r_fi['Extra']."</td><td class='pro'><a href='{$path}11/$db/$tb/".$r_fi['Field']."'>change</a> | <a href='{$path}13/$db/$tb/".$r_fi['Field']."'>drop</a> | <a href='{$path}12/$db/$tb/".$r_fi['Field']."'>add field</a>";
		if($r_filds>1){
		$lim=" | ";
		$up= "<a class='move up' title='Up'>&#9650;</a>";
		$down= "<a class='move down' title='Down'>&#9660;</a>";
		if($h == 1) echo $lim.$down;
		elseif($h == $r_filds) echo $lim.$up;
		else echo $lim.$up.$down;
		}
		echo "</td></tr>";
		++$h;
	}
	echo "</tbody><tr><td class='div' colspan=8>
	<button type='submit' name='primary'>Primary</button> <button type='submit' name='index'>Index</button> <button type='submit' name='unique'>Unique</button> <button type='submit' name='fulltext'>Fulltext</button></td></tr></table></form>
	<table class='a mrg'><tr><th colspan=4>TABLE INDEX</th></tr><tr><th class='pro'>KEY NAME</th><th class='pro'>FIELD</th><th class='pro'>TYPE</th><th class='pro'>ACTIONS</th></tr>";
	$q_idx= mysql_query("SHOW KEYS FROM ".$tb);
	if(mysql_num_rows($q_idx)) {
	while($r_idx=mysql_fetch_assoc($q_idx)) {
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
	echo "<tr class='r c$bg'><td class='pro'>".$idxnam."</td><td class='pro'>";
	foreach($idxcol['column'] as $col) echo $col."<br/>";
	echo "</td><td class='pro'>".$idxcol['type'];
	echo "</td><td class='pro'><a href='{$path}9/$db/$tb/".$idxnam."'>drop</a></td></tr>";
	}
	}
	}
	echo "</table><table class='a c1 mrg'><tr><td>Rename Table<br/>".form("9/$db/$tb")."<input type='text' name='rtab' /><br/><button type='submit'>Rename</button></form><br/>Copy Table<br/>".form("9/$db/$tb")."<select name='copytab'>";
	$q_ldb = mysql_query("SHOW DATABASES");
	while($r_ldb = mysql_fetch_row($q_ldb)) {
		echo "<option value=".$r_ldb[0].">".$r_ldb[0]."</option>";
	}
	echo "</select><br/><button type='submit'>Copy</button></form><br/>";
	$q_cll = mysql_query("SHOW TABLE STATUS FROM {$db} like '{$tb}'");
	$r_cll = mysql_fetch_assoc($q_cll);
	$q_cl = mysql_query("SHOW COLLATION");
	echo "Change Table Collation<br/>".form("9/$db/$tb")."<select name='cll'><option value=''>&nbsp;</option>";
	while($r_cl = mysql_fetch_row($q_cl)) {
		if($r_cl[0] == $r_cll['Collation']) {
		echo "<option value='".$r_cl[0]."' selected='selected'>".$r_cl[0]."</option>";
		} else {
		echo "<option value='".$r_cl[0]."'>".$r_cl[0]."</option>";
		}
	}
	echo "</select><br/><button type='submit'>Change</button></form></td></tr></table></td></tr></table>";
break;

case "11": //structure change
	check(array(1,2,3),array('redir'=>10));
	$db= $sg[1];
	$tb= $sg[2];
	if(post('fi','!e') && post('ty','!e')) {//structure update
		$fi=sanitize(post('fi'));
		$fi_=post('fi_');
		$va= (post('va','e') ? "":"(".post('va','',1).")");
		$at= (post('at','e') ? "":" ".post('at'));
		$def=(post('de','e') ? "":" default '".post('de')."'");
		$clls=(post('clls','e') ? "":" collate ".post('clls'));
		if(post('ex','!e') && post('ex',0)==1) {
			$ex=" AUTO_INCREMENT";
			$q_pri= mysql_query("SHOW KEYS FROM {$db}.".$tb);
			if(mysql_num_rows($q_pri)) {
			while($r_pri=mysql_fetch_assoc($q_pri)) {
				if($r_pri['Key_name'] != "PRIMARY" && $r_pri['Column_name'] != $fi_) {
				$ex .= " PRIMARY KEY";
				}
			}
			} else $ex .= " PRIMARY KEY";
		} else $ex="";
		$ok= mysql_query("ALTER TABLE $tb CHANGE ".$fi_." ".sanitize(post('fi'))." ".post('ty').$va.$at." ".post('nc').$def.$clls.$ex);
		if($ok) {
		if(version_compare($v2,'5.0.45','>=')) {
		//replace field in view
		$q_vw = mysql_query("SELECT TABLE_NAME,VIEW_DEFINITION FROM information_schema.VIEWS WHERE `TABLE_SCHEMA`='$db'");
		if(mysql_num_rows($q_vw)) {
		while($r_vw = mysql_fetch_row($q_vw)) {
			if(strrpos($r_vw[1],"`$db`.`$tb`")==true) {
			mysql_query("DROP VIEW IF EXISTS ".$r_vw[0]);
			mysql_query("CREATE VIEW `".$r_vw[0]."` AS ".str_replace("`".$fi_."`", "`".$fi."`", $r_vw[1]));
			}
		}
		}
		//repalce field in trigger
		$q_tge = mysql_query("SHOW TRIGGERS FROM $db WHERE `Table`='$tb'");
		if(mysql_num_rows($q_tge)) {
		while($r_tge = mysql_fetch_row($q_tge)) {
			if($r_tge[2] == $tb) {
			$stt = str_replace($fi_, $fi,$r_tge[3]);
			mysql_query("DROP TRIGGER IF EXISTS ".$db.".".$r_tge[0]);
			mysql_query("CREATE TRIGGER `".$r_tge[0]."` ".$r_tge[4]." ".$r_tge[1]." ON `".$r_tge[2]."` FOR EACH ROW ".$stt);
			}
		}
		}
		//replace field in routine
		$q_pf = mysql_query("SELECT db,name,param_list,body FROM mysql.proc WHERE db='$db'");
		if(mysql_num_rows($q_pf)) {
		while($r_pf = mysql_fetch_row($q_pf)) {
			if(strrpos($r_pf[3],$tb)==true) {
			$plist = str_replace("`".$fi_."`", "`".$fi."`", $r_pf[2]);
			$body = str_replace($fi_, $fi, $r_pf[3]);
			mysql_query("UPDATE mysql.proc SET param_list='{$plist}', body='{$body}'".(version_compare($v2,'5.1.6','>=')?",body_utf8='{$body}'":"")." WHERE db='$db' AND name='".$r_pf[1]."'");
			}
		}
		}
		if(version_compare($v2,'5.1.6','>=')) {
		//replace field in event
		$q_evtn = mysql_query("SELECT db,name,body FROM mysql.event WHERE db='$db'");
		if(mysql_num_rows($q_evtn)) {
		while($r_evtn = mysql_fetch_row($q_evtn)) {
			if(strrpos($r_evtn[2],$tb)==true) {
			$bdy = str_replace($fi_, $fi, $r_evtn[2]);
			mysql_query("UPDATE mysql.event SET body='$bdy',body_utf8='$bdy' WHERE db='$db' AND name='".$r_evtn[1]."'");
			}
		}
		}
		}
		}
		}
		redir("10/$db/".$tb,array('ok'=>"Successfully changed"));
	} else {//structure form
	echo $head.menu($db, $tb);
	echo form("11/$db/$tb/".$sg[3]).$stru;
	$r_fe = mysql_fetch_row(mysql_query("SHOW FULL FIELDS FROM {$db}.{$tb} LIKE '".$sg[3]."'"));
	$fe_type= preg_split("/[()]+/", $r_fe[1], -1, PREG_SPLIT_NO_EMPTY);
	echo "<tr><td><input type='hidden' name='fi_' value='".$r_fe[0]."'/><input type='text' name='fi' value=".$r_fe[0]." /></td>
	<td><select name='ty'>".fieldtype(strtoupper($fe_type[0]))."</select></td>
	<td><input type='text' name='va' value=\"".(isset($fe_type[1])?$fe_type[1]:"")."\" /></td><td><select name='at'>";
	$fe_atr=substr($r_fe[1], strpos($r_fe[1], " ")+1);
	$big= strtoupper($fe_atr);
	foreach($inttype as $b=>$b2) echo "<option value='$b'".($b==$big ? " selected":"").">".$b2."</option>";
	echo "</select></td><td><select name='nc'>";
	$cc = array('NOT NULL','NULL');
	foreach ($cc as $c) echo("<option value='$c' ".(($r_fe[3]=="YES" && $c=="NULL")?"selected":"").">$c</option>");
	echo "</select></td><td><input type='text' name='de' value='".$r_fe[5]."' /></td><td><select name='clls'><option value=''>&nbsp;</option>";
	$q_colls = mysql_query("SHOW COLLATION");
	while($r_cl = mysql_fetch_row($q_colls)) {
		echo "<option value='".$r_cl[0].(($r_fe[2]==$r_cl[0]) ? " selected":"")."'>".$r_cl[0]."</option>";
	}
	echo "</select></td><td><input type='radio' name='ex[]' value='1' ".($r_fe[6]=="auto_increment" ? "checked":"")." /></td>
	</tr><tr><td colspan=9><button type='submit'>Change field</button></td></tr></table></form>";
	}
break;

case "12": //Add field
	check(array(1,2,3),array('redir'=>10));
	$db= $sg[1];
	$tb= $sg[2];
	$id= $sg[3];
	if(post('fi','!e') && post('ty','!e')) {
		$va=(post('va','!e') ? "(".post('va').")" : "");
		$at=(post('at')!=0 ? " ".post('at'):"");
		$def=(post('de','!e') ? " default '".post('de')."' ":"");
		$clls=(post('clls','!e') ? " collate ".post('clls'):"");
		$ex= (post('ex','!e') && post('ex',0)==1 ? " AUTO_INCREMENT PRIMARY KEY":"");
		$col=(post('col')=="FIRST" ? " FIRST":" AFTER ".post('col'));
		mysql_query("ALTER TABLE $tb ADD ".sanitize(post('fi'))." ".post('ty').$va.$at." ".post('nc').$def.$clls.$ex.$col);
		if($e) redir("10/$db/".$tb,array('ok'=>"Successfully added"));
		else redir("10/$db/".$tb,array('err'=>"Add field failed"));
	} else {
	echo $head.menu($db, $tb);
	echo form("12/$db/$tb/$id").$stru.
	"<tr><td><input type='text' name='fi' /></td><td><select name='ty'>".fieldtype()."</select></td><td><input type='text' name='va' /></td><td><select name='at'>";
	foreach($inttype as $ke=>$ar) {
	echo "<option value='$ke'>$ar</option>";
	}
	echo "</select></td>
	<td><select name='nc'><option value='NOT NULL'>NOT NULL</option><option value='NULL'>NULL</option></select></td>
	<td><input type='text' name='de' /></td><td><select name='clls'><option value=''>&nbsp;</option>";
	$q_cls = mysql_query("SHOW COLLATION");
	while($r_cls = mysql_fetch_row($q_cls)) {
		echo "<option value='".$r_cls[0]."'>".$r_cls[0]."</option>";
	}
	echo "</select></td><td><input type='radio' name='ex[]' value='1' /></td>
	<td><select name='col'><option value='".$id."'>after: ".$id."</option><option value='FIRST'>first</option></select></td>
	</tr><tr><td colspan=9><button type='submit'>Add field</button></td></tr></table></form>";
	}
break;

case "13": //Drop field
	check(array(1,2,3),array('redir'=>10));
	$db= $sg[1];
	$tb= $sg[2];
	$fi= $sg[3];
	if(version_compare($v2,'5.0.0','>')) {
	//drop view if have field
	$q_vi = mysql_query("SHOW TABLE STATUS FROM $db");
	while($r_vi = mysql_fetch_assoc($q_vi)) {
	if($r_vi['Comment']=='VIEW') {
	$q_sv= mysql_query("SHOW CREATE VIEW {$db}.".$r_vi['Name']);
	$r_sv= mysql_fetch_row($q_sv);
	if(strpos($r_sv[1],"`$tb`.`$fi`")!==false) mysql_query("DROP VIEW {$db}.".$r_vi['Name']);
	}
	}
	}
	$q_drofd = mysql_query("ALTER TABLE $tb DROP ".$fi);
	if($q_drofd) redir("10/$db/".$tb, array('ok'=>"Successfully deleted"));
	redir("10/$db/".$tb, array('err'=>"Field delete failed"));
break;

case "21": //table browse
	check(array(1,2));
	$db= $sg[1];
	$tb= $sg[2];
	mysql_query("SET NAMES utf8");
	//paginate
	$q_resul= mysql_query("SELECT * FROM ".$tb);
	$totalr= mysql_num_rows($q_resul);
	$totalpg= ceil($totalr/$step);
	if(empty($sg[3])) {
		$pg = 1;
	} else {
		$pg= $sg[3];
		check(array(1,4),array('pg'=>$pg,'total'=>$totalpg,'redir'=>"21/$db/$tb"));
	}
	$q_vi = mysql_query("SHOW CREATE VIEW ".$tb);
	echo $head;
	echo menu($db, (!$q_vi ? $tb:''), 1);

	echo "<table class='a'><tr>";
	if(!$q_vi){
	echo "<th colspan=2><a href='{$path}22/$db/$tb'>INSERT</a></th>";
	}
	$q_bro= mysql_query("SHOW FIELDS FROM ".$tb);
	$r_cl= mysql_num_rows($q_bro);
	while($r_brw = mysql_fetch_assoc($q_bro)) {
		echo "<th>".$r_brw['Field']."</th>";
	}
	echo "</tr>";

	$offset = ($pg - 1) * $step;
	$q_res = mysql_query("SELECT * FROM $tb LIMIT $offset, $step");
	while($r_rw = mysql_fetch_row($q_res)) {
		$bg=($bg==1)?2:1;
		$nu = mysql_field_name($q_res,0);
		$rw0= base64_encode($r_rw[0]);
		echo "<tr class='r c$bg'>";
		if(!$q_vi){
		echo "<td><a href='{$path}23/$db/$tb/$nu/$rw0'>Edit</a><td><a href='{$path}24/$db/$tb/$nu/$rw0'>Delete</a></td>";
		}
		for($i=0;$i<$r_cl;$i++) {
			$val = stripslashes($r_rw[$i]);
			$bin = mysql_field_flags($q_res,$i);//blob-bin
			if(stristr($bin,"blob binary") == true && !in_array($db,$deny)) {
				echo "<td class='pro'>[binary] ".number_format((strlen($r_rw[$i])/1024),2)." KB</td>";
			} elseif(strlen($val) > 200 ) {
				echo "<td class='pro'>",substr($val,0,200),"...</td>";
			} else {
				echo "<td class='pro'>",$val,"</td>";
			}
		}
		echo "</tr>";
	}
	echo "</table>".pg_number($pg, $totalpg)."</td></tr></table>";
break;

case "22": //table insert
	check(array(1,2));
	$db= $sg[1];
	$tb= $sg[2];
	if(post('save','i')) {
		$q_res= mysql_query("SELECT * FROM ".$tb);
		$r_col= mysql_num_fields($q_res);
			$qr1="INSERT INTO $tb (";
			$qr2="";
			$qr3="VALUES(";
			$qr4="";
			$n = (post('r0','e') ? 1:0);//id auto increment
			while($n<$r_col) {
				$bin = mysql_field_flags($q_res,$n);//blob-bin
				$nme = mysql_field_name($q_res,$n);
				$qr2.=$nme.",";
				if(stristr($bin,"blob binary") === false) {
					$fty = mysql_fetch_assoc(mysql_query("SHOW COLUMNS FROM {$tb} LIKE '{$nme}'"));
					if(substr($fty['Type'],0,3)=='bit') {
					$qr4.= "'".(post('r'.$n,0) ? 1:'')."',";
					} else $qr4.= "'".post('r'.$n,'',1)."',";
				} else {
					if(!empty($_FILES['r'.$n]['tmp_name'])) {
					$blb = addslashes(file_get_contents($_FILES['r'.$n]['tmp_name']));
					$qr4.= "'{$blb}',";
					} else $qr4.= "'',";
				}
				++$n;
			}
			$qr2=substr($qr2,0,-1).") ";
			$qr4=substr($qr4,0,-1).")";
		$q_rins = mysql_query($qr1.$qr2.$qr3.$qr4);
		if($q_rins) redir("21/{$db}/".$tb,array('ok'=>"Successfully inserted"));
		else redir("21/{$db}/".$tb,array('err'=>"Insert failed"));
	} else {
		echo $head.menu($db, $tb, 1);
		echo form("22/$db/$tb",1)."<table class='a'><caption>Insert Row</caption>";
		$q_res= mysql_query("SELECT * FROM ".$tb);
		$r_col= mysql_num_fields($q_res);
		$j=0;
		while($j<$r_col) {
			$bin = mysql_field_flags($q_res,$j);//blob-bin
			$nm = mysql_field_name($q_res,$j);
			echo "<tr><td>{$nm}</td><td>";
			$tty = mysql_fetch_assoc(mysql_query("SHOW COLUMNS FROM {$tb} LIKE '{$nm}'"));
			if(stristr($bin,"enum") == true OR stristr($bin,"set") == true) {//enum
			$enums = explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2", $tty['Type']));
			echo "<select name='r{$j}'>";
			foreach($enums as $enm) {
			echo "<option value='{$enm}'>".$enm."</option>";
			}
			echo "</select>";
			} elseif(substr($tty['Type'],0,3)=='bit') {//bit
			foreach($bbs as $kj=>$bb) {
			echo "<input type='radio' name='r{$j}[]' value='$kj' /> $bb ";
			}
			} elseif(stristr($bin,"blob binary") == true && !in_array($db,$deny)) {//blob-bin
			echo "<input type='file' name='r{$j}'/>";
			} elseif(mysql_field_type($q_res, $j)=="blob") {//blob
			echo "<textarea name='r{$j}'></textarea>";
			} else {
			echo "<input type='text' name='r{$j}' />";
			}
			++$j;
		}
		echo "<tr><td class='c1' colspan=2><button type='submit' name='save'>Save</button></td></tr>
		</table></form></td></tr></table>";
	}
break;

case "23": //table edit row
	check(array(1,2,3),array('redir'=>'21'));
	$db= $sg[1];
	$tb= $sg[2];
	$nu= $sg[3];
	$id= base64_decode($sg[4]);
	if(post('update','i') && $id!=null) {
	$q_re2= mysql_query("SELECT * FROM ".$tb);
	$r_co= mysql_num_fields($q_re2);
		$qr1="UPDATE $tb SET ";
		$qr2="";
		for ($p=0;$p<$r_co;$p++) {
			$bin= mysql_field_flags($q_re2,$p);//blob-bin
			$nme= mysql_field_name($q_re2,$p);
			if(stristr($bin,"blob binary") == true) {
				if(!empty($_FILES["te".$p]['tmp_name'])) {
				$blb = addslashes(file_get_contents($_FILES["te".$p]['tmp_name']));
				$qr2.= $nme."='".$blb."',";
				}
			} else {
				$fty = mysql_fetch_assoc(mysql_query("SHOW COLUMNS FROM {$tb} LIKE '{$nme}'"));
				if(substr($fty['Type'],0,3)=='bit') {
				$qr2.= $nme."='".(post("te".$p,0) ? 1:'')."',";
				} else $qr2.= $nme."='".post("te".$p,'',1)."',";
			}
		}
		$qr2=substr($qr2,0,-1);
		$qr3=" WHERE $nu='$id' LIMIT 1";
		$q_upd = mysql_query($qr1.$qr2.$qr3);
		if($q_upd) redir("21/{$db}/".$tb,array('ok'=>"Successfully updated"));
		else redir("21/{$db}/".$tb,array('err'=>"Update failed"));
	} else {
		echo $head.menu($db, $tb, 1);
		$q_flds = mysql_query("SHOW COLUMNS FROM ".$tb);
		$r_fnr = mysql_num_rows($q_flds);
		$q_rst = mysql_query("SELECT * FROM $tb WHERE $nu='{$id}'");
		if(mysql_num_rows($q_rst) < 1) redir("21/$db/".$tb);
		$r_rx = mysql_fetch_row($q_rst);
		echo form("23/$db/$tb/$nu/".base64_encode($r_rx['0']),1)."<table class='a'><caption>Edit Row</caption>";
		for ($k=0;$k<$r_fnr;$k++) {
			$bin = mysql_field_flags($q_rst,$k);//blob-bin
			$nam = mysql_field_name($q_rst, $k);
			echo "<tr><td>$nam</td><td>";
			$tty = mysql_fetch_assoc(mysql_query("SHOW COLUMNS FROM {$tb} LIKE '{$nam}'"));
			if(stristr($bin,"enum") == true OR stristr($bin,"set") == true) {//enum
			$enums = explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2", $tty['Type']));
			echo "<select name='te{$k}'>";
			foreach($enums as $enm) {
			echo "<option value='{$enm}'".($r_rx[$k]==$enm ? " selected":"").">".$enm."</option>";
			}
			echo "</select>";
			} elseif(substr($tty['Type'],0,3)=='bit') {//bit
			foreach($bbs as $kk=>$bb) {
			echo "<input type='radio' name='te{$k}[]' value='$kk'".($r_rx[$k]==$kk ? " checked":"")." /> $bb ";
			}
			} elseif(stristr($bin,"blob binary") == true && !in_array($db,$deny)) {//blob-bin
			echo "[binary] ".number_format((strlen($r_rx[$k])/1024),2)." KB<br/><input type='file' name='te{$k}'/>";
			} elseif(mysql_field_type($q_rst, $k)=="blob") {//blob
			echo "<textarea name='te{$k}'>".htmlspecialchars($r_rx[$k],ENT_QUOTES)."</textarea>";
			} else {
			echo "<input type='text' name='te{$k}' value='".htmlspecialchars($r_rx[$k],ENT_QUOTES)."' />";
			}
			echo "</td></tr>";
		}
	echo "<tr><td class='c1' colspan=2><button type='submit' name='update'>Update</button></td></tr></table>
	</form></td></tr></table>";
	}
break;

case "24": //table delete row
	check(array(1,2,3),array('redir'=>'21'));
	$db= $sg[1];
	$tb= $sg[2];
	$nu= $sg[3];
	$id= base64_decode($sg[4]);
	$q_delro = mysql_query("DELETE FROM {$tb} WHERE {$nu} = '".$id."' LIMIT 1");
	if($q_delro) redir("21/$db/".$tb,array('ok'=>"Successfully deleted"));
break;

case "26": //table empty
	check(array(1,2));
	$q_trunc = mysql_query("TRUNCATE TABLE ".$sg[2]);
	if($q_trunc) redir("21/".$sg[1]."/".$sg[2],array('ok'=>"Table is empty"));
break;

case "27": //table drop
	check(array(1,2));
	$db= $sg[1];
	$tb= $sg[2];
	mysql_query("DROP TABLE ".$tb);
	if(version_compare($v2,'5.0.45','>=')) {
	//drop view
	$q_rw = mysql_query("SELECT TABLE_SCHEMA, TABLE_NAME, VIEW_DEFINITION FROM information_schema.views WHERE `TABLE_SCHEMA`='$db'");
	if(mysql_num_rows($q_rw)) {
	while($r_rw = mysql_fetch_assoc($q_rw)) {
		$q = mysql_query($r_rw['VIEW_DEFINITION']);
		if(!$q) mysql_query("DROP VIEW ".$r_rw['TABLE_NAME']);
	}
	}
	//drop procedure (function not depend by table)
	$q_rp = mysql_query("SELECT db, name, type, body FROM mysql.proc WHERE `db`='$db' AND `type`='PROCEDURE'");
	while($r_rp = mysql_fetch_assoc($q_rp)) {
		$q = mysql_query($r_rp['body']);
		if(mysql_num_rows($q) < 1) mysql_query("DROP PROCEDURE ".$r_rp['name']);
	}
	//drop event
	if(version_compare($v2,'5.1.6','>=')) {
	$q_evn = mysql_query("SELECT EVENT_SCHEMA,EVENT_NAME,EVENT_DEFINITION FROM information_schema.events WHERE `EVENT_SCHEMA`='$db'");
	while($r_evn = mysql_fetch_assoc($q_evn)) {
		if(preg_match('/'.$tb.'/',$r_evn['EVENT_DEFINITION'])) mysql_query("DROP EVENT {$db}.".$r_evn['EVENT_NAME']);
	}
	}
	}
	redir("5/".$db,array('ok'=>"Successfully dropped"));
break;

case "28": //optimize table
	check(array(1,2));
	$q_optm = mysql_query("OPTIMIZE TABLE ".$sg[2]);
	if($q_optm) redir("5/".$sg[1],array('ok'=>"Successfully optimized"));
break;

case "30"://import
	check(array(1));
	$db= $sg[1];
	mysql_query("SET NAMES utf8");
	$out="<div class='box'>";
	if(post()) {
		$e='';
		$rgex =
"~^\xEF\xBB\xBF|DELIMITER.*?[^ ]|(\#|--).*|([\$].*[^\$])|(?-m)\(([^)]*\)*(\".*\")*('.*'))(*SKIP)(*F)|(?s)(BEGIN.*?END)(*SKIP)(*F)|(?<=;)(?![ ]*$)~im";
		if(post('qtxt','!e')) {//in textarea
			$e= preg_split($rgex, post('qtxt','',1), -1, PREG_SPLIT_NO_EMPTY);
		} elseif(post('send','i') && post('send') == "ja") {//from file
			if(empty($_FILES['importfile']['tmp_name'])) {
			redir("5/$db",array('err'=>"No file to upload"));
			} else {
			$tmp= $_FILES['importfile']['tmp_name'];
			$finame= explode('.',$_FILES['importfile']['name']);
			$ext= strtolower(end($finame));
			if($ext == 'sql') {//sql file
				$fi= clean(file_get_contents($tmp),1);
				$e= preg_split($rgex, $fi, -1, PREG_SPLIT_NO_EMPTY);
			} elseif($ext == 'csv') {//csv file
				$handle= fopen("$tmp","r");
				$data= fgetcsv($handle, 1000000, ",");
				if(empty($data)) redir("5/".$db);
				$fd='';
				for($h=0;$h<count($data);$h++) {
					$fd .= clean($data[$h]).',';
				}
				$fdx= "(".substr($fd,0,-1).")";
				$e= array();
				while(($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
					$num= count($data);
					if($num < 1) redir("5/".$db);
					$import="INSERT INTO ".sanitize($finame[0]).$fdx." VALUES(";
					for ($c=0; $c < $num; ++$c) {
						$import.="'".clean($data[$c])."',";
					}
					$e[] = substr($import,0,-1).");";
				}
				fclose($handle);
				if(empty($e)) redir("5/$db",array('err'=>"Query failed"));
			} else {
				redir("5/$db",array('err'=>"Disallowed extension"));
			}
			}
		} else {
			redir("5/$db",array('err'=>"Query failed"));
		}
		if(is_array($e)) {
			foreach($e as $qry) {
				$qry= trim($qry);
				if(!empty($qry))
				$out .= "<p>".(mysql_query($qry) ? "<b>OK!</b> $qry" : "<b>FAILED!</b> $qry")."</p>";
			}
		}
	}
	echo $head.menu($db).$out."</div>";
break;

case "31": //export form
	check(array(1));
	$db= $sg[1];
	$q_tables= mysql_query("SHOW TABLES FROM ".$db);
	if(mysql_num_rows($q_tables)) {
	echo $head.menu($db);
	echo "<table><tr><td>".form("32/$db")."<table class='a'><tr><th>Export</th></tr><tr><td>
	<table class='a1 wi'><tr><th>Select table(s)</th></tr><tr><td>
	<p><input type='checkbox' onclick='selectall(\"tables\",\"sel\")' id='sel' /> Select/Deselect</p>
	<select class='he' id='tables' name='tables[]' multiple='multiple'>";
	while($r_tts = mysql_fetch_row($q_tables)) {
	echo "<option value='".$r_tts[0]."'>".$r_tts[0]."</option>";
	}
	echo "</select></td></tr></table>
	<table class='a1 wi'><tr><th style='text-align:left'><input type='checkbox' onclick='toggle(this,\"fopt[]\")' /></th><th>Options</th></tr><tr><td colspan=2>";
	$opts = array('structure'=>'Structure','data'=>'Data','cdb'=>'Create DB','auto'=>'Auto Increment','drop'=>'Drop Table','ifnot'=>'If not exist','procfunc'=>'Routines');
	foreach($opts as $k => $opt) {
	echo "<p><input type='checkbox' name='fopt[]' value='{$k}'".($k=='structure' ? ' onclick="opt()" checked':'')." /> ".$opt."</p>";
	}
	echo "</td></tr></table>
	<table class='a1 wi'><tr><th>File format</th></tr><tr><td>";
	$ffo = array('sql'=>'SQL','csv'=>'CSV','xls'=>'Excel','doc'=>'Word');
	foreach($ffo as $k => $ff) {
	echo "<p><input type='radio' name='ffmt[]' value='{$k}'".($k=='sql' ? ' checked':'')." /> {$ff}</p>";
	}
	echo "</td></tr></table>
	<table class='a1 wi'><tr><th>File type</th></tr><tr><td>";
	$fty = array('plain'=>'Plain','gzip'=>'GZ','zip'=>'Zip');
	foreach($fty as $k => $ft) {
	echo "<p><input type='radio' name='ftype[]' value='{$k}'".($k=='plain' ? ' checked':'')." /> {$ft}</p>";
	}
	echo "</td></tr></table>
	</td></tr><tr><td class='c1'><button type='submit' name='exp'>Export</button></td></tr></table></form></td></tr></table>";
	} else {
	redir("5/".$db,array("err"=>"No export empty DB"));
	}
break;

case "32": //export
	if(post('exp','i')) {
	check(array(1));
	$db= $sg[1];
	$tbs= array();
	$vws= array();
	if(post('tables','e')) {//push selected
		redir("31/".$db,array('err'=>"You didn't select any table"));
	} else {
		$tabs = post('tables');
		foreach($tabs as $tab) {
			$q_strc = mysql_query("SHOW TABLE STATUS FROM {$db} like '{$tab}'");
			$r_com = mysql_fetch_assoc($q_strc);
			if($r_com['Comment'] == 'VIEW') {
			array_push($vws, $r_com['Name']);
			} else {
			array_push($tbs, $r_com['Name']);
			}
		}
	}

	if(post('fopt','e')) {//check export options
		redir("31/".$db,array('err'=>"You didn't select any option"));
	} else {
		$fopt=post('fopt');
	}

	$sql="";
	$ffmt= post('ffmt');
	if(in_array('sql',$ffmt)) {//sql format
		$sql.="-- EdMyAdmin SQL Dump\n-- version ".$version."\n\n";
		if(in_array('cdb',$fopt) && in_array('structure',$fopt)) {//check option create db
			$sql .= "CREATE DATABASE ";
			if(in_array('ifnot',$fopt)) {//check option if not exist
			$sql .= "IF NOT EXISTS ";
			}
			$sql .= $db.";\nUse $db;\n\n";
		}

		foreach($tbs as $tb) {
			$q_st= mysql_query("SHOW TABLE STATUS FROM {$db} like '{$tb}'");
			$r_st= mysql_fetch_array($q_st);
			if(in_array('structure',$fopt)) {//begin structure
				if(in_array('drop',$fopt)) {//check option drop
					$sql .= "DROP TABLE IF EXISTS `$tb`;\n";
				}
				$q_ex= mysql_query("SHOW FULL FIELDS FROM ".$tb);
				$ifnot='';
				if(in_array('ifnot',$fopt)) {//check option if not exist
					$ifnot .= "IF NOT EXISTS ";
				}

				$sq="CREATE TABLE ".$ifnot."`".$tb."` (";
				while($r_ex = mysql_fetch_assoc($q_ex)) {
					$trans = array("PRI" => "PRIMARY KEY","UNI"=>"UNIQUE KEY","MUL"=>"KEY");
					$nul=($r_ex['Null']=='YES' ? "NULL" : "NOT NULL");
					$def=($r_ex['Default']!='' ? " default '".$r_ex['Default']."'" : "");
					$clls=(($r_ex['Collation']!='' && $r_ex['Collation']!='NULL') ? " COLLATE '".$r_ex['Collation']."'" : "");
					$xtr=($r_ex['Extra']!='' ? " ".$r_ex['Extra'] : "");
					$sq.="\n\t`".$r_ex['Field']."` ".$r_ex['Type']." ".$nul.$clls.
					$def.$xtr.",";
				}
				$idx1= array();$idx2= array();$idx3= array();$idx4= array();
				$q_sidx= mysql_query("SHOW KEYS FROM ".$tb);
				while($r_sidx=mysql_fetch_assoc($q_sidx)) {
				if($r_sidx['Key_name']=='PRIMARY') $idx1[]=$r_sidx['Column_name'];
				elseif($r_sidx['Index_type']=='FULLTEXT') $idx4[$r_sidx['Key_name']][]= $r_sidx['Column_name'];
				elseif($r_sidx['Non_unique']==1) $idx2[$r_sidx['Key_name']][]= $r_sidx['Column_name'];
				elseif($r_sidx['Non_unique']==0) $idx3[$r_sidx['Key_name']][]= $r_sidx['Column_name'];
				}
				$sq.= (count($idx1) > 0 ? "\n\tPRIMARY KEY(`".implode("`,`",$idx1)."`),":"");
				foreach($idx2 as $k2=>$q2) {
				if(is_array($q2)) $sq.="\n\tKEY `".$k2."` (`".implode("`,`",$q2)."`),";
				else $sq.="\n\tKEY `".$k2."` (`".$q2."`),";
				}
				foreach($idx3 as $k3=>$q3) {
				if(is_array($q3)) $sq.="\n\tUNIQUE KEY `".$k3."` (`".implode("`,`",$q3)."`),";
				else $sq.="\n\tUNIQUE KEY `".$k3."` (`".$q3."`),";
				}
				foreach($idx4 as $k4=>$q4) {
				if(is_array($q4)) $sq.="\n\tFULLTEXT INDEX `".$k4."` (`".implode("`,`",$q4)."`),";
				else $sq.="\n\tFULLTEXT INDEX `".$k4."` (`".$q4."`),";
				}
				$sql.= substr($sq,0,-1)."\n)";
				$co = ($r_st['Comment']=='' ? "":" COMMENT='".$r_st['Comment']."'");
				$auto = ((version_compare($v2,'5.0.24','>=') && in_array('auto',$fopt)) ? " AUTO_INCREMENT=".$r_st[10] : "");//check auto option
				$sql.= ($r_st['Comment']!='VIEW' ? " ENGINE=".$r_st[1]." DEFAULT CHARSET=".strtok($r_st['Collation'],'_').$co.$auto:"").";\n\n";
			}//end structure

			if(in_array('data',$fopt)) {//check option data
				$q_fil= mysql_query("SHOW FIELDS FROM ".$tb);
				$cols= mysql_num_rows($q_fil);
				$q_rx= mysql_query("SELECT * FROM ".$tb);
				if(mysql_num_rows($q_rx)) {
					if($r_st['Comment'] != 'VIEW') {
					while($r_rx=mysql_fetch_row($q_rx)) {
						$ins="INSERT INTO `".$tb."` VALUES (";
						$inn="";
						for($e=0;$e<$cols;$e++) {
							$bi = mysql_field_flags($q_rx,$e);//blob-bin
							if(stristr($bi,"blob binary") == true) {
							$inn .= (empty($r_rx[$e]) ? "''":"0x".bin2hex($r_rx[$e])).", ";
							} elseif(is_numeric($r_rx[$e])){
							$inn .= $r_rx[$e].", ";
							} else {
							$inn .= "'".$r_rx[$e]."', ";
							}
						}
						$ins.=substr($inn,0,-2);
						$sql.=$ins.");\n";
					}
					$sql.= "\n\n";
					}
				}
			}//end option data
		}

		if($vws != '') {//export views
		foreach($vws as $vw) {
			$q_rw = mysql_query("SHOW CREATE VIEW ".$vw);
			if(mysql_num_rows($q_rw)) {
			if(in_array('drop',$fopt)) {//check option drop
			$sql .= "DROP VIEW IF EXISTS `$vw`;\n";
			}
			while($r_rr = mysql_fetch_row($q_rw)) {
			$sql .= $r_rr[1].";\n\r";
			}
			$sql .= "\n\r";
			}
		}
		}

		if(in_array('procfunc',$fopt)) {//check option spp
			if(version_compare($v2,'5.0.45','>=')) {
			$sql .= "DELIMITER $$\n\r";
			//export triggers
			$q_trg=mysql_query("SELECT TRIGGER_NAME,ACTION_TIMING,EVENT_MANIPULATION,EVENT_OBJECT_TABLE,ACTION_STATEMENT FROM information_schema.triggers WHERE TRIGGER_SCHEMA='".$db."'");
			if(mysql_num_rows($q_trg)) {
			while($r_row = mysql_fetch_row($q_trg)) {
				if(in_array($r_row[3], $tbs)) {
					if(in_array('drop',$fopt)) {//check option drop
					$sql .= "DROP TRIGGER IF EXISTS `".$r_row[0]."`;\n";
					}
					$sql .= "CREATE TRIGGER `".$r_row[0]."` ".$r_row[1]." ".$r_row[2]." ON `".$r_row[3]."` FOR EACH ROW\n".$r_row[4].";\n\r";
				}
			}
			$sql .= "\n\r";
			}
			//export procedures & functions
			$q_pr = mysql_query("SELECT ROUTINE_TYPE, ROUTINE_NAME FROM information_schema.routines WHERE ROUTINE_SCHEMA='".$db."'");
			if(mysql_num_rows($q_pr)) {
			while($r_px = mysql_fetch_row($q_pr)) {
				$q_rs = mysql_query("SHOW CREATE ".$r_px[0]." ".$r_px[1]);
				if(in_array('drop',$fopt)) {//check option drop
				$sql .= "DROP ".$r_px[0]." IF EXISTS `".$r_px[1]."`;\n";
				}
				while($r_rs = mysql_fetch_row($q_rs)) {
				$sql .= $r_rs[2].";\n\r";
				}
			}
			$sql .= "\n";
			}
			$sql .= "DELIMITER ;\n\r";
			}
		}
	} elseif(in_array('csv',$ffmt)) {//csv format
		foreach($tbs as $tb) {
		$q_csv= mysql_query("SHOW FIELDS FROM ".$tb);
		while($r_csv = mysql_fetch_assoc($q_csv)) {
			$sql.='"'.$r_csv['Field'].'",';
		}
		$sql=substr($sql,0,-1)."\n";
		$q_rs=mysql_query("SELECT * FROM ".$tb);
		$r_cols=mysql_num_fields($q_rs);
		while($r_rs=mysql_fetch_row($q_rs)) {
			for($t=0;$t<$r_cols;$t++) $sql.="\"".str_replace('"','""',$r_rs[$t])."\",";
			$sql=substr($sql,0,-1)."\n";
		}
		}
	} elseif(in_array('xls',$ffmt) || in_array('doc',$ffmt)) {//xls format
		$ms = (in_array('doc',$ffmt) ? 'word': 'excel');
		$sql .= '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:'.$ms.'" 	xmlns="http://www.w3.org/TR/REC-html40"><!DOCTYPE html><html><head><meta http-equiv="Content-type" content="text/html;charset=utf-8" /></head><body>';
		foreach($tbs as $tb) {
			$sql .='<table border=1 cellpadding=0 cellspacing=0 style="border-collapse: collapse"><tr>';
			$q_xl1 = mysql_query("SHOW FIELDS FROM ".$tb);
			while($r_xl1 = mysql_fetch_row($q_xl1)) {
				$sql .= '<th>'.$r_xl1[0].'</th>';
			}
			$sql .= "</tr>";
			$q_xl2 = mysql_query("SELECT * FROM ".$tb);
			$r_nrs = mysql_num_fields($q_xl2);
			while($r_xl2 = mysql_fetch_row($q_xl2)) {
				$sql .= "<tr>";
				$z = 0;
				while($z < $r_nrs) {
				$sql .= '<td>'.$r_xl2[$z].'</td>';
				++$z;
				}
				$sql .= "</tr>";
			}
			$sql .='</table>';
		}
		$sql .='</body></html>';
	}

	if(in_array("csv", $ffmt)) {//type, ext
		$ffty = "text/csv";
		$ffext= ".csv";
	} elseif(in_array("xls", $ffmt)) {
		$ffty = "application/excel";
		$ffext= ".xls";
	} elseif(in_array("doc", $ffmt)) {
		$ffty = "application/msword";
		$ffext= ".doc";
	} elseif(in_array("sql", $ffmt)) {
		$ffty = "text/plain";
		$ffext= ".sql";
	}
	$ftype= post('ftype');
	if(in_array("gzip", $ftype)) {//pack
		$zty = "application/x-gzip";
		$zext= ".gz";
	} elseif(in_array("zip", $ftype)) {
		$zty = "application/x-zip";
		$zext= ".zip";
	}
	$fname= $db.(count($tbs) == 1 ? ".".$tbs[0] : "").$ffext;
	if(in_array("gzip", $ftype)) {//gzip
		ini_set('zlib.output_compression','Off');
		$sql = gzencode($sql, 9);
		header('Content-Encoding: gzip');
		header("Content-Length: ".strlen($sql));
	} elseif(in_array("zip", $ftype)) {//zip
		$info = array();
		$ctrl_dir = array();
		$eof = "\x50\x4b\x05\x06\x00\x00\x00\x00";
		$old_offset = 0;
		$ti = getdate();
		if($ti['year'] < 1980) {
		$ti['year'] = 1980;$ti['mon'] = 1;$ti['mday'] = 1;$ti['hours'] = 0;$ti['minutes'] = 0;$ti['seconds'] = 0;
		}
		$time = (($ti['year'] - 1980) << 25) | ($ti['mon'] << 21) | ($ti['mday'] << 16) | ($ti['hours'] << 11) | ($ti['minutes'] << 5) | ($ti['seconds'] >> 1);
		$dtime = substr("00000000" . dechex($time), -8);
		$hexdtime = '\x'.$dtime[6].$dtime[7].'\x'.$dtime[4].$dtime[5].'\x'.$dtime[2].$dtime[3].'\x'.$dtime[0].$dtime[1];
		eval('$hexdtime = "'.$hexdtime.'";');
		$fr = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00".$hexdtime;
		$unc_len = strlen($sql);
		$crc = crc32($sql);
		$zdata = gzcompress($sql);
		$zdata = substr(substr($zdata, 0, strlen($zdata) - 4), 2);
		$c_len = strlen($zdata);
		$fr .= pack('V', $crc).pack('V', $c_len).pack('V', $unc_len).pack('v', strlen($fname)).pack('v', 0).$fname.$zdata;
		$info[] = $fr;
		$cdrec = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00".$hexdtime.
		pack('V', $crc).pack('V', $c_len).pack('V', $unc_len).pack('v', strlen($fname)).
		pack('v', 0).pack('v', 0).pack('v', 0).pack('v', 0).pack('V', 32).pack('V', $old_offset);
		$old_offset += strlen($fr);
		$cdrec .= $fname;
		$ctrl_dir[] = $cdrec;
		$ctrldir = implode('', $ctrl_dir);
		$end = $ctrldir.$eof.pack('v', sizeof($ctrl_dir)).pack('v', sizeof($ctrl_dir)).pack('V', strlen($ctrldir)).pack('V', $old_offset)."\x00\x00";
		$datax = implode('', $info);
		$sql = $datax.$end;
	}
	header("Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0");
	header("Content-Type: ".(in_array("plain", $ftype) ? $ffty."; charset=utf-8" : $zty));
	header("Content-Disposition: attachment; filename=".$fname.(in_array("plain", $ftype) ? "":$zext));
	die($sql);
	}
break;

case "40": //add view
	check(array(1));
	$db= $sg[1];
	if(post('vname','!e') && post('vstat','!e')) {//add
	$vstat= post('vstat','',1);
	$stat= mysql_query($vstat);
	if(!mysql_num_rows($stat)) redir("5/".$db,array('err'=>"Wrong statement"));
	$e_v= mysql_query("CREATE VIEW `".post('vname')."` AS ".$vstat);
	if($e_v) redir("5/".$db,array('ok'=>"Successfully created"));
	else redir("5/".$db,array('err'=>"Create view failed"));
	}
	echo $head.menu($db);
	echo form("40/$db")."<table class='a1'><tr><th colspan=2>Create View</th></tr>
	<tr><td>Name</td><td><input type='text' name='vname'/></td></tr>
	<tr><td>Statement</td><td><textarea name='vstat'></textarea></td></tr>
	<tr><td class='c1' colspan=2><button type='submit'>Create</button></td></tr></table></form>";
break;

case "41": //add trigger
	check(array(1));
	$db= $sg[1];
	if(post('trgnm','!e') && post('trgdf','!e')) {//add
	$q_tgcrt= mysql_query("CREATE TRIGGER `".post('trgnm')."` ".post('trgti')." ".post('trgev')." ON `".post('trgtb')."` FOR EACH ROW ".post('trgdf','',1));
	if($q_tgcrt) redir("5/".$db,array('ok'=>"Successfully created"));
	else redir("5/".$db,array('err'=>"Create trigger failed"));
	}
	$tgtb= array();
	$q_trgt = mysql_query("SHOW TABLE STATUS FROM ".$db);
	while($r_trgt = mysql_fetch_assoc($q_trgt)) {
	if($r_trgt['Comment']!='VIEW') {
	$tgtb[] = $r_trgt['Name'];
	}
	}
	echo $head.menu($db);
	echo form("41/$db")."<table class='a1'><tr><th colspan=2>Create Trigger</th></tr>
	<tr><td>Trigger Name</td><td><input type='text' name='trgnm'/></td></tr>
	<tr><td>Table</td><td><select name='trgtb'>";
	foreach($tgtb as $tgt) echo "<option value='".$tgt."'>".$tgt."</option>";
	echo "</select></td></tr>
	<tr><td>Time</td><td><select name='trgti'><option value='BEFORE'>BEFORE</option><option value='AFTER'>AFTER</option></select></td></tr>
	<tr><td>Event</td><td><select name='trgev'><option value='INSERT'>INSERT</option><option value='UPDATE'>UPDATE</option><option value='DELETE'>DELETE</option></select></td></tr>
	<tr><td>Definition</td><td><textarea name='trgdf'></textarea></td></tr>
	<tr><td class='c1' colspan=2><button type='submit'>Create</button></td></tr></table></form>";
break;

case "42": //add routine
	check(array(1));
	$db= $sg[1];
	$sdas= array('CONTAINS SQL','NO SQL','READS SQL DATA','MODIFIES SQL DATA');
	if(post('ronme','!e') && post('rodf','!e')) {//add
		$roty= post('roty');
		$rtn= "CREATE DEFINER=`".$_SESSION['user']."`@`".$_SESSION['host']."` $roty `".post('ronme')."`";
		$rt2="(";
		$roc= count(post('ropty'));
		if($roty=='PROCEDURE') {
			$rc2=0;
			while($rc2 < $roc) {
			$rt2.=post('ropin',$rc2)." `".post('roppa',$rc2)."` ".post('ropty',$rc2).(post('ropva',$rc2)!=''?"(".post('ropva',$rc2).")":"");
			if(in_array(post('ropty',$rc2),$fieldtype['Numbers'])) {
			$rt2.=(post('rop1',$rc2)!=''?" ".post('rop1',$rc2):"");
			}
			if(in_array(post('ropty',$rc2),$fieldtype['Strings'])) {
			$rt2.=(post('rop2',$rc2)!=''?" CHARSET ".post('rop2',$rc2):"");
			}
			$rt2.=",";
			++$rc2;
			}
			$rtn.=substr($rt2,0,-1).")";
		} elseif($roty=='FUNCTION') {
			$rc3=0;
			while($rc3 < $roc) {
			$rt2.="`".post('roppa',$rc3)."` ".post('ropty',$rc3).(post('ropva',$rc3)!=''?"(".post('ropva',$rc3).")":"");
			if(in_array(post('ropty',$rc2),$fieldtype['Numbers'])) {
			$rt2.=(post('rop1',$rc2)!=''?" ".post('rop1',$rc2):"");
			}
			if(in_array(post('ropty',$rc2),$fieldtype['Strings'])) {
			$rt2.=(post('rop2',$rc2)!=''?" ".post('rop2',$rc2):"");
			}
			$rt2.=",";
			++$rc3;
			}
			$rtn.=substr($rt2,0,-1).") RETURNS ".post('rorty').(post('rorva','!e')?"(".post('rorva').")":"");
			if(in_array(post('rorty'),$fieldtype['Numbers'])) {
			$rtn.=(post('rorop1','!e')?" ".post('rorop1'):"");
			}
			if(in_array(post('rorty'),$fieldtype['Strings'])) {
			$rtn.=(post('rorop2','!e')?" CHARSET ".post('rorop2'):"");
			}
		}
		if(post('rosda')==1) $dd=$sdas[1];
		elseif(post('rosda')==2) $dd=$sdas[2];
		elseif(post('rosda')==3) $dd=$sdas[3];
		else $dd=$sdas[0];
		$rtn.= $dd.(post('rodet','i')?" DETERMINISTIC":"").(post('rosec')=='INVOKER'?" SQL SECURITY INVOKER":"").(post('rocom','!e')?" COMMENT '".post('rocom')."'":"")."\n".post('rodf','',1);

		$run_ro= mysql_query($rtn);
		if($run_ro) redir("5/$db",array('ok'=>"Created routine"));
		else redir("5/$db",array('err'=>"Can't create routine"));
	}
	
	$swcl= "<option value=''>&nbsp;</option>";
	$q_rocl= mysql_query("SHOW COLLATION");
	while($r_rocl= mysql_fetch_row($q_rocl)) {
		$swcl .= "<option value='".$r_rocl[0]."'>".$r_rocl[0]."</option>";
	}
	echo $head.menu($db);
	echo form("42/$db")."<table class='a1'><tr><th colspan=2>Create Routine</th></tr>
	<tr><td>Name</td><td><input type='text' name='ronme'/></td></tr>
	<tr><td>Type</td><td><select id='rou' name='roty'><option value='PROCEDURE'>PROCEDURE</option><option value='FUNCTION'>FUNCTION</option></select></td></tr>
	<tr><td>Parameters</td><td>
	<table>
	<tr><th class='rou1'>Direction</th><th>Name</th><th>Type</th><th>Values</th><th>Options</th><th class='bb' id='minus'>-</th></tr>
	<tr id='rr_1'><td class='rou1'>
		<select name='ropin[]'><option value='IN'>IN</option><option value='OUT'>OUT</option><option value='INOUT'>INOUT</option></select>
		</td><td><input type='text' name='roppa[]'/></td><td>
		<select class='pty1' name='ropty[]'>".fieldtype()."</select>
		</td><td><input type='text' name='ropva[]'/></td><td>
		<select class='pa1' name='rop1[]'>";
		foreach($inttype as $itk=>$itt) {
		echo "<option value='$itk'>$itt</option>";
		}
		echo "</select><select class='pa2' name='rop2[]'>".$swcl."</select>
		</td><td class='bb' id='plus'>+</td></tr></table>
	</td></tr>

	<tr class='rou2'><td>Return type</td><td><select id='pty2' name='rorty'>".fieldtype()."</select></td></tr>
	<tr class='rou2'><td>Return values</td><td><input type='text' name='rorva'/></td></tr>
	<tr class='rou2'><td>Return options</td><td><select id='px1' name='rorop1'>";
	foreach($inttype as $itk=>$itt) {
	echo "<option value='$itk'>$itt</option>";
	}
	echo "</select><select id='px2' name='rorop2'>".$swcl."</select></td></tr>

	<tr><td>Definition</td><td><textarea name='rodf'></textarea></td></tr>
	<tr><td>Deterministic</td><td><input type='checkbox' name='rodet'/></td></tr>
	<tr><td>Security type</td><td><select name='rosec'><option value='DEFINER'>DEFINER</option><option value='INVOKER'>INVOKER</option></select></td></tr>
	<tr><td>SQL data access</td><td><select name='rosda'>";
	foreach($sdas as $sdk=>$sda) echo "<option value='$sdk'>$sda</option>";
	echo "</select></td></tr><tr><td>Comment</td><td><input type='text' name='rocom'/></td></tr>
	<tr><td class='c1' colspan=2><button type='submit'>Create</button></td></tr></table></form>";
break;

case "43": //add event
	check(array(1));
	$db= $sg[1];
	if(post('evnme','!e') && post('evstat','!e')) {
		$q_evcrt = mysql_query("CREATE EVENT `".post('evnme')."` ON SCHEDULE ".(post('evpre','i')? "AT '".post('evsta')."'":"EVERY '".post('evevr1')."' ".post('evevr2')." STARTS '".post('evsta')."' ENDS '".post('evend')."'")." ON COMPLETION".(post('evpre','i')?"":" NOT")." PRESERVE ".post('evendi')." COMMENT '".post('evcom')."' DO ".post('evstat','',1));
		if($q_evcrt) redir("5/".$db,array('ok'=>"Successfully created"));
		else redir("5/".$db,array('err'=>"Create event failed"));
	}
	echo $head.menu($db);
	echo form("43/$db")."<table class='a1'><tr><th colspan=2>Create Event</th></tr>
	<tr><td>Name</td><td><input type='text' name='evnme'/></td></tr>
	<tr><td>Start</td><td><input type='text' name='evsta'/></td></tr>
	<tr id='evend'><td>End</td><td><input type='text' name='evend'/></td></tr>
	<tr><td>One time</td><td><input type='checkbox' id='one' name='evone'/></td></tr>
	<tr id='every'><td>Every</td><td class='div'><input type='text' name='evevr1' size='3'/><select name='evevr2'>";
	$evr= array('YEAR','QUARTER','MONTH','DAY','HOUR','MINUTE','WEEK','SECOND','YEAR_MONTH','DAY_HOUR','DAY_MINUTE','DAY_SECOND','HOUR_MINUTE','HOUR_SECOND','MINUTE_SECOND');
	foreach($evr as $vr) echo "<option value='$vr'>$vr</option>";
	echo "</select></td></tr>
	<tr><td>Status</td><td><select name='evendi'><option value='ENABLE'>ENABLE</option><option value='DISABLE'>DISABLE</option><option value='DISABLE ON SLAVE'>DISABLE ON SLAVE</option></select></td></tr>
	<tr><td>Comment</td><td><input type='text' name='evcom'/></td></tr>
	<tr><td>On completion preserve</td><td><input type='checkbox' name='evpre'/></td></tr>
	<tr><td>Statement</td><td><textarea name='evstat'></textarea></td></tr>
	<tr><td class='c1' colspan=2><button type='submit'>Create</button></td></tr></table></form>";
break;

case "45": //edit sp
	check(array(1,5));
	$db= $sg[1];
	$sp= $sg[2];
	$ty= $sg[3];
	if(post('sp2','!e')) {
		mysql_query("DROP {$ty} IF EXISTS ".$sp);
		mysql_query(post('sp2','',1));
		redir("5/".$db,array('ok'=>"Successfully updated"));
	} else {
		echo $head.menu($db,'','',array($ty,$sp));
		echo "<div class='box'>".form("45/$db/$sp/$ty")."<textarea name='sp2'>";
		if($ty == 'trigger' && version_compare($v2,'5.1.30','<')) {
			$q_trg=mysql_query("SELECT TRIGGER_NAME,ACTION_TIMING,EVENT_MANIPULATION,EVENT_OBJECT_TABLE,ACTION_STATEMENT from information_schema.TRIGGERS WHERE TRIGGER_SCHEMA='".$db."' AND `TRIGGER_NAME`='$sp'");
			$row = mysql_fetch_row($q_trg);
			echo "CREATE TRIGGER `".$row[0]."` ".$row[1]." ".$row[2]." ON `".$row[3]."` FOR EACH ROW ".$row[4];
		} else {
			$q_spp = mysql_query("SHOW CREATE ".$ty." {$db}.".$sp);
			$sx = mysql_fetch_row($q_spp);
			echo ($ty=='view' ? $sx[1]:($ty=='event'?$sx[3]:$sx[2]));
		}
		echo "</textarea><br/><button type='submit'>Save</button></form></div>";
	}
break;

case "49": //drop sp
	check(array(1,5));
	$q_drosp = mysql_query("DROP ".$sg[3]." ".$sg[1].".".$sg[2]);
	if($q_drosp) redir("5/".$sg[1],array('ok'=>"Successfully dropped"));
break;

case "50": //login
	if(post('lhost','!e') && post('username','!e') && post('password','i')) {
		$_SESSION['user']= post('username');
		$_SESSION['host']= post('lhost');
		$_SESSION['token']= base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5($salt.$_SERVER['HTTP_USER_AGENT']), post('lhost')."*#*".post('password'), MCRYPT_MODE_ECB, $iv));
		redir();
	}
	session_unset();
	session_destroy();
	echo $head."<div class='scroll'>".form("50")."<table class='a1'><caption>LOGIN</caption><tr><td>Host<br/><input type='text' name='lhost' value='localhost' /></td></tr><tr><td>Username<br/><input type='text' id='username' name='username' /></td></tr><tr><td>Password<br/><input type='password' name='password' /></td></tr><tr><td><button type='submit'>Login</button></table></form>";
break;

case "51": //logout
	session_unset();
	session_destroy();
	redir();
break;

case "52": //users
	check();
	echo $head."<div class='l2'><a href='{$path}'>List DBs</a></div><div class='scroll'>
	<table class='a1'><tr><th>USER</th><th>HOST</th><th><a href='{$path}53'>ADD</a></th></tr>";
	$q_usr = mysql_query("SELECT User, Host FROM mysql.user");
	while($r_usr = mysql_fetch_row($q_usr)) {
	$bg=($bg==1)?2:1;
	echo "<tr class='r c$bg'><td class='pro'>".$r_usr[0]."</td><td class='pro'>".$r_usr[1]."</td><td><a".$del." href='{$path}55/".$r_usr[0]."/".base64_encode($r_usr[1])."'>Drop</a> | <a href='{$path}54/".$r_usr[0]."/".base64_encode($r_usr[1])."'>Edit</a></td></tr>";
	}
	echo "</table>";
break;

case "53": //add user
	check();
	if(post('username','i') && post('host','!e')) {
	$user = sanitize(post('username'));
	$passwd = (post('password','e') ? "":" IDENTIFIED BY '".post('password')."'");
	$host = post('host');
	$q_exist = mysql_query("SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user='{$user}' AND host='{$host}')");
	$r_exist = mysql_fetch_row($q_exist);
	if($r_exist[0] == 1) {
		echo "Username already exist";
	} else {
		mysql_query("CREATE USER '{$user}'@'{$host}'{$passwd}");
		$alldb = post('dbs');
		$allpri = post('pri');
		$grant = (post('ogrant','!e') ? " GRANT OPTION":"");
		$with = " WITH".$grant." MAX_QUERIES_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0".
		(version_compare($v2,'5.0.0','<')?"":" MAX_USER_CONNECTIONS 0");
		if($allpri[0] == 'on') {//selected priv
			array_shift($allpri);
			$allprivs = implode(", ",$allpri);
		}
		if($alldb[0] == 'all' && $allpri[0] == 'all') {//all priv, all db
			mysql_query("GRANT USAGE ON *.* TO '{$user}'@'{$host}'".$passwd.$with);
			mysql_query("GRANT ALL PRIVILEGES ON *.* TO '{$user}'@'{$host}'");
		}
		if($alldb[0] == 'all' && $allpri[0] != 'all') {
			mysql_query("GRANT $allprivs ON *.* TO '{$user}'@'{$host}'".$passwd.$with);
		}
		if($alldb[0] == 'on') {//selected db
		array_shift($alldb);
		foreach($alldb as $adb) {
			if($allpri[0] == 'all') {//all priv
			mysql_query("GRANT USAGE ON {$adb}.* TO '{$user}'@'{$host}'".$passwd.$with);
			mysql_query("GRANT ALL PRIVILEGES ON {$adb}.* TO '{$user}'@'{$host}'");
			} else {//selected priv
			mysql_query("GRANT $allprivs ON {$adb}.* TO '{$user}'@'{$host}'".$passwd.$with);
			}
		}
		}
		mysql_query("FLUSH PRIVILEGES");
		redir("52",array('ok'=>"Added user"));
	}
	}

	echo $head."<div class='l2'><a href='{$path}'>List DBs</a></div><div class='scroll'>"
	.form("53")."<table class='a1'><tr><th colspan=2>Add User</th></tr>
	<tr><td>Host </td><td><input type='text' name='host' value='localhost' /></td></tr>
	<tr><td>Name </td><td><input type='text' name='username' /></td></tr>
	<tr><td>Password </td><td><input type='password' name='password' /></td></tr>
	<tr><td>Allow access to </td><td><input type='radio' onclick='hide(\"tdbs\")' name='dbs[]' value='all' checked /> All Databases</td></tr>
	<tr><td></td><td><input type='radio' onclick='show(\"tdbs\")' name='dbs[]' /> Selected Databases</td></tr>
	<tr><td></td><td>
	<table id='tdbs' class='c1 wi'><tr><th>Databases</th></tr><tr><td><p><input type='checkbox' onclick='selectall(\"dbs\",\"sel2\")' id='sel2' /> Select/Deselect</p><select class='he' id='dbs' name='dbs[]' multiple='multiple'>";
	$q_dbs = mysql_query("SHOW DATABASES");
	while($r_dbs= mysql_fetch_row($q_dbs)) {
	if(!in_array($r_dbs[0],$deny)) {
	echo "<option value='".$r_dbs[0]."'>".$r_dbs[0]."</option>";
	}
	}
	echo "</select></td></tr></table></td></tr>
	<tr><td>Privileges</td><td><input type='radio' onclick='hide(\"privs\")' name='pri[]' value='all' checked /> All Privileges</td></tr>
	<tr><td></td><td><input type='radio' onclick='show(\"privs\")' name='pri[]' /> Selected Privileges</td></tr>
	<tr><td></td><td>
	<table id='privs' class='c1 wi'><tr><th>Privileges</th></tr><tr><td>";
	if(version_compare($v2,'5.0.45','>=')) $prvs = array_merge($prvs,array('CREATE USER','SHOW VIEW','CREATE VIEW','CREATE ROUTINE','ALTER ROUTINE'));
	if(version_compare($v2,'5.1.6','>=')) $prvs = array_merge($prvs,array('TRIGGER','EVENT'));
	foreach($prvs as $prv) {
		echo "<p><input type='checkbox' name='pri[]' value='".$prv."' /> ".$prv."</p>";
	}
	echo "</td></tr></table></td></tr>
	<tr><td>Options</td><td><input type='checkbox' name='ogrant' value='GRANT OPTION' /> Grant Option</td></tr>
	<tr><td class='c1' colspan=2><button type='submit'>Create</button></td></tr></table></form>";
break;

case "54": //edit-update user
	check(array(6));
	if(empty($sg[2])) {
	$usr='';
	$hst= base64_decode($sg[1]);
	} else {
	$usr= $sg[1];
	$hst= base64_decode($sg[2]);
	}
	if(post()) {
		mysql_query("REVOKE ALL PRIVILEGES ON *.* FROM '$usr'@'$hst'");
		mysql_query("REVOKE GRANT OPTION ON *.* FROM '$usr'@'$hst'");
		mysql_query("DELETE FROM mysql.db WHERE `User`='$usr' AND `Host`='$hst'");
		$alldb= post('dbs');
		$allpri= post('pri');
		$grant= (post('ogrant','!e') ? " GRANT OPTION":"");
		$with= " WITH".$grant." MAX_QUERIES_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0".
		(version_compare($v2,'5.0.0','<')?"":" MAX_USER_CONNECTIONS 0");
		$passwd= (post('password','e') ? "":" IDENTIFIED BY '".post('password')."'");
		if($allpri[0] == 'on') {//selected priv
			array_shift($allpri);
			$allprivs = implode(", ",$allpri);
		}
		if($alldb[0] == 'all' && $allpri[0] == 'all') {//all priv, all db
			mysql_query("GRANT USAGE ON *.* TO '{$usr}'@'{$hst}'".$passwd.$with);
			mysql_query("GRANT ALL PRIVILEGES ON *.* TO '{$usr}'@'{$hst}'");
		}
		if($alldb[0] == 'all' && $allpri[0] != 'all') {
			mysql_query("GRANT $allprivs ON *.* TO '{$usr}'@'{$hst}'".$passwd.$with);
		}
		if($alldb[0] == 'on') {//selected db
		array_shift($alldb);
		foreach($alldb as $adb) {
			if($allpri[0] == 'all') {//all priv
			mysql_query("GRANT USAGE ON `{$adb}`.* TO '$usr'@'$hst'".$passwd.$with);
			mysql_query("GRANT ALL PRIVILEGES ON `{$adb}`.* TO '$usr'@'$hst'");
			} else {//selected priv
			mysql_query("GRANT $allprivs ON `{$adb}`.* TO '$usr'@'$hst'".$passwd.$with);
			}
		}
		}

		if(post('password','!e')) {
		mysql_query("SET PASSWORD FOR '$usr'@'$hst' = PASSWORD('".post('password')."')");
		}
		if(post('host','!e') || post('username','!e')) {
		$comma= ((post('host','e') && post('username','e'))?"":",");
		mysql_query("UPDATE mysql.user SET ".(post('host','e')?"":"host='".post('host')."'").$comma.(post('username','e')?"":"user='".post('username')."'")." WHERE host='$hst' AND user='$usr'");
		}
		mysql_query("FLUSH PRIVILEGES");
		redir("52",array("ok"=>"Changed user privileges"));
	}

	$dbarr= array();//if selected db
	$q_dbpri = mysql_query("SELECT * FROM mysql.db WHERE User='{$usr}'");
	while($r_dbpri = mysql_fetch_assoc($q_dbpri)) {
	$dbarr[]= $r_dbpri['Db'];
	}
	$showgr='';
	$q_uu = mysql_query("SHOW GRANTS FOR '{$usr}'@'{$hst}'");//general priv
	while($r_uu = mysql_fetch_row($q_uu)) {
	$showgr=$r_uu;
	}
	$grprivs= preg_replace('~GRANT\s(.*?)\sON(.*)~s','\1',end($showgr));
	$grprivs2= explode(", ",$grprivs);

	echo $head."<div class='l2'><a href='{$path}'>List DBs</a></div><div class='scroll'>
	".form("54/$usr/".base64_encode($hst))."<table class='a1'><tr><th colspan=2>Edit User</th></tr>
	<tr><td>Host </td><td><input type='text' name='host' value='{$hst}' /></td></tr>
	<tr><td>Name </td><td><input type='text' name='username' value='{$usr}' /></td></tr>
	<tr><td>Password </td><td><input type='password' name='password' /></td></tr>

	<tr><td>Allow access to </td><td><input type='radio' onclick='hide(\"tdbs\")' name='dbs[]' value='all'".(empty($dbarr)?" checked":"")." /> All Databases</td></tr>
	<tr><td></td><td><input type='radio' id='seldb' onclick='show(\"tdbs\")' name='dbs[]'".(!empty($dbarr)?" checked":"")." /> Selected Databases</td></tr>
	<tr><td></td><td>
	<table id='tdbs' class='c1 wi'><tr><th>Databases</th></tr><tr><td><p><input type='checkbox' onclick='selectall(\"dbs\",\"sel2\")' id='sel2' /> Select/Deselect</p><select class='he' id='dbs' name='dbs[]' multiple='multiple'>";
	$q_dbs = mysql_query("SHOW DATABASES");
	while($r_dbs= mysql_fetch_row($q_dbs)) {
	if(!in_array($r_dbs[0],$deny)) {
	echo "<option value='".$r_dbs[0]."'".(in_array($r_dbs[0],$dbarr)?" selected ":"").">".$r_dbs[0]."</option>";
	}
	}
	echo "</select></td></tr></table></td></tr>

	<tr><td>Privileges</td><td><input type='radio' onclick='hide(\"privs\")' name='pri[]' value='all'".($grprivs=="ALL PRIVILEGES"?" checked":"")." /> All Privileges</td></tr>
	<tr><td></td><td><input type='radio' id='selpriv' onclick='show(\"privs\")' name='pri[]'".($grprivs!="ALL PRIVILEGES"?" checked":"")." /> Selected Privileges</td></tr>
	<tr><td></td><td>
	<table id='privs' class='c1 wi'><tr><th>Privileges</th></tr><tr><td>";
	if(version_compare($v2,'5.0.45','>=')) $prvs = array_merge($prvs,array('CREATE USER','SHOW VIEW','CREATE VIEW','CREATE ROUTINE','ALTER ROUTINE'));
	if(version_compare($v2,'5.1.6','>=')) $prvs = array_merge($prvs,array('TRIGGER','EVENT'));

	foreach($prvs as $prv) {
		echo "<p><input type='checkbox' name='pri[]' value='".$prv."'".(in_array($prv,$grprivs2)? " checked":"")." /> ".$prv."</p>";
	}
	echo "</td></tr></table></td></tr>
	<tr><td>Options</td><td><input type='checkbox' name='ogrant' value='GRANT OPTION'".(strpos(end($showgr),"GRANT OPTION")? " checked":"")." /> Grant Option</td></tr>

	<tr><td class='c1' colspan=2><button type='submit'>Save</button></td></tr>
	</table></form>";
break;

case "55": //drop user
	check(array(6));
	if(empty($sg[2])) {
	$uu='';
	$hh= base64_decode($sg[1]);
	} else {
	$uu= $sg[1];
	$hh= base64_decode($sg[2]);
	}
	mysql_query("REVOKE ALL PRIVILEGES ON *.* FROM '$uu'@'$hh'");
	mysql_query("REVOKE GRANT OPTION ON *.* FROM '$uu'@'$hh'");
	$dpr = array('columns_priv','tables_priv','db','user');
	foreach($dpr as $dp) {
	mysql_query("DELETE FROM mysql.{$dp} WHERE `User`='$uu' AND `Host`='$hh'");
	}
	mysql_query("DROP USER '$uu'@'$hh'");
	mysql_query("FLUSH PRIVILEGES");
	redir("52",array('ok'=>"Successfully deleted"));
break;
}//End Switch
unset($_POST);
unset($_SESSION["ok"]);
unset($_SESSION["err"]);
$stop=microtime();
echo '</div><div class="l1" style="text-align:center"><a href="http://edmondsql.github.io">edmondsql</a></div></body></html>';
?>