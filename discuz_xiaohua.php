<?php
/**
 * Short description.
 * 
 * @author  zzj 
 * @version 1.0
 * @package main
 */
set_time_limit(0);

/*
$username='cuddlesam';
$pass='pig20689';
$fid=43;
*/

$username='xie';
$pass="xiexie";
$fid=39;

$link=mysql_connect("127.0.0.1:3306",'root','123456');
if(!$link){
  die('Could not connect'.mysql_error());
}
mysql_select_db('test',$link) or die("Cant't use foo:".mysql_error());
mysql_set_charset("utf8",$link);


$discuz_url = 'http://bbs.zhaolicaiwang.com/';
$login_url = $discuz_url .'member.php?mod=logging&action=login';
$post_fields = array();
$post_fields['loginfield'] = 'username';
$post_fields['loginsubmit'] = 'true';
$post_fields['username'] = $username;
$post_fields['password'] = $pass;
$post_fields['questionid'] = 0;
$post_fields['answer'] = '';
$post_fields['seccodeverify'] = '';
$ch = curl_init($login_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec($ch);
curl_close($ch);
preg_match('/<input\s*type="hidden"\s*name="formhash"\s*value="(.*?)"\s*\/>/i', $contents, $matches);
if(!empty($matches)) {
    $formhash = $matches[1];
} else {
    die('Not found the forumhash.');
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


for($i=1;$i<6;$i++){
$page=$i;
//获取列表
$send_url = $discuz_url."forum.php?mod=forumdisplay&fid=$fid&page=$page"; 
$ch = curl_init($send_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$contents = curl_exec($ch);
curl_close($ch);

//获取tid
$tid=array();
preg_match_all("/<a\s*href=\"(.*)\" onclick=\"atarget\(this\)\" /", $contents, $matches);
if(!empty($matches)){
	$all=$matches[1];
	foreach ($all as $key => $value) {
		preg_match('/tid=(.*)&amp/',$value,$mtc2);
		if(!empty($mtc2[1]))
		$tid[]=$mtc2[1];
	}
}
foreach($tid as $key=>$v){
	$send_url = $discuz_url."forum.php?mod=post&action=reply&fid=$fid&tid=$v"; 
	echo $send_url;
	$ch = curl_init($send_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	$contents = curl_exec($ch);
	//var_dump($contents);
	curl_close($ch);
	preg_match('/<input\s*type="hidden"\s*name="formhash"\s*id="formhash"\s*value="(.*?)"\s*\/>/i', $contents, $matches);
	if(!empty($matches)) {
    		$formhash = $matches[1];
	} else {
   		 die('Not found the forumhash.');
	}
	$send_url = $discuz_url."forum.php?mod=post&action=reply&fid=$fid&tid=$v&extra=page%3D1&replysubmit=yes&infloat=yes&handlekey=fastpost&inajax=1";
	echo $send_url;
	$post_data = array();

/*
	$messages=array("我只是来看一下评论的，大家继续！","我只是来看一下评论的，大家继续！","LS说的有道理，我也这么觉得的","LZ说的挺对的，我支持","想想大部分投资人的刚性需求吧，不就是兜底么？平台凭什么赚钱，不就是介绍好的标的么？我们怎么知道平台的标的好，不是又绕回来了么？","投资和理财，你们分得清楚么？","需要仔细在考虑下才能回答这个问题");
	$temp_index=rand(0,6);
	$post_data['message'] = $messages[$temp_index];
*/


$result=mysql_query("select * from xiaohua order by rand() limit 1") or die("Invalid query".mysql_error);
if(mysql_num_rows($result)==0){
  echo "No rows found";
  exit;		
}
$xiaohuas=array();
$row=mysql_fetch_assoc($result);
$post_data['message']=$row['content'];

	$post_data['formhash']=$formhash; 
	$ch = curl_init($send_url);
	curl_setopt($ch, CURLOPT_REFERER, $send_url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$contents = curl_exec($ch);
	curl_close($ch);
	echo "成功";
	sleep(30);
}
}
//unlink($cookie_file);

echo "成功";
?>
