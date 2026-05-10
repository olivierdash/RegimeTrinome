<?php
namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController {

    public function login() {
        return view('user/login');
    }

    public function createUser() {
        $model = new UserModel();

        $passwordRaw = $this->request->getPost('mot_de_passe');
        
        $userData = [
            'nom'          => trim($this->request->getPost('nom')),
            'email'        => trim($this->request->getPost('email')),
            'mot_de_passe' => $passwordRaw, // On valide le clair avant de hasher
        ];

        if ($model->insert($userData) === false) {
            return view('user/login', [
                'errors' => $model->errors()
            ]);
        }

        // Succès
        return redirect()->to('/login')->with('success', 'Utilisateur créé avec succès');
    }

    public function createHealth(){
        
    }
}