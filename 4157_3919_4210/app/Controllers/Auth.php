<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login(): string
    {
        return view('auth/login', ['title' => 'Connexion']);
    }

    public function attemptLogin()
    {
        $email = trim((string) $this->request->getPost('email'));
        $password = (string) $this->request->getPost('mot_de_passe');
        $user = model(UserModel::class)->findByEmail($email);
        $data = [
            'title' => 'Connexion',
            'errors' => [],
            'emailValue' => $email,
        ];

        if (! $user ) {
            $data['errors']['email'] = 'Email incorrect';
        }

        if ($user && ! password_verify($password, $user['mot_de_passe'])) {
            $data['errors']['mot_de_passe'] = 'Mot de passe incorrect';
        }

        if (! empty($data['errors'])) {
            return view('auth/login', $data);
        }

        if ($user['taille'] === null || $user['poids'] === null) {
            session()->set('pending_user_id', (int) $user['id']);

            return redirect()->to('/register/sante');
        }

        session()->set([
            'user_id' => (int) $user['id'],
            'user_name' => $user['nom'],
            'is_admin' => false,
        ]);

        return redirect()->to('/dashboard');
    }

    public function registerIdentity(): string
    {
        return view('auth/register_identity', [
            'title' => 'Inscription',
            'errors' => [],
            'values' => [],
        ]);
    }

    public function saveIdentity()
    {
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'email' => trim((string) $this->request->getPost('email')),
            'genre' => (string) $this->request->getPost('genre'),
            'mot_de_passe' => (string) $this->request->getPost('mot_de_passe'),
        ];

        $errors = [];

        if ($data['nom'] === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        }

        if ($data['email'] === '') {
            $errors['email'] = 'L email est obligatoire.';
        } elseif (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Format email invalide.';
        }

        if ($data['mot_de_passe'] === '') {
            $errors['mot_de_passe'] = 'Le mot de passe est obligatoire.';
        } 

        $userModel = model(UserModel::class);
        if ($data['email'] !== '' && $userModel->emailExists($data['email'])) {
            $errors['email'] = 'Cet email existe deja.';
        }

        if (! empty($errors)) {
            return view('auth/register_identity', [
                'title' => 'Inscription',
                'errors' => $errors,
                'values' => [
                    'nom' => $data['nom'],
                    'email' => $data['email'],
                    'genre' => $data['genre'],
                ],
            ]);
        }

        $userId = $userModel->createPendingRegistration([
            'nom' => $data['nom'],
            'email' => $data['email'],
            'genre' => $data['genre'],
            'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
        ]);

        session()->set('pending_user_id', $userId);

        return redirect()->to('/register/sante');
    }

    public function registerHealth()
    {
        if (! session()->get('pending_user_id')) {
            return redirect()->to('/register');
        }

        return view('auth/register_health', [
            'title' => 'Informations de sante',
            'errors' => [],
            'values' => [],
        ]);
    }

    public function saveHealth()
    {
        $userId = (int) session()->get('pending_user_id');
        if (! $userId) {
            return redirect()->to('/register');
        }

        $taille = (float) $this->request->getPost('taille');
        $poids = (float) $this->request->getPost('poids');
        $errors = [];

        if ($taille <= 0) {
            $errors['taille'] = 'La taille doit etre positive.';
        }

        if ($poids <= 0) {
            $errors['poids'] = 'Le poids doit etre positif.';
        }

        if (! empty($errors)) {
            return view('auth/register_health', [
                'title' => 'Informations de sante',
                'errors' => $errors,
                'values' => [
                    'taille' => $this->request->getPost('taille'),
                    'poids' => $this->request->getPost('poids'),
                ],
            ]);
        }

        $userModel = model(UserModel::class);
        $userModel->completeHealthProfile($userId, $taille, $poids);
        session()->remove('pending_user_id');

        return redirect()->to('/login')->with('success', 'Compte cree. Tu peux te connecter.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
