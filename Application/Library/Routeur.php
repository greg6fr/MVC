<?php

namespace Library;

class Routeur {

	private static function getUrl(){
		return rtrim(str_replace("/o22b/","",$_SERVER["REDIRECT_URL"]),"/");
	}

	public static function dispatch(){
		$url               = explode("/",self::getUrl());
		$defaultController = "Controllers\\Index";
		$defaultMethod     = "error";

		if (count($url) === 2){
			if (class_exists("Controllers\\".ucfirst($url[0])) 
			&& method_exists("Controllers\\".ucfirst($url[0]),$url[1])){
				$defaultController = "Controllers\\".ucfirst($url[0]);
				$defaultMethod     = $url[1];
			}
		} else {
			if ( class_exists("Controllers\\".ucfirst($url[0])) ) {
				$defaultController = "Controllers\\".ucfirst($url[0]);
				$defaultMethod     = "index";
			} else if (method_exists("Controllers\\Index",$url[0])){
				$defaultMethod = $url[0];
			}else if($url[0] === ""){ 
				$defaultMethod = "index";
			}
		}

		call_user_func([$defaultController, $defaultMethod]);
	}
}