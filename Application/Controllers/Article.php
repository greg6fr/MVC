<?php 

namespace Controllers;

use Library\Session;
use Models\Article as ModelArticle;

class Article {

	public static function write(){
		$error = [];
        $message=[];
		$_SESSION["nothing"]="";

		if( count($_POST) !== 2){
			$error[] = "Le nombre d'élement du formulaire n'est pas correct";
		}

		if( !isset($_POST["title"])
		||  !isset($_POST["description"])
	 ){
			$error[] = "les champs ne sont pas valides";
		}

		if (strlen($_POST["title"])<5 || empty($_POST["title"]) || strlen($_POST["title"])>255){
			$error[] = "le champ titre n'est pas valide. Il doit contenir entre 5 et 255 caractères.";
		}

		if (strlen($_POST["description"])<10 || empty($_POST["description"]) || strlen($_POST["description"])>255){
			$error[] = "le champ description n'est pas valide. Il doit contenir entre 10 et 255 caractères.";
		}
if(count($error) > 0){
			$_SESSION["errors"] = $error; 
			header("Location: /o22b/articles?".http_build_query($_POST)."&".http_build_query($error));
			exit;
		}
       
		$article = new ModelArticle([
			"title" => $_POST["title"],
			"description"  => $_POST["description"]
		]);

		$article->save();
       

        $article = ModelArticle::find();

		if(!$article){
			$_SESSION["errors"] = ["Aucun article n'est disponible."];
			header("Location: /o22b/?".http_build_query($_SESSION["errors"]));
			exit;
		}

		$_SESSION["article"] = $article;
      
        $_SESSION["errors"] = [];
		$message=["success"=>"Votre article a été insérée avec succès..."];
		$_SESSION["message"] = $message;
		header("Location: /o22b/?".http_build_query($article));

		exit;
	}

	public static function sort(){

        $_SESSION["errors"]="";
		$_SESSION["nothing"]="";
		$_SESSION["message"]="";

		$article = ModelArticle::find();

		if(!$article){
			$_SESSION["errors"] = ["Aucun article n'est disponible."];
			$_SESSION["nothing"]=["message"=>"Aucun article n'est disponible."];
			header("Location: /o22b/");
			exit;
		}
		unset($_SESSION["nothing"]);

		$_SESSION["article"] = $article;
      
		//var_dump($article);																																							

		header("Location: /o22b/?".http_build_query($article));
		exit;
	}

    public static function update(){

        $error = [];
        $message=[];
		$_SESSION["nothing"]="";

		if( count($_POST) !== 3){
			$error[] = "Le nombre d'élement du formulaire n'est pas correct";
		}

		if( !isset($_POST["title"])
		||  !isset($_POST["id"])
        ||  !isset($_POST["description"])
	 ){
			$error[] = "les champs ne sont pas valides";
		}

		if (strlen($_POST["title"])<5 || empty($_POST["title"]) || strlen($_POST["title"])>255){
			$error[] = "le champ titre n'est pas valide. Il doit contenir entre 5 et 255 caractères.";
		}

		if (strlen($_POST["description"])<10 || empty($_POST["description"]) || strlen($_POST["description"])>255){
			$error[] = "le champ description n'est pas valide. Il doit contenir entre 10 et 255 caractères.";
		}

		if(count($error) > 0){
			$_SESSION["errors"] = $error; 
			header("Location: /o22b/updating?".http_build_query($_SESSION["errors"]));
			exit;
		}
        
		$article = new ModelArticle([
            "id" => $_POST["id"],
			"title" => $_POST["title"],
			"description"  => $_POST["description"]
		]);


		$art=$article->update($article);

     
        

        $article = ModelArticle::find();

		if(!$article){
			$_SESSION["errors"] = ["Aucun article n'est disponible."];
			header("Location: /o22b/?".http_build_query($_SESSION["errors"]));
			exit;
		}

		$_SESSION["article"] = $article;
		$message=["success"=>"Votre article a été mise à jour avec succès..."];
		$_SESSION["message"]=$message;
      
        $_SESSION["errors"] = [];
		//var_dump($_SESSION["article"]);
		header("Location: /o22b/?".http_build_query($article));

		
		//  header("Location: /o22b/?".http_build_query($message));
		exit;

    }

    public static function erase(){
        $error = [];
        $message=[];
        if( !isset($_GET["id"]) ||  !isset($_GET["title"])
        ||  !isset($_GET["description"] )
        
        ){
			$error[] = "Aucune information n'est disponible pour cette annonce";
		}
        if(count($error) > 0){
			$_SESSION["errors"] = $error; 
			header("Location: /o22b/?".http_build_query($_SESSION["errors"]));
			exit;
		}

        $article = new ModelArticle([
            "id" => $_GET["id"],
            "title" => $_GET["title"],
			"description"  => $_GET["description"]
    ]);

        $article=$article->delete((["id"=>$_GET['id']]));

        var_dump($article);
        echo $_GET["id"];
     
        $message=["success"=>"Votre article a été supprimée."];

        $article = ModelArticle::find();

		if(!$article){
			$_SESSION["errors"] = ["Aucun article n'est disponible."];
			header("Location: /o22b/?".http_build_query($_SESSION["errors"]));
			exit;
		}

		$_SESSION["article"] = $article;
      
		$message=["success"=>"L'article n° ".$_GET['id']." a été supprimée."];
		$_SESSION["message"] = $message;
		header("Location: /o22b/?".http_build_query($article));

		 $_SESSION["errors"] = [];
		
		exit;

     }
}