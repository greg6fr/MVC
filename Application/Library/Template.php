<?php

namespace Library;

class Template {

	public static $extension = ".html";
	public static $viewPath  = __DIR__ . "/../Views/";

	public static function render($templateFileName, $data = []){
		
		if (file_exists(self::$viewPath . $templateFileName . self::$extension)){
			$template = self::load(self::$viewPath . $templateFileName . self::$extension);
			$template = self::injectData($template, $data);
			echo $template;
		} else if (file_exists(self::$viewPath . "error" . self::$extension)){

		} else {
			throw new \Error(`Le fichier $templateFileName et/ou error n'existe pas`);
		}

	}

	private static function injectData($template, $data){
		preg_match_all("/{{([^}}]*)}}/",$template, $matches);
		foreach($matches[1] as $matche){
			$keys      = explode(".", $matche);
			$dataCopie = $data;
			foreach ($keys as $key){
				$key = trim($key);
				if(isset($dataCopie[$key])){
					$dataCopie = $dataCopie[$key];
				} else {
					$dataCopie = '';
					break;
				}
			}

			$template = str_replace("{{".$matche."}}",$dataCopie,$template);
		}

		return $template;
	}

	private static function load($pathToLoad){
		ob_start();
		require_once $pathToLoad;
		$template = ob_get_contents();
		ob_end_clean();

		return $template;
	}

}