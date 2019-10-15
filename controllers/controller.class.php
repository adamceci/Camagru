<?php
class Controller {
	
	public static function createView($viewName){
		require_once('./views/'.$viewName.'.view.php');
	}
	public static function createModule($moduleName){
		require_once('./views/'.$moduleName.'.module.php');
	}

}

