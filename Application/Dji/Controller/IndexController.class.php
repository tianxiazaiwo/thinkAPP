<?php
/**
 * Created by PhpStorm.
 * User: gadflybsd
 * Date: 2017/2/8
 * Time: 10:10
 */

namespace Dji\Controller;
use Think\Controller;

class IndexController extends Controller{
	public function index(){
		echo '<h1>gdssdgsdg</h1>';
		echo '<hr>';
		echo U();
		echo '<hr>';
		echo U('Action/module');
	}
	
	public function test(){
		echo 'test';
	}
}