<?php

namespace Models;

use Library\Database;
use PDO;

class Generique {

	/**
	 * @schema INT PRIMARY KEY NOT NULL AUTO_INCREMENT
	 */
	public $id = 0;

	/**
	 * @schema DATE
	 */
	public $createdAt = "9999-12-31";

	/**
	 * @schema DATE
	 */
	public $updatedAt = "9999-12-31";	
	
	/**
	 * @schema DATE
	 */
	public $deletedAt = "9999-12-31";

	function __construct($data = []){
		if (!$this->id) {
			$date = date("Y-m-d");
			$this->createdAt = $date;
			$this->updatedAt = $date;
		}

		foreach($this as $property => $value){
			if(isset($data[$property])){
				$this->$property = $data[$property];
			}
		}
	}

	public function save(){
		if ($this->id) {
			return self::update($this);
		} else {
			$this->id = self::insert($this);
			return !!$this->id;
		}
	}

	public static function insert($object){
		$db   = Database::connect();
		$stmt = $db->prepare(Database::insert(get_called_class()));
		foreach($object as $property => $value){
			$stmt->bindValue(":$property", $value);
		}

		return $stmt->execute() ? $db->lastInsertId() : var_dump($db->errorInfo());
	}

	public static function update($object){
		$db   = Database::connect();
		$stmt = $db->prepare(Database::update(get_called_class()));

		foreach($object as $property => $value){
			$stmt->bindValue(":$property", $value);
		}

		return $stmt->execute();
	}

	public static function delete($object){
		$db   = Database::connect();
		$stmt = $db->prepare(Database::delete(get_called_class()));

		foreach($object as $property => $value){
			$stmt->bindValue(":$property", $value);
		}
var_dump($stmt);
		return $stmt->execute();
	}

	public static function find($params = []){
		$db   = Database::connect();	
		$stmt = $db->prepare(Database::read(get_called_class(),$params));

		foreach($params as $property => $value){
			$stmt->bindValue(":$property", $value);
		}

		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_CLASS, get_called_class());

	}

	public static function findOne($params = []){
		$result = self::find($params);
		return count($result) === 1 ? $result[0] : null;
	}

}