<?php
/**
 * 针对论坛某一个版块发帖.
 * 
 * @author  zzj 
 * @version 1.0
 * @package main
 */
set_time_limit(0);
/*
$username='cuddlesam';
$pass='pig20689';
*/

$username='忠臣孝子';
$pass='zaizai1989413';

$fid=99;
$typeid=412;//理财交流
$page=30;
$num=2;

$discuz_url = 'http://bbs.wuxue.cc/';
$login_url = $discuz_url .'member.php?mod=logging&action=login';


$post_fields = array();
$post_fields['loginfield'] = 'username';
$post_fields['loginsubmit'] = 'true';
$post_fields['username'] = $username;
$post_fields['password'] = $pass;
$post_fields['questionid'] = 0;
$post_fields['referer'] = 'http://bbs.wuxue.cc/./';
$ch = curl_init($login_url);
$UserAgent = 'Mozilla/5.0 (Windows; U; Windows NT 5.2) Gecko/2008070208 Firefox/3.0.1';
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER,"http://bbs.wuxue.cc/./");
curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
$contents = curl_exec($ch);
curl_close($ch);
var_dump($contents);
//<input type="hidden" name="formhash" value="abe61a08" />
preg_match('/<input\s*type="hidden"\s*name="formhash"\s*value="(.*?)"\s*\/>/i', $contents, $matches);
if(!empty($matches)) {
    $formhash = $matches[1];
} else {
    die('Not found the forumhash when login');
}
echo $formhash;
$cookie_file = tempnam('./tmp','cookie');
$ch = curl_init($login_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_exec($ch);
curl_close($ch);






$link=mysql_connect("127.0.0.1:3306",'root','123456');
if(!$link){
  die('Could not connect'.mysql_error());
}
mysql_select_db('test',$link) or die("Cant't use foo:".mysql_error());
mysql_set_charset("utf8",$link);


$start=($num-1)*$page;
$result=mysql_query("select * from xiaohua order by id asc limit $start,$page") or die("Invalid query".mysql_error);
if(mysql_num_rows($result)==0){
  echo "No rows found";
  exit;		
}
$xiaohuas=array();
$i=0;
while($row=mysql_fetch_assoc($result)){
  $xiaohua[$i]['id']=$row['id']; 
  $xiaohua[$i]['message']=$row['content'];
  $xiaohua[$i]['subject']=$row['title'];
  $i++;
}
$num=count($xiaohua);
for ($i=0;$i<$num ;$i++ ) {
	 //取到了关键的cookie文件就可以带着cookie文件去模拟发帖,fid为论坛的栏目ID
	$send_url = $discuz_url."forum.php?mod=post&action=newthread&fid=$fid";
	$ch = curl_init($send_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	$contents = curl_exec($ch);
	curl_close($ch);
	//这里的hash码和登陆窗口的hash码的正则不太一样，这里的hidden多了一个id属性
	preg_match('/<input\s*type="hidden"\s*name="formhash"\s*id="formhash"\s*value="(.*?)"\s*\/>/i', $contents, $matches);
	if(!empty($matches)) {
		$formhash = $matches[1];
	} else {
		die('Not found the forumhash.');
	} 
	$post_data = array();
	//帖子标题
	$post_data['subject'] = $xiaohua[$i]['subject'];
	//帖子内容
	$post_data['message'] =$xiaohua[$i]['message'];
	$post_data['topicsubmit'] = "yes";
	$post_data['extra'] = '';
	$post_data['typeid']='';
	$post_data['tags'] = '';
	$post_data['formhash']=$formhash; 
	$ch = curl_init($send_url);
	curl_setopt($ch, CURLOPT_REFERER, $send_url);       //伪装REFERER
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$contents = curl_exec($ch);
	curl_close($ch);
	echo "成功___".$i;
	sleep(35);
	
}
//unlink($cookie_file);
echo "完毕";
?>
