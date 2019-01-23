<?php
//将信息保存在静态缓存文件中
class File {
	private $_dir;

	public function __construct() {
		$this->_dir = dirname(__FILE__) . '/files/';
	}

	public function cacheData($key, $value = '', $cacheTime = 0) {
		$filename = $this->_dir  . $key . '.txt';//key是文本名

        //没有该文件就创建一个空文件
        if(!file_exists($filename)){
            fopen($filename, "w");//先创建一个空文本
        }

        if($value !== '') { // 将value值写入缓存
			if(is_null($value)) {
				return @unlink($filename);
			}

			$cacheTime = sprintf('%011d', $cacheTime);//保留11位
			return file_put_contents($filename,$cacheTime . json_encode($value));
		}
		//读取文本内容
        $contents = file_get_contents($filename);
        //判断文本内容是不是空的，是空的就返回内容为空，删除该文件，不读取数据
        if(empty($contents)){
            unlink($filename);//删除文件
            return FALSE;
        }
        $cacheTime = (int)substr($contents, 0 ,11);
        $value = substr($contents, 11);
        if($cacheTime !=0 && ($cacheTime + filemtime($filename) < time())) {//filemtime取得文件修改时间
            unlink($filename);//删除文件
            return FALSE;
        }
        return json_decode($value, true);
    }
}

$file = new File();

$file->cacheData('test1','test','1548127397');//将test内容存储在test1.txt文本中
//$file->cacheData('test2','test2');//value为空 则读取test1.txt中的内容
//$file->cacheData('test1');//value为空 则读取test1.txt中的内容