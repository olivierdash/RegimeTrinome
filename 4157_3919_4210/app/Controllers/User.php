<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function login()
    {
        return view('user/login');
    }

    // ÉTAPE 1 : Validation et stockage temporaire
    public function createUser()
    {
        $model = new UserModel();
        $session = session();

        $userData = [
            'nom'          => trim($this->request->getPost('nom')),
            'email'        => trim($this->request->getPost('email')),
            'mot_de_passe' => $this->request->getPost('mot_de_passe'),
        ];

        // On utilise validate() du modèle sans insérer
        if (!$model->validate($userData)) {
            return view('user/login', [
                'errors' => $model->errors()
            ]);
        }

        // On stocke en session et on passe à la suite
        $session->set('temp_user_data', $userData);
        
        return redirect()->to('user/step2'); // Redirige vers la vue taille/poids
    }

    // ÉTAPE 2 : Fusion et insertion finale
    public function savePhysicalData()
    {
        $session = session();
        $model = new UserModel();

        $rules = [
            'taille' => 'required|is_natural_no_zero',
            'poids'  => 'required|is_natural_no_zero'
        ];

        if (!$this->validate($rules)) {
            return view('user/step2', [
                'validation' => $this->validator
            ]);
        }

        $step2Data = [
            'taille' => $this->request->getPost('taille'),
            'poids'  => $this->request->getPost('poids'),
        ];

        // Fusion des données de l'étape 1 et 2
        $tempData = $session->get('temp_user_data') ?? [];
        $finalData = array_merge($tempData, $step2Data);

        // Insertion finale en BDD
        if ($model->insert($finalData) === false) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        $session->remove('temp_user_data');
        return redirect()->to('/login')->with('success', 'Compte créé avec succès !');
    }
}