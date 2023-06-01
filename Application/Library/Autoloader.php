<?php 

namespace Library;

class Autoloader {

	public static function register(){
		spl_autoload_register([__CLASS__,"load"]);
	}

	public static function load($className){
		// on récupère le namespace Library\Auroloader.php
		// on remplace les \ par des / => Library/Auroloader.php
		// On indique le chemin vers le dossier application (__DIR__ . "/../")
		$fileName = __DIR__ . "/../" . str_replace("\\", '/',$className). ".php";
		// On test si le fichier existe ou non
		if (file_exists($fileName)){
			// si il existe ou le charge
			require_once $fileName;
		}
	}

}