<?php

class Response {
	const JSON = "json";
	/**
	* 按综合方式输出通信数据
	*/
	public static function show($code, $message = '', $data = array(), $type = self::JSON) {
		if(!is_numeric($code)) {
			return '';
		}

		//默认json格式
		$type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
		);

		if($type == 'json') {
			self::json($code, $message, $data);//调用json解析方法
			exit;
		} elseif($type == 'array') {//研发自测
			var_dump($result);
		} elseif($type == 'xml') {//调用xml解析数据
			self::xmlEncode($code, $message, $data);
			exit;
		} 
	}
	/**
	* 按json方式输出通信数据  比较简单
	*/
	public static function json($code, $message = '', $data = array()) {
		
		if(!is_numeric($code)) {
			return '';
		}

		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data
		);

		echo json_encode($result);
		exit;
	}

	/**
	* 按xml方式输出通信数据
	*/
	public static function xmlEncode($code, $message, $data = array()) {
		if(!is_numeric($code)) {
			return '';
		}

		$result = array(
			'code' => $code,
			'message' => $message,
			'data' => $data,
		);

		header("Content-Type:text/xml");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";

		$xml .= self::xmlToEncode($result);

		$xml .= "</root>";
		echo $xml;
	}

	public static function xmlToEncode($data) {

		$xml = $attr = "";
		foreach($data as $key => $value) {
			if(is_numeric($key)) {
				$attr = " id='{$key}'";
				$key = "item";
			}
			$xml .= "<{$key}{$attr}>";
			$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
			$xml .= "</{$key}>\n";
		}
		return $xml;
	}

}


//demo测试
/*
$data = array(
    'name' => 'ryh',
    'age' => '24',
);

$test = new response();
$test::show('100','hehe',$data);
*/