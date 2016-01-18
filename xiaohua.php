<?php


$link=mysql_connect("127.0.0.1:3306",'root','123456');
if(!$link){
  die('Could not connect'.mysql_error());
}
mysql_select_db('test',$link) or die("Cant't use foo:".mysql_error());
mysql_set_charset("utf8",$link);

for($i=60000;$i<70000;$i++){
        $url="http://xiaohua.zol.com.cn/detail1/$i.html";
        $content=file_get_contents($url);
	$temp_title="/<h1 class=\"article-title\">(.*)<\/h1>/";
        preg_match($temp_title,$content,$match);
	if($match[1]){
                $match[1]=str_replace('&nbsp;',' ',$match[1]);
		$title=getUTF($match[1]);
		$message='';
		$temp_content="/<div class=\"article-text\">([\s\S]*)<div data-id=/";
		preg_match($temp_content,$content,$matches);
		if($matches[1]){
                	$message=str_replace('</div>','',$matches[1]);
                	$message=str_replace("<br>",'\r\n',$message);
                	$message=str_replace('&nbsp;',' ',$message);
                	$message=str_replace('<p>',' ',$message);
                	$message=str_replace('</p>',' ',$message);
                	$message=trim($message);
                	$message=getUTF($message);
                	$sql="insert into xiaohua set title='$title',content='$message'";
                	mysql_query($sql);
                	echo "ok___$i\r\n";
                	usleep(100000);
		}
	}
}
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
