<?php
class Controller {
	public static function createView($viewName){
		require_once('./views/article.'.$viewName.'.php');
	}
	public static function createModule($moduleName){
		require_once('./views/module.'.$moduleName.'.php');
	}

}

