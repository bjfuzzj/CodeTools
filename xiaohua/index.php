<?php


$link=mysql_connect("127.0.0.1:3306",'root','bjfuzzj');
if(!$link){
  die('Could not connect'.mysql_error());
}
mysql_select_db('test',$link) or die("Cant't use foo:".mysql_error());
mysql_set_charset("utf8",$link);

$page=30;$num=1;
$start=($num-1)*$page;
$result=mysql_query("select * from xiaohua limit $start,$page") or die("Invalid query".mysql_error);
if(mysql_num_rows($result)==0){
  echo "No rows found";
  exit;		
}



/***** 笑话入库 *******/
for($i=11001;$i<=11830;$i++){
	$url="http://xiaohua.zol.com.cn/detail12/$i.html";
	$content=file_get_contents($url);
	$temp="/<div class=\"lastC\" style=\"word-wrap:break-word;word-break:break-all\">([\s\S]*)<div class=\"lastVote\">/";
	preg_match($temp,$content,$match);
	if($match[1]){
		$message=str_replace('</div>','',$match[1]);
		$message=str_replace("<br>",'\r\n',$message);
		$message=str_replace('&nbsp;',' ',$message);
		$message=trim($message);
		$message=getUTF($message);
		$sql="insert into xiaohua set content='$message'";
		mysql_query($sql);
		echo "ok___$i\r\n";
		usleep(100000);
	}
}

echo "完成";
function getUTF( $result ) {
    $res = '' ;
    if ( is_array( $result ) ) {
        foreach ( $result as $key => $value ) {
            if ( is_array( $result[$key] ) ) {
                $res[$key] = getUTF( $value ) ;
            }
            else {
                $res[$key] = @iconv( "GBK", "UTF-8//IGNORE", $value ) ;
            }
        }
    }
    else {
        $res = @iconv( "GBK", "UTF-8//IGNORE", $result ) ;
    }
    return $res ;
} // end func





?>

