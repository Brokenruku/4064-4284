<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\DepotModel;
use App\Models\RetraitModel;
use App\Models\TransfertModel;
use App\Models\FraisRetraitModel;
use App\Models\FraisTransfertModel;
use App\Models\NumeroTelephoneModel;

class Client extends BaseController
{
    public function index(): string
    {
        $clientModel = new ClientModel();
        $data['resume'] = $clientModel->resume(session()->get('client_id'));
        return view('client/dashboard', $data);
    }

    public function depot(): string
    {
        $clientModel = new ClientModel();
        $data['resume'] = $clientModel->resume(session()->get('client_id'));
        return view('client/depot', $data);
    }

    public function store_depot()
    {
        $numeroModel = new NumeroTelephoneModel();
        $depotModel = new DepotModel();

        $compteId = session()->get('client_id');
        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->to('/client/depot')->with('erreur', 'Montant invalide');
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

        return redirect()->to('/client')->with('message', 'Depot effectue avec succes');
    }

    public function retrait(): string
    {
        $clientModel = new ClientModel();
        $fraisModel = new FraisRetraitModel();

        $data['resume'] = $clientModel->resume(session()->get('client_id'));
        $data['tranches'] = $fraisModel->toutesTranches();

        return view('client/retrait', $data);
    }

    public function store_retrait()
    {
        $numeroModel = new NumeroTelephoneModel();
        $retraitModel = new RetraitModel();
        $fraisModel = new FraisRetraitModel();

        $compteId = session()->get('client_id');
        $montant = (float) $this->request->getPost('montant');
        $frais = $fraisModel->calculerFrais($montant);

        $compte = $numeroModel->find($compteId);
        if (! $compte || $compte['solde'] < ($montant + $frais)) {
            return redirect()->to('/client/retrait')->with('erreur', 'Solde insuffisant pour ce retrait');
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

        return redirect()->to('/client')->with('message', 'Retrait effectue, frais preleve: ' . $frais);
    }

    public function transfert(): string
    {
        $clientModel = new ClientModel();
        $fraisModel = new FraisTransfertModel();

        $data['resume'] = $clientModel->resume(session()->get('client_id'));
        $data['tranches'] = $fraisModel->toutesTranches();

        return view('client/transfert', $data);
    }

    public function store_transfert()
    {
        $numeroModel = new NumeroTelephoneModel();
        $clientModel = new ClientModel();
        $transfertModel = new TransfertModel();
        $fraisModel = new FraisTransfertModel();

        $expediteurId = session()->get('client_id');
        $telephoneDestinataire = $this->request->getPost('prefix_destinataire') . $this->request->getPost('numero_destinataire');
        $montant = (float) $this->request->getPost('montant');
        $frais = $fraisModel->calculerFrais($montant);

        $destinataire = $clientModel->trouverParTelephone($telephoneDestinataire);
        if (! $destinataire) {
            return redirect()->to('/client/transfert')->with('erreur', 'Numero destinataire non reconnu');
        }

        if ($destinataire['compte_id'] == $expediteurId) {
            return redirect()->to('/client/transfert')->with('erreur', 'Impossible de transferer vers votre propre compte');
        }

        $expediteur = $numeroModel->find($expediteurId);
        if (! $expediteur || $expediteur['solde'] < ($montant + $frais)) {
            return redirect()->to('/client/transfert')->with('erreur', 'Solde insuffisant pour ce transfert');
        }

        $db = db_connect();
        $db->transStart();

        $transfertModel->insert([
            'expediteur_id' => $expediteurId,
            'destinataire_id' => $destinataire['compte_id'],
            'montant' => $montant,
            'date_transfert' => date('Y-m-d'),
        ]);

        $numeroModel->ajusterSolde($expediteurId, -($montant + $frais));
        $numeroModel->ajusterSolde($destinataire['compte_id'], $montant);

        $db->transComplete();

        return redirect()->to('/client')->with('message', 'Transfert effectue, frais preleve: ' . $frais);
    }

    public function historique(): string
    {
        $clientModel = new ClientModel();
        $data['historique'] = $clientModel->historique(session()->get('client_id'));
        return view('client/historique', $data);
    }
}