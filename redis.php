<?php
    require_once('db.php');

    $connect = Db::getInstance()->connect();
    $redis = new Redis();
    $redis->connect('127.0.0.1',6379);
    echo "Connection to server sucesssfully"."<br/>";

    $sql = "select * from app";
    $result = mysql_query($sql, $connect);

    while($article = mysql_fetch_assoc($result)) {
        $get_data[] = array(
            'id'=>$article['id'],
            'name'=>$article['name'],
            'is_encryption'=>$article['is_encryption'],
            'key'=>$article['key'],
            'create_time'=>$article['create_time'],
            'update_time'=>$article['update_time'],
            'status'=>$article['status'],
        );
        $data = json_encode($get_data);
        $redis->set('mykey' . $article['id'] ,$data);


        //存储并读出
        if($ret=$redis->get('mykey' . $article['id']))
        {
            $row = json_decode($ret, true);
            //var_dump($row);

            $usr_id = $row[0]['id'];
            $name = $row[0]['name'];
            $is_encryption = $row[0]['is_encryption'];
            $key = $row[0]['key'];
            $create_time = $row[0]['create_time'];
            $update_time = $row[0]['update_time'];
            $status = $row[0]['status'];

            echo "\r\nuserID:$usr_id, Name:$name, is_encryption:$is_encryption, key:$key, create_time:$create_time, status:$status\n";
        }
    }
?>