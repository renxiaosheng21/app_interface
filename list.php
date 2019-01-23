<?php
// http://app.com/list.php?page-=1&pagesize=12
require_once('response.php');
require_once('file.php');
require_once('db.php');


/*
 * 测试读取文件内容，并已接口的形式显示
 * $file = new File();
$data = $file->cacheData('test1');//读取内容
if($data) {
	return Response::show(200, '首页数据获取成功', $data);
}else{
	return Response::show(400, '首页数据获取失败', $data);
}*/


//分页读取数据库内容，并已json形式显示
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] : 6;
if(!is_numeric($page) || !is_numeric($pageSize)) {
	return Response::show(401, '数据不合法');
}

$offset = ($page - 1) * $pageSize;

$sql = "select * from blog_article limit ". $offset ." , ".$pageSize;
$cache = new File();
$articles = array();
if(!$articles = $cache->cacheData('index_mk_cache' . $page .'-' . $pageSize)) {
	try {
		$connect = Db::getInstance()->connect();
	} catch(Exception $e) {
		// $e->getMessage();
		return Response::show(403, '数据库链接失败');
	}
	$result = mysql_query($sql, $connect); 
	
	while($article = mysql_fetch_assoc($result)) {
        $articles[] = $article;
	}

	if($articles) {
		$cache->cacheData('index_mk_cache' . $page .'-' . $pageSize, $articles, 1200);
	}
}

if($articles) {
	return Response::show(200, '首页数据获取成功', $articles);
} else {
	return Response::show(400, '首页数据获取失败', $articles);
}
