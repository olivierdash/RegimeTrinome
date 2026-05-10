<?php

namespace App\Controllers;

use App\Models\ActivityModel;
use App\Models\AdminModel;
use App\Models\CodeModel;
use App\Models\ObjectiveModel;
use App\Models\PurchaseModel;
use App\Models\RechargeRequestModel;
use App\Models\RegimeModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function login(): string
    {
        return view('admin/login', ['title' => 'Back-office', 'values' => []]);
    }

    public function attemptLogin()
    {
        $admin = model(AdminModel::class)->findByEmail(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('mot_de_passe');

        if (! $admin || (! password_verify($password, $admin['mot_de_passe']) && $password !== $admin['mot_de_passe'])) {
            return redirect()->back()->with('error', 'Identifiants back-office incorrects.');
        }

        session()->set([
            'admin_id' => (int) $admin['id'],
            'admin_name' => $admin['nom'],
            'is_admin' => true,
        ]);
        session()->remove(['user_id', 'user_name', 'pending_user_id']);

        return redirect()->to('/admin');
    }

    public function logout()
    {
        session()->remove(['admin_id', 'admin_name', 'is_admin']);

        return redirect()->to('/admin/login');
    }

    public function dashboard(): string
    {
        $this->requireAdmin();
        $userModel = model(UserModel::class);
        $purchaseModel = model(PurchaseModel::class);
        $rechargeModel = model(RechargeRequestModel::class);

        return view('admin/dashboard', [
            'title' => 'Tableau de bord',
            'stats' => [
                'users' => $userModel->countAllResults(),
                'gold' => $userModel->countGoldUsers(),
                'sales' => $purchaseModel->totalSales(),
                'pending' => $rechargeModel->countPending(),
            ],
            'byObjective' => $purchaseModel->salesByObjective(),
            'byRegime' => $purchaseModel->salesByRegime(),
            'byDate' => $purchaseModel->recentSalesByDate(),
            'recharges' => $rechargeModel->pendingWithDetails(),
        ]);
    }

    public function regimes(): string
    {
        $this->requireAdmin();

        return view('admin/regimes', [
            'title' => 'Regimes',
            'regimes' => model(RegimeModel::class)->allWithObjective(),
            'objectifs' => model(ObjectiveModel::class)->ordered(),
            'activities' => model(ActivityModel::class)->orderedByName(),
        ]);
    }

    public function saveRegime()
    {
        $this->requireAdmin();
        $data = [
            'id_objectif' => (int) $this->request->getPost('id_objectif'),
            'nom' => trim((string) $this->request->getPost('nom')),
            'prix_journalier' => (float) $this->request->getPost('prix_journalier'),
            'poids_par_jour' => (float) $this->request->getPost('poids_par_jour'),
            'pourcentage_viande' => (float) $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => (float) $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => (float) $this->request->getPost('pourcentage_volaille'),
        ];
        $id = (int) $this->request->getPost('id');
        $activityId = (int) $this->request->getPost('id_activite');
        $duration = (int) $this->request->getPost('duree_minutes_jour');

        if ($data['nom'] === '' || $data['prix_journalier'] <= 0 || $data['id_objectif'] <= 0) {
            return redirect()->to('/admin/regimes')->with('error', 'Nom, objectif et prix sont obligatoires.');
        }

        $totalComposition = $data['pourcentage_viande'] + $data['pourcentage_poisson'] + $data['pourcentage_volaille'];
        if (abs($totalComposition - 100) > 0.01) {
            return redirect()->to('/admin/regimes')->with('error', 'La composition viande/poisson/volaille doit totaliser 100%.');
        }

        $regimeModel = model(RegimeModel::class);
        if ($id > 0) {
            $regimeModel->update($id, $data);
            $regimeId = $id;
        } else {
            $regimeId = (int) $regimeModel->insert($data);
        }

        $regimeModel->replaceSportSuggestion($regimeId, $activityId, $duration);

        return redirect()->to('/admin/regimes')->with('success', 'Regime enregistre.');
    }

    public function deleteRegime(int $id)
    {
        $this->requireAdmin();
        try {
            model(RegimeModel::class)->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/admin/regimes')->with('error', 'Suppression impossible: ce regime est peut-etre deja achete.');
        }

        return redirect()->to('/admin/regimes')->with('success', 'Regime supprime.');
    }

    public function activities(): string
    {
        $this->requireAdmin();

        return view('admin/activities', [
            'title' => 'Activites sportives',
            'activities' => model(ActivityModel::class)->orderedByNewest(),
        ]);
    }

    public function saveActivity()
    {
        $this->requireAdmin();
        $data = [
            'designation' => trim((string) $this->request->getPost('designation')),
            'calories_moyennes_heure' => (int) $this->request->getPost('calories_moyennes_heure'),
        ];
        if ($data['designation'] === '' || $data['calories_moyennes_heure'] <= 0) {
            return redirect()->to('/admin/activites')->with('error', 'Designation et calories sont obligatoires.');
        }

        $id = (int) $this->request->getPost('id');
        model(ActivityModel::class)->saveFromForm($data, $id);

        return redirect()->to('/admin/activites')->with('success', 'Activite enregistree.');
    }

    public function deleteActivity(int $id)
    {
        $this->requireAdmin();
        try {
            model(ActivityModel::class)->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/admin/activites')->with('error', 'Suppression impossible.');
        }

        return redirect()->to('/admin/activites')->with('success', 'Activite supprimee.');
    }

    public function objectives(): string
    {
        $this->requireAdmin();

        return view('admin/objectives', [
            'title' => 'Objectifs',
            'objectifs' => model(ObjectiveModel::class)->ordered(),
        ]);
    }

    public function saveObjective()
    {
        $this->requireAdmin();
        $data = ['libelle' => trim((string) $this->request->getPost('libelle'))];
        $id = (int) $this->request->getPost('id');

        if ($data['libelle'] === '') {
            return redirect()->to('/admin/objectifs')->with('error', 'Le libelle est obligatoire.');
        }

        model(ObjectiveModel::class)->saveFromForm($data, $id);

        return redirect()->to('/admin/objectifs')->with('success', 'Objectif enregistre.');
    }

    public function deleteObjective(int $id)
    {
        $this->requireAdmin();
        try {
            model(ObjectiveModel::class)->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/admin/objectifs')->with('error', 'Suppression impossible: cet objectif est utilise par un regime.');
        }

        return redirect()->to('/admin/objectifs')->with('success', 'Objectif supprime.');
    }

    public function codes(): string
    {
        $this->requireAdmin();

        return view('admin/codes', [
            'title' => 'Codes porte-monnaie',
            'codes' => model(CodeModel::class)->orderedByNewest(),
        ]);
    }

    public function saveCode()
    {
        $this->requireAdmin();
        $data = [
            'numero_code' => strtoupper(trim((string) $this->request->getPost('numero_code'))),
            'montant' => (float) $this->request->getPost('montant'),
            'est_utilise' => (int) ($this->request->getPost('est_utilise') === '1'),
        ];
        $id = (int) $this->request->getPost('id');
        if ($data['numero_code'] === '' || $data['montant'] <= 0) {
            return redirect()->to('/admin/codes')->with('error', 'Numero et montant sont obligatoires.');
        }

        $codeModel = model(CodeModel::class);
        $existing = $codeModel->where('numero_code', $data['numero_code']);
        if ($id > 0) {
            $existing->where('id !=', $id);
        }
        if ($existing->first()) {
            return redirect()->to('/admin/codes')->with('error', 'Ce code existe deja.');
        }

        $codeModel->saveFromForm($data, $id);

        return redirect()->to('/admin/codes')->with('success', 'Code enregistre.');
    }

    public function deleteCode(int $id)
    {
        $this->requireAdmin();
        try {
            model(CodeModel::class)->delete($id);
        } catch (\Throwable $e) {
            return redirect()->to('/admin/codes')->with('error', 'Suppression impossible: ce code est lie a une recharge.');
        }

        return redirect()->to('/admin/codes')->with('success', 'Code supprime.');
    }

    public function validateRecharge(int $id)
    {
        $this->requireAdmin();
        if (! model(RechargeRequestModel::class)->validateRecharge($id)) {
            return redirect()->to('/admin')->with('error', 'Demande introuvable.');
        }

        return redirect()->to('/admin')->with('success', 'Recharge validee.');
    }

    public function rejectRecharge(int $id)
    {
        $this->requireAdmin();
        model(RechargeRequestModel::class)->rejectPending($id);

        return redirect()->to('/admin')->with('success', 'Recharge refusee.');
    }

    private function requireAdmin(): void
    {
        if (! session()->get('admin_id')) {
            header('Location: ' . site_url('admin/login'));
            exit;
        }
    }

}
