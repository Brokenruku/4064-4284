<?php

namespace App\Controllers;

use App\Models\DepotModel;
use App\Models\RetraitModel;
use App\Models\TransfertModel;
use App\Models\FraisRetraitModel;
use App\Models\FraisTransfertModel;
use App\Models\NumeroTelephoneModel;
use App\Models\GainModel;
use App\Models\CompteModel;

class Creation_operation extends BaseController
{
    public function index(): string
    {
        $gainModel = new GainModel();
        $data['statistiques'] = $gainModel->statistiquesGlobales();
        return view('operateur/creation_operation', $data);
    }

    public function depot(): string
    {
        $numeroModel = new NumeroTelephoneModel();
        $depotModel = new DepotModel();

        $data['comptes'] = $numeroModel->listeAvecPrefixe();
        $data['derniers_depots'] = $depotModel->orderBy('id', 'DESC')->findAll(10);

        return view('operateur/depot', $data);
    }

    public function store_depot()
    {
        $numeroModel = new NumeroTelephoneModel();
        $depotModel = new DepotModel();

        $compteId = $this->request->getPost('compte_id');
        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->to('/operateur/depot')->with('erreur', 'Montant invalide');
        }

        $db = db_connect();
        $db->transStart();

        $depotModel->insert([
            'numero_telephone_id' => $compteId,
            'montant' => $montant,
            'date_depot' => date('Y-m-d'),
        ]);

        $numeroModel->ajusterSolde($compteId, $montant);

        $db->transComplete();

