<?php
namespace Library;

use PDO;
use ReflectionClass;

class Database {

	private static $dbName   = "o22b";
	private static $instance = null;

	public static function connect(){
		if (!self::$instance){
			self::$instance = new PDO("mysql:host=localhost;","root","");
			self::$instance->query("CREATE DATABASE IF NOT EXISTS `" . self::$dbName . "`;");
			self::$instance->query("use " . self::$dbName . ";");
		}

		return self::$instance;
	}

	public static function createTableIfNotExists($entityName){ // User
		$reflect    = self::getReflectByEntityName($entityName);
		$properties = [];
		$pattern    = "/@schema (.*)\n/";

		foreach($reflect->getProperties() as $meta){
			if (preg_match_all($pattern,$meta->getDocComment(), $matches)){
				$properties[] = "`$meta->name` ".$matches[1][0];
			}
		}

		self::connect()->query("CREATE TABLE IF NOT EXISTS `$entityName` (". join(",",$properties ) .")");
	}

	public static function getReflectByEntityName($entityName){
		return new ReflectionClass("Models\\".$entityName);
	}

	public static function insert($namespace){ // Models\User
		$arrayNamespace = explode("\\",$namespace);
		$entityName     = end($arrayNamespace);
		
		self::createTableIfNotExists($entityName);

		$reflect = self::getReflectByEntityName($entityName);
		foreach($reflect->getProperties() as $meta){
			$properties[] = "`".$meta->name."`";
			$values[]     = ":".$meta->name;
		}

		return "INSERT INTO `$entityName` (" . join(",",$properties) . ") VALUES (" . join(",",$values) . ");";
	}

	public static function update($namespace){ // Models\User
		$arrayNamespace = explode("\\",$namespace);
		$entityName     = end($arrayNamespace);
		
		$reflect = self::getReflectByEntityName($entityName);
		foreach($reflect->getProperties() as $meta){
			$properties[] = "`".$meta->name."` = :".$meta->name;
		}

		return "UPDATE `$entityName` SET ". join(",",$properties) ." WHERE id = :id;";
	}

	public static function delete($namespace){ // Models\User
		$arrayNamespace = explode("\\",$namespace);
		$entityName     = end($arrayNamespace);

		return "DELETE FROM `$entityName` WHERE id = :id;";
	}

	public static function read($namespace, $params = []){ // Models\User
		$arrayNamespace = explode("\\",$namespace);
		$entityName     = end($arrayNamespace);

		$sql = "SELECT * from `$entityName` ";

		if( count($params)> 0){
			$count = 0;
			foreach( $params as $key => $value){
				$sql .= $count === 0 
							? " WHERE `$key` = :$key"
							: " AND `$key` = :$key";
				$count++;
			}
		}


		return $sql;
	}

}