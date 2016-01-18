<?php


$link=mysql_connect("127.0.0.1:3306",'root','123456');
if(!$link){
  die('Could not connect'.mysql_error());
}
mysql_select_db('test',$link) or die("Cant't use foo:".mysql_error());
mysql_set_charset("utf8",$link);

for($i=1;$i<=835;$i++){
        $url="http://bbs.wdzj.com/forum-42-$i.html";
        $content=file_get_contents($url);
	$temp_title="/class=\"s xst\">(.*)<\/a>/";
        preg_match_all($temp_title,$content,$match);
	var_dump($match);
	if($match[1]){
		foreach($match[1] as $key=>$v){
		$title=$v;
                $sql="insert into thread set title='$title',type=1";
                mysql_query($sql);
                echo "ok___$title\r\n";
		}
	}
	echo "page-->",$i,"搞定\r\n";
       usleep(100000);
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
