<?php
/**
 * 处理接口公共业务
 */
require_once('response.php');
require_once('db.php');
class Common {
	public $params;
	public $app;
	public function check() {
	    //通过表单实现提交，验证versionUpgrade表数据
		$this->params['app_id'] = $appId = isset($_POST['app_id']) ? $_POST['app_id'] : '';
		$this->params['version_id'] = $versionId = isset($_POST['version_id']) ? $_POST['version_id'] : '';
		$this->params['version_mini'] = $versionMini = isset($_POST['version_mini']) ? $_POST['version_mini'] : '';
		$this->params['did'] = $did = isset($_POST['did']) ? $_POST['did'] : '';
		$this->params['encrypt_did'] = $encryptDid = isset($_POST['encrypt_did']) ? $_POST['encrypt_did'] : '';
		$appId = (int)$appId;
		$versionId = (int)$versionId;
		if(!is_numeric($appId) || !is_numeric($versionId)) {
			return Response::show(401, '参数不合法');
		}
		// 获取数据
		$this->app = $this->getApp($appId);//对应app表中的id
		if(!$this->app) {
			return Response::show(402, 'app_id不存在');
		}

		//判断权限(通过输入的did和encrypt_did结合数据库中的key判断是否有此订单)
		if($this->app['is_encryption'] && $encryptDid != md5($did . $this->app['key'])) {
			return Response::show(403, '没有该权限');
		}
	}

	//获取app表字段信息
	public function getApp($id) {
		$sql = "select *
				from `app`
				where id = " . $id ."
				and status = 1 
				limit 1";
		$connect = Db::getInstance()->connect();
		$result = mysql_query($sql, $connect);
		return mysql_fetch_assoc($result);
	}

	//获取versionUpgrade表的字段信息
	public function getversionUpgrade($appId) {
		$sql = "select *
				from `version_upgrade`
				where app_id = " . $appId ."
				and status = 1 
				limit 1";
		$connect = Db::getInstance()->connect();
		$result = mysql_query($sql, $connect);
		return mysql_fetch_assoc($result);
	}
}