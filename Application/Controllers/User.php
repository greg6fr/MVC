<?php 

namespace Controllers;

use Library\Session;
use Models\User as ModelUser;

class User {

	public static function register(){
		$error = [];
		$message = [];

		if( count($_POST) !== 4){
			$error[] = "Le nombre d'élement du formulaire n'est pas correct";
		}

		if( !isset($_POST["firstName"])
		||  !isset($_POST["lastName"])
		||  !isset($_POST["login"])
		||  !isset($_POST["password"]) ){
			$error[] = "les champs ne sont pas valide";
		}

		if (!preg_match("/^[a-z\ \-]+$/i",$_POST["firstName"])){
			$error[] = "le champ firstName n'est pas valide";
		}

		if (!preg_match("/^[a-z\ \-]+$/i",$_POST["lastName"])){
			$error[] = "le champ lastName n'est pas valide";
		}

		if (!preg_match("/^[a-z\-\.\_0-9]+@[a-z\-\.\_0-9]+\.[a-z]+$/i",$_POST["login"])){
			$error[] = "le champ login n'est pas valide";
		}

		if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/",$_POST["password"])){
			$error[] = "le champ password n'est pas valide";
		}

		if(count($error) > 0){
			$_SESSION["errors"] = $error; 
			header("Location: /o22b/register?".http_build_query($_POST));
			exit;
		}

		$user = new ModelUser([
			"firstName" => $_POST["firstName"],
			"lastName"  => $_POST["lastName"],
			"login"     => $_POST["login"],
			"password"  => $_POST["password"],
		]);

		$u=$user->save();
		if($u) {
			$_SESSION["errors"] = [];
			$message=["success"=>"Votre compte a été bien créée. Connectez-vous !"];
		header("Location: /o22b/login?".http_build_query($message));
		exit;

		}

		
	}

	public static function auth(){

		$error = [];

		if( count($_POST) !== 2){
			$error[] = "Le nombre d'élement du formulaire n'est pas correct";
		}

		if( !isset($_POST["login"])
		||  !isset($_POST["password"]) ){
			$error[] = "les champs ne sont pas valide";
		}

		if (!preg_match("/^[a-z\-\.\_0-9]+@[a-z\-\.\_0-9]+\.[a-z]+$/i",$_POST["login"])){
			$error[] = "le champ login n'est pas valide";
		}

		if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/",$_POST["password"])){
			$error[] = "le champ password n'est pas valide";
		}

		if(count($error) > 0){
			$_SESSION["errors"] = $error; 
			header("Location: /o22b/login?".http_build_query($_POST));
			exit;
		}

		$u = ModelUser::findOne([
			"login"    => $_POST["login"],
			"password" => ModelUser::hashPlainPassword($_POST["password"],$_POST["login"]),
		]);

		if(!$u){
			$_SESSION["errors"] = ["L'utilisateur n'existe pas en base ou le mot de passe est incorrect."];
			header("Location: /o22b/login?login=".$_POST["login"]);
			exit;
		}

		$_SESSION["user"] = $u;

		$_SESSION["errors"] = [];
		header("Location: /o22b/home");
		exit;
	}

	public static function logout(){
		Session::stop();
		header("Location: /o22b/home");
		exit;
	}

}