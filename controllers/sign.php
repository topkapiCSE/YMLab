<?php
if(!defined("CONTROL"))die("access denied");
if(defined("SIGN")) return; define("SIGN",1);

class Sign{

    private $mUser;
    private $mSign;
    function __construct() {
        require "models/sign_model.php";
        require "models/user_model.php";
        $this->mUser = new mUser();
        $this->mSign = new mSign();
    }

    function register(){

        $data = array(
            "email" => trim(@$_POST["email"]),
            "pass1" => trim(@$_POST["pass1"]),
            "pass2" => trim(@$_POST["pass2"]),
        );

        if(strlen($data["pass1"]) < 5 ){
            Response::response(Status::BAD_REQUEST, SignMessages::shortPass);
        }

        if(strlen($data["pass1"]) !=  strlen($data["pass2"])){
            Response::response(Status::BAD_REQUEST, SignMessages::errMatchPass);
        }
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            Response::response(Status::BAD_REQUEST, SignMessages::invalidEmail);
        }

        $data["pass1"] = md5($data["pass1"]);



        if($this->mUser->isRegisteredEmail($data["email"]))
            Response::response(Status::BAD_REQUEST, SignMessages::usedEmail);

        if($this->mSign->register($data))
            Response::response(Status::OK, SignMessages::success);
        else
            Response::response(Status::SERVER_ERR, SystemMessages::unknown);


    }

    function getUser($userId){
        $user = array(
            "userId" => $userId,
            "name"   => "emre can",
            "email"  => "emrecandegis@topkapi.edu.tr"
        );
        $resp = new Response();
        Response::response(Status::OK, UserMessages::getUser,$user);
    }

}

?>