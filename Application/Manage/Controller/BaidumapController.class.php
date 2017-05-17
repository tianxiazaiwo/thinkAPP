<?php
/**
 * Created by PhpStorm.
 * User: gadflybsd
 * Date: 2017/3/6
 * Time: 09:49
 */

namespace App\Controller;
use Think\Controller\RestController;

class BaidumapController extends RestController{
	private $_ak = 'tQspgSUq8eGCWRRQYiN9T0invSadgyhf';
	private $_sk = '3c1Th1fYmMBXuigMS8430GaDN1kYyTAv';

	/**
	 * 普通IP定位API
	 */
	public function getIocation(){
		$url = 'http://api.map.baidu.com/location/ip';
		$query_arrays = array('ak' => $this->_ak, 'coor' => 'bd09ll', 'ip' => get_client_ip());
		$data = array_merge($query_arrays, $this->_caculateAKSN($url, $query_arrays));
		$json = $this->_curl($url, $data, 'GET', false);
		$this->response($json);
	}
	
	/**
	 * 通过CURL或者封装过的Snoopy方式像微信服务器发送指令,GET或者POST方法提交数据返回结果
	 * @param        $url       提交数据的接收地址,如果是GET方法,该地址不包含?后的数据
	 * @param        $data      提交的数据GET方式为?后的部分,POST为一个表单的JSON数据
	 * @param string $method    数据提交的方法,GET(默认)或者POST
	 * @param bool   $ssl       是否SSL加密,默认为True
	 *
	 * @return string   返回服务器返回的结果
	 */
	private function _curl($url, $data, $method='GET', $ssl=true){
		switch($method){
			case 'GET':
				if(is_array($data)){
					$pieces = array();
					foreach($data AS $key => $val){
						$pieces[] = $key.'='.$val;
					}
					$param = implode('&', $pieces);
				}else{
					$param = $data;
				}
				$getUrl = $url.'?'.$param;
				$ch = curl_init($getUrl);
				curl_setopt($ch, CURLOPT_URL,$getUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				break;
			case 'POST':
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 500);
		if($ssl){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		$results= curl_exec($ch);
		curl_close($ch);
		return $results;
	}

	private function _caculateAKSN($url, $query_arrays, $method = 'GET'){
		if ($method === 'POST'){
			ksort($query_arrays);
		}
		$querystring = http_build_query($query_arrays);
		return array('sn' => md5(urlencode($url.'?'.$querystring.$this->_sk)));
	}
}