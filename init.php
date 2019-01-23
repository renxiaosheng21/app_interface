<?php
//判断当前版本是否需要升级
require_once('common.php');
class Init extends Common {
	public function index() {
		$this->check();
		// 获取版本升级信息
		$versionUpgrade = $this->getversionUpgrade($this->app['id']);
		if($versionUpgrade) {
			if($versionUpgrade['update_type'] && $this->params['version_id'] < $versionUpgrade['version_id']) {
				$versionUpgrade['is_upload'] = $versionUpgrade['update_type'];
			}else {
				$versionUpgrade['is_upload'] = 0;
			}
			return Response::show(200, '版本升级信息获取成功', $versionUpgrade);
		} else {
			return Response::show(400, '版本升级信息获取失败');
		}
	}
}

$init = new Init();
$init->index();