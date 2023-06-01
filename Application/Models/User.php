<?php

namespace Models;

class User extends Generique {

	/**
	 * @schema VARCHAR(255) NOT NULL
	 */
	public $firstName;

	/**
	 * @schema VARCHAR(255) NOT NULL
	 */
	public $lastName;

	/**
	 * @schema VARCHAR(255) NOT NULL
	 */
	public $login;

	/**
	 * @schema VARCHAR(40) NOT NULL
	 */
	public $password;


	public static function hashPlainPassword($password,$email){
		return sha1($password . $email . __SALT__);
	}

	public function save(){
		if (!preg_match("/^[0-9a-f]{40}+$/i",$this->password)){
			$this->password = self::hashPlainPassword($this->password,$this->login);
		}

		return parent::save();
	}

}