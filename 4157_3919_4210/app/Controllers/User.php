<?php

namespace App\Controllers;

use App\Libraries\SimplePdf;
use App\Models\CodeModel;
use App\Models\ObjectiveModel;
use App\Models\PurchaseModel;
use App\Models\RechargeRequestModel;
use App\Models\RegimeModel;
use App\Models\UserModel;

class User extends BaseController
{
    private const GOLD_PRICE = 30000.0;

    public function dashboard()
    {
        $user = $this->currentUser();

        return view('user/dashboard', [
            'title' => 'Dashboard',
            'user' => $user,
            'imc' => $this->bmi($user),
            'imcStatus' => $this->bmiStatus($this->bmi($user)),
            'idealWeight' => $this->idealWeight($user),
            'achats' => model(PurchaseModel::class)->listForUser((int) $user['id']),
            'recharges' => model(RechargeRequestModel::class)->listForUser((int) $user['id']),
            'goldPrice' => self::GOLD_PRICE,
        ]);
    }

    public function profile()
    {
        $user = $this->currentUser();

        return view('user/profile', [
            'title' => 'Profil',
            'user' => $user,
            'errors' => [],
            'values' => $user,
            'imc' => $this->bmi($user),
            'imcStatus' => $this->bmiStatus($this->bmi($user)),
            'idealWeight' => $this->idealWeight($user),
        ]);
    }

    public function updateProfile()
    {
        $user = $this->currentUser();
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'email' => trim((string) $this->request->getPost('email')),
            'genre' => (string) $this->request->getPost('genre'),
            'taille' => (float) $this->request->getPost('taille'),
            'poids' => (float) $this->request->getPost('poids'),
        ];
        $password = (string) $this->request->getPost('mot_de_passe');
        $errors = [];

        if ($data['nom'] === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        }

        if (! filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalide.';
        } elseif (model(UserModel::class)->emailExists($data['email'], (int) $user['id'])) {
            $errors['email'] = 'Cet email est deja utilise.';
        }

        if (! in_array($data['genre'], ['Homme', 'Femme'], true)) {
            $errors['genre'] = 'Choisis un genre.';
        }

        if ($data['taille'] <= 0) {
            $errors['taille'] = 'La taille doit etre positive.';
        }

        if ($data['poids'] <= 0) {
            $errors['poids'] = 'Le poids doit etre positif.';
        }

        if ($password !== '' && strlen($password) < 8) {
            $errors['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caracteres.';
        }

        if (! empty($errors)) {
            return view('user/profile', [
                'title' => 'Profil',
                'user' => $user,
                'errors' => $errors,
                'values' => $data,
                'imc' => $this->bmi($user),
                'imcStatus' => $this->bmiStatus($this->bmi($user)),
                'idealWeight' => $this->idealWeight($user),
            ]);
        }

        if ($password !== '') {
            $data['mot_de_passe'] = password_hash($password, PASSWORD_DEFAULT);
        }

        model(UserModel::class)->updateProfile((int) $user['id'], $data);
        session()->set('user_name', $data['nom']);

        return redirect()->to('/dashboard/profil')->with('success', 'Profil mis a jour.');
    }

    public function regimes()
    {
        $user = $this->currentUser();
        $objectiveId = (int) ($this->request->getGet('objectif') ?? 0);

        return view('user/regimes', [
            'title' => 'Regimes suggeres',
            'user' => $user,
            'objectifs' => model(ObjectiveModel::class)->ordered(),
            'selectedObjective' => $objectiveId,
            'regimes' => model(RegimeModel::class)->suggestionsForUser($objectiveId, $user, (bool) $user['est_gold']),
        ]);
    }

    public function buyRegime()
    {
        $user = $this->currentUser();
        $regimeId = (int) $this->request->getPost('id_regime');
        $duration = (int) $this->request->getPost('duree_jours');
        $regime = model(RegimeModel::class)->findWithObjective($regimeId);

        if (! $regime || $duration <= 0) {
            return redirect()->to('/dashboard/regimes')->with('error', 'Regime ou duree invalide.');
        }

        $total = $this->purchaseTotal($regime, $duration, (bool) $user['est_gold']);
        if ((float) $user['porte_monnaie'] < $total) {
            return redirect()->to('/dashboard/wallet')->with('error', 'Solde insuffisant pour cet achat.');
        }

        if (! model(PurchaseModel::class)->createWithWalletDebit($user, $regime, $duration, $total)) {
            return redirect()->to('/dashboard/regimes')->with('error', 'Achat impossible pour le moment.');
        }

        return redirect()->to('/dashboard/suivi')->with('success', 'Regime achete avec succes.');
    }

    public function wallet()
    {
        $user = $this->currentUser();

        return view('user/wallet', [
            'title' => 'Porte-monnaie',
            'user' => $user,
            'recharges' => model(RechargeRequestModel::class)->listForUser((int) $user['id']),
            'goldPrice' => self::GOLD_PRICE,
        ]);
    }

    public function requestRecharge()
    {
        $user = $this->currentUser();
        $number = strtoupper(trim((string) $this->request->getPost('numero_code')));
        if ($number === '') {
            return redirect()->to('/dashboard/wallet')->with('error', 'Entre un code de recharge.');
        }

        $code = model(CodeModel::class)->findAvailableByNumber($number);
        if (! $code) {
            return redirect()->to('/dashboard/wallet')->with('error', 'Code introuvable ou deja utilise.');
        }

        $rechargeModel = model(RechargeRequestModel::class);
        if ($rechargeModel->codeAlreadyRequested((int) $code['id'])) {
            return redirect()->to('/dashboard/wallet')->with('error', 'Ce code a deja une demande en cours ou validee.');
        }

        $rechargeModel->createPending((int) $user['id'], (int) $code['id']);

        return redirect()->to('/dashboard/wallet')->with('success', 'Demande de recharge envoyee.');
    }

    public function activateGold()
    {
        $user = $this->currentUser();
        if (! model(UserModel::class)->activateGold($user, self::GOLD_PRICE)) {
            return redirect()->to('/dashboard/wallet')->with('error', 'Activation Gold impossible. Verifie ton solde.');
        }

        return redirect()->to('/dashboard')->with('success', 'Option Gold activee.');
    }

    public function history()
    {
        $user = $this->currentUser();

        return view('user/history', [
            'title' => 'Suivi',
            'user' => $user,
            'achats' => model(PurchaseModel::class)->listForUser((int) $user['id']),
            'recharges' => model(RechargeRequestModel::class)->listForUser((int) $user['id']),
        ]);
    }

    public function exportPurchase(int $id)
    {
        $user = $this->currentUser();
        $purchase = model(PurchaseModel::class)->findForExport($id, (int) $user['id']);
        if (! $purchase) {
            return redirect()->to('/dashboard/suivi')->with('error', 'Achat introuvable.');
        }

        $sports = model(RegimeModel::class)->sportSuggestions((int) $purchase['id_regime']);
        $pdf = new SimplePdf();
        $bytes = $pdf->purchaseSummary($user, $purchase, $sports);

        return $this->response
            ->setHeader('Content-Disposition', 'attachment; filename="regime-' . $id . '.pdf"')
            ->setContentType('application/pdf')
            ->setBody($bytes);
    }

    private function currentUser(): array
    {
        $id = (int) session()->get('user_id');
        if (! $id) {
            header('Location: ' . site_url('login'));
            exit;
        }

        $user = model(UserModel::class)->find($id);
        if (! $user) {
            session()->destroy();
            header('Location: ' . site_url('login'));
            exit;
        }

        return $user;
    }

    private function purchaseTotal(array $regime, int $duration, bool $isGold): float
    {
        $total = (float) $regime['prix_journalier'] * $duration;

        return $isGold ? $total * 0.85 : $total;
    }

    private function bmi(array $user): float
    {
        if (empty($user['taille']) || empty($user['poids'])) {
            return 0.0;
        }

        $height = (float) $user['taille'] / 100;
        if ($height <= 0) {
            return 0.0;
        }

        return (float) $user['poids'] / ($height * $height);
    }

    private function idealWeight(array $user): float
    {
        if (empty($user['taille'])) {
            return 0.0;
        }

        $height = (float) $user['taille'] / 100;

        return 22 * $height * $height;
    }

    private function bmiStatus(float $bmi): string
    {
        if ($bmi <= 0) {
            return 'Profil incomplet';
        }

        if ($bmi < 18.5) {
            return 'Insuffisance ponderale';
        }

        if ($bmi < 25) {
            return 'Corpulence normale';
        }

        if ($bmi < 30) {
            return 'Surpoids';
        }

        return 'Obesite';
    }
}
