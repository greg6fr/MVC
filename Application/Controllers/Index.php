<?php

namespace Controllers;

use Library\Template;

class Index {
	
	// url => /
	public static function index(){
		Template::render("index",[
			"userName" => "Florent",
			"User" => [
				"firstName" => "Florent"
			]
		]);
	}

	public static function error(){
		Template::render("error",[
			"code" => 404,
			"errorMessage" => "Page not found"
		]);
	}

	// url => /register
	public static function register(){
		Template::render("register",[
			"get" => $_GET,
		]);
	}

	// url => /login
	public static function login(){
		Template::render("login",[
			"get" => $_GET,
		]);
	}

	// url => /articles/article
	public static function articles(){
		Template::render("article",[
			"get" => $_GET,
		]);
	}

	
	
	public static function home(){
		Template::render("loading",[
			"get" => $_GET,
		]);
	}

	public static function updating(){
		Template::render("update",[
			"get" => $_GET,
		]);
	}


}