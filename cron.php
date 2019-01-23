<?php

// 让crontab定时执行的脚本程序     */5 * * * * /usr/bin/php /data/www/app/cron.php

//定时获取中数据,并写入静态缓存中

require_once('db.php');
require_once('file.php');

$sql = "select * from blog_article";
try {
	$connect = Db::getInstance()->connect();
} catch(Exception $e) {
	// $e->getMessage();
    //记录日志
	file_put_contents('./logs/'.date('y-m-d') . '.txt' , $e->getMessage());
	return;
}
$result = mysql_query($sql, $connect); 
$articles = array();
while($article = mysql_fetch_assoc($result)) {
    $articles[] = $article;
}
$file = new File();
if($articles) {
	$file->cacheData('index_cron_cahce', $articles);
} else {
	file_put_contents('./logs/'.date('y-m-d') . '.txt' , "没有相关数据");
}
return;