<?php
/**
 * Created by PhpStorm.
 * User: gadflybsd
 * Date: 2017/3/29
 * Time: 22:06
 */

namespace Manage\Model;


class AppHeaderModel extends CommonModel{
	public function getLists(){
		return $this->curd(array(
			'type'  => 'select',
			'where' => 'status=1',
			'order' => 'sort, id'
		));
	}
	
	public function getInfo($param){
		return $this->curd(array(
			'type'  => 'find',
			'where' => 'id='.$param['pk']
		));
	}
	
	public function del($param){
		$where = is_array($param['data']['ids'])?' IN ('.implode(', ', $param['data']['ids']).')':' = '.$param['data']['ids'];
		$del = $this->curd(array(
			'type'  => 'delete',
			'where' => $where,
			'order' => 'sort, id'
		));
		if($del['type'] == 'Success')
			return $this->getList();
		else
			return $del;
	}
	
	public function action($param){
		$validate = array(
			array('label', 'require', '头部样式名称必须填写！'),
			array('ckey', 'require', '头部样式唯一英文标识必须填写！'),
		);
		$param['data']['params'] = json_encode($param['data']['params']);
		switch ($param['op']){
			case 'Insert':
				$return = $this->curd(array(
					'validate'	=> array_merge($validate, array('ckey', '', '路由标识已经存在！', 0, 'unique', 1)),
					'type'		=> 'add',
					'data'		=> $param['data'],
					'msg'		=> '头部样式数据添加成功！'
				));
				$return['type'] = 'Success';
				break;
			case 'Update':
				$where = array('id' => $param['data']['id']);
				$return = $this->curd(array(
					'validate'	=> $validate,
					'type'		=> 'save',
					'where'     => $where,
					'data'		=> $param['data'],
					'msg'		=> '头部样式数据编辑成功！'
				));
				break;
		}
		return $return;
	}
}