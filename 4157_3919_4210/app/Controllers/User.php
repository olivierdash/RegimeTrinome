<?php
namespace App\Controllers;

class User extends BaseController{
    public function login(){
        return view('user/login');
    }

    public function createUser(){
        $req = $this->request;

        $nom = trim($req->getPost('nom'));
        $mail = trim($req->getPost('email'));
        $password = trim($req->getPost('mot_de_passe'));

        $data['errors'] = [];

        if( $nom === '' ){
            $data['errors']['nom'] = "Veuillez donner une valeur non vide au nom";
        }

        if( $mail === '' ){
            $data['errors']['email'] = "Veuillez donner une valeur valide au format d'un email";
        }

        if( $password === '' ){
            $data['errors']['password'] = "Veuillez entrer un mot de passe automatique";
        }

        $hashed_password = hash($password, PASSWORD_DEFAULT);

        /*
        Un code appelant le modele pour inserer les donnees d'utilisateur
        */

    }
}