        return redirect()->to('/operateur/depot')->with('message', 'Depot enregistre avec succes');
    }

    public function retrait(): string
    {
        $numeroModel = new NumeroTelephoneModel();
        $retraitModel = new RetraitModel();
        $fraisModel = new FraisRetraitModel();

        $data['comptes'] = $numeroModel->listeAvecPrefixe();
        $data['tranches'] = $fraisModel->toutesTranches();
        $data['derniers_retraits'] = $retraitModel->orderBy('id', 'DESC')->findAll(10);

        return view('operateur/retrait', $data);
    }

    public function store_retrait()
    {
        $numeroModel = new NumeroTelephoneModel();
        $retraitModel = new RetraitModel();
        $fraisModel = new FraisRetraitModel();

        $compteId = $this->request->getPost('compte_id');
        $montant = (float) $this->request->getPost('montant');
        $frais = $fraisModel->calculerFrais($montant);

        $compte = $numeroModel->find($compteId);
        if (! $compte || $compte['solde'] < ($montant + $frais)) {
            return redirect()->to('/operateur/retrait')->with('erreur', 'Solde insuffisant pour ce retrait');
        }

        $db = db_connect();
        $db->transStart();

        $retraitModel->insert([
            'numero_telephone_id' => $compteId,
            'montant' => $montant,
            'date_retrait' => date('Y-m-d'),
        ]);

        $numeroModel->ajusterSolde($compteId, -($montant + $frais));

        $db->transComplete();

        return redirect()->to('/operateur/retrait')->with('message', 'Retrait enregistre, frais preleve: ' . $frais);
    }

    public function transfert(): string
    {
        $numeroModel = new NumeroTelephoneModel();
        $transfertModel = new TransfertModel();
        $fraisModel = new FraisTransfertModel();

        $data['comptes'] = $numeroModel->listeAvecPrefixe();
        $data['tranches'] = $fraisModel->toutesTranches();
        $data['derniers_transferts'] = $transfertModel->orderBy('id', 'DESC')->findAll(10);

        return view('operateur/transfert', $data);
    }

    public function store_transfert()
    {
        $numeroModel = new NumeroTelephoneModel();
        $transfertModel = new TransfertModel();
        $fraisModel = new FraisTransfertModel();

        $expediteurId = $this->request->getPost('expediteur_id');
        $destinataireId = $this->request->getPost('destinataire_id');
        $montant = (float) $this->request->getPost('montant');
        $frais = $fraisModel->calculerFrais($montant);

        if ($expediteurId === $destinataireId) {
            return redirect()->to('/operateur/transfert')->with('erreur', 'Expediteur et destinataire doivent etre differents');
        }

        $expediteur = $numeroModel->find($expediteurId);
        if (! $expediteur || $expediteur['solde'] < ($montant + $frais)) {
            return redirect()->to('/operateur/transfert')->with('erreur', 'Solde insuffisant pour ce transfert');
        }

        $db = db_connect();
        $db->transStart();

        $transfertModel->insert([
            'expediteur_id' => $expediteurId,
            'destinataire_id' => $destinataireId,
            'montant' => $montant,
            'date_transfert' => date('Y-m-d'),
        ]);

        $numeroModel->ajusterSolde($expediteurId, -($montant + $frais));
        $numeroModel->ajusterSolde($destinataireId, $montant);

        $db->transComplete();

        return redirect()->to('/operateur/transfert')->with('message', 'Transfert enregistre, frais preleve: ' . $frais);
    }

    public function modifier_frais(): string
    {
        $fraisRetraitModel = new FraisRetraitModel();
        $fraisTransfertModel = new FraisTransfertModel();

        $data['tranches_retrait'] = $fraisRetraitModel->toutesTranches();
        $data['tranches_transfert'] = $fraisTransfertModel->toutesTranches();

        return view('operateur/modifier_frais', $data);
    }

    public function store_frais_retrait()
    {
        $model = new FraisRetraitModel();
        $model->insert([
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ]);
        return redirect()->to('/operateur/modifier-frais')->with('message', 'Tranche de retrait ajoutee');
    }

    public function update_frais_retrait($id)
    {
        $model = new FraisRetraitModel();
        $model->update($id, [
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ]);
        return redirect()->to('/operateur/modifier-frais')->with('message', 'Tranche de retrait modifiee');
    }

    public function delete_frais_retrait($id)
    {
        $model = new FraisRetraitModel();
        $model->delete($id);
        return redirect()->to('/operateur/modifier-frais')->with('message', 'Tranche de retrait supprimee');
    }

    public function store_frais_transfert()
    {
        $model = new FraisTransfertModel();
        $model->insert([
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ]);
        return redirect()->to('/operateur/modifier-frais')->with('message', 'Tranche de transfert ajoutee');
    }

    public function update_frais_transfert($id)
    {
        $model = new FraisTransfertModel();
        $model->update($id, [
            'montant_min' => $this->request->getPost('montant_min'),
            'montant_max' => $this->request->getPost('montant_max'),
            'frais' => $this->request->getPost('frais'),
        ]);
        return redirect()->to('/operateur/modifier-frais')->with('message', 'Tranche de transfert modifiee');
    }

    public function delete_frais_transfert($id)
    {
        $model = new FraisTransfertModel();
        $model->delete($id);
        return redirect()->to('/operateur/modifier-frais')->with('message', 'Tranche de transfert supprimee');
    }

    public function gain(): string
    {
        $gainModel = new GainModel();

        $data['par_operation'] = $gainModel->parOperation();
        $data['statistiques'] = $gainModel->statistiquesGlobales();
        $data['mensuels'] = $gainModel->mensuels();
        $data['repartition'] = $gainModel->repartition();
        $data['performance'] = $gainModel->performanceOperateur();

        return view('operateur/gain', $data);
    }

    public function comptes(): string
    {
        $compteModel = new CompteModel();
        $data['comptes'] = $compteModel->liste();
        return view('operateur/comptes', $data);
    }

    public function compte_detail($id): string
    {
        $compteModel = new CompteModel();
        $data['resume'] = $compteModel->resume($id);
        $data['historique'] = $compteModel->historique($id);
        return view('operateur/compte_detail', $data);
    }
}