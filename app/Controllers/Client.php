<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\DepotModel;
use App\Models\RetraitModel;
use App\Models\TransfertModel;
use App\Models\FraisRetraitModel;
use App\Models\FraisTransfertModel;
use App\Models\NumeroTelephoneModel;
use App\Models\PrefixeModel;

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

        $numeroModel->ajusterSolde($compteId, - ($montant + $frais));

        $db->transComplete();

        return redirect()->to('/client')->with('message', 'Retrait effectue, frais preleve: ' . $frais);
    }

    public function transfert(): string
    {
        $clientModel = new ClientModel();
        $fraisModel = new FraisTransfertModel();
        $prefixeModel = new PrefixeModel();

        $data['resume'] = $clientModel->resume(session()->get('client_id'));
        $data['compte'] = $clientModel->trouverParId(session()->get('client_id'));
        $data['tranches'] = $fraisModel->toutesTranches();
        $data['prefixes'] = $prefixeModel->listeAvecOrganisation();

        return view('client/transfert', $data);
    }

    public function store_transfert()
    {
        $numeroModel = new NumeroTelephoneModel();
        $clientModel = new ClientModel();
        $transfertModel = new TransfertModel();
        $fraisModel = new FraisTransfertModel();
        $fraisRetraitModel = new FraisRetraitModel();

        $expediteurId = session()->get('client_id');
        $telephoneDestinataire = $this->request->getPost('prefix_destinataire') . $this->request->getPost('numero_destinataire');
        $montant = (float) $this->request->getPost('montant');
        $inclureFraisRetrait = (bool) $this->request->getPost('inclure_frais_retrait');
        $frais = $fraisModel->calculerFrais($montant);

        $destinataire = $clientModel->trouverParTelephone($telephoneDestinataire);
        if (! $destinataire) {
            return redirect()->to('/client/transfert')->with('erreur', 'Numero destinataire non reconnu');
        }

        if ($destinataire['compte_id'] == $expediteurId) {
            return redirect()->to('/client/transfert')->with('erreur', 'Impossible de transferer vers votre propre compte');
        }

        $expediteur = $clientModel->trouverParId($expediteurId);

        $fraisRetrait = 0;
        if ($inclureFraisRetrait) {
            if (! $expediteur || $destinataire['operateur'] !== $expediteur['operateur']) {
                return redirect()->to('/client/transfert')->with('erreur', 'Frais de retrait indisponible pour les autres operateurs');
            }
            $fraisRetrait = $fraisRetraitModel->calculerFrais($montant);
        }

        $totalDebite = $montant + $frais + $fraisRetrait;
        $montantCredite = $montant + $fraisRetrait;

        $compteExpediteur = $numeroModel->find($expediteurId);
        if (! $compteExpediteur || $compteExpediteur['solde'] < $totalDebite) {
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

        $numeroModel->ajusterSolde($expediteurId, - $totalDebite);
        $numeroModel->ajusterSolde($destinataire['compte_id'], $montantCredite);

        $db->transComplete();

        return redirect()->to('/client')->with('message', 'Transfert effectue, frais preleve: ' . $frais);
    }

    public function transfert_multiple(): string
    {
        $clientModel = new ClientModel();
        $fraisModel = new FraisTransfertModel();
        $prefixeModel = new PrefixeModel();

        $compte = $clientModel->trouverParId(session()->get('client_id'));

        $data['resume'] = $clientModel->resume(session()->get('client_id'));
        $data['compte'] = $compte;
        $data['tranches'] = $fraisModel->toutesTranches();
        $data['prefixes'] = array_values(array_filter($prefixeModel->listeAvecOrganisation(), function ($prefixe) use ($compte) {
            return $compte && $prefixe['organisation'] === $compte['operateur'];
        }));

        return view('client/transfert_multiple', $data);
    }

    public function store_transfert_multiple()
    {
        $numeroModel = new NumeroTelephoneModel();
        $clientModel = new ClientModel();
        $transfertModel = new TransfertModel();
        $fraisModel = new FraisTransfertModel();

        $expediteurId = session()->get('client_id');
        $expediteur = $clientModel->trouverParId($expediteurId);

        $prefixesDestinataires = $this->request->getPost('prefix_destinataire');
        $numerosDestinataires = $this->request->getPost('numero_destinataire');
        $montantTotal = (float) $this->request->getPost('montant_total');

        if (! is_array($numerosDestinataires) || count($numerosDestinataires) < 1) {
            return redirect()->to('/client/transfert-multiple')->with('erreur', 'Aucun destinataire renseigne');
        }

        $nombreDestinataires = count($numerosDestinataires);
        $montantParDestinataire = round($montantTotal / $nombreDestinataires, 2);

        if ($montantParDestinataire <= 0) {
            return redirect()->to('/client/transfert-multiple')->with('erreur', 'Montant invalide');
        }

        $fraisUnitaire = $fraisModel->calculerFrais($montantParDestinataire);
        $totalFrais = $fraisUnitaire * $nombreDestinataires;
        $totalDebite = $montantTotal + $totalFrais;

        $compteExpediteur = $numeroModel->find($expediteurId);
        if (! $compteExpediteur || $compteExpediteur['solde'] < $totalDebite) {
            return redirect()->to('/client/transfert-multiple')->with('erreur', 'Solde insuffisant pour cet envoi multiple');
        }

        $destinataires = [];
        foreach ($numerosDestinataires as $index => $numero) {
            $prefix = $prefixesDestinataires[$index] ?? '';
            $telephone = $prefix . $numero;
            $destinataire = $clientModel->trouverParTelephone($telephone);

            if (! $destinataire) {
                return redirect()->to('/client/transfert-multiple')->with('erreur', 'Numero destinataire non reconnu: ' . $telephone);
            }

            if ($destinataire['compte_id'] == $expediteurId) {
                return redirect()->to('/client/transfert-multiple')->with('erreur', 'Impossible de transferer vers votre propre compte');
            }

            if (! $expediteur || $destinataire['operateur'] !== $expediteur['operateur']) {
                return redirect()->to('/client/transfert-multiple')->with('erreur', 'Envoi multiple limite au meme operateur');
            }

            $destinataires[] = $destinataire;
        }

        $db = db_connect();
        $db->transStart();

        foreach ($destinataires as $destinataire) {
            $transfertModel->insert([
                'expediteur_id' => $expediteurId,
                'destinataire_id' => $destinataire['compte_id'],
                'montant' => $montantParDestinataire,
                'date_transfert' => date('Y-m-d'),
            ]);

            $numeroModel->ajusterSolde($destinataire['compte_id'], $montantParDestinataire);
        }

        $numeroModel->ajusterSolde($expediteurId, - $totalDebite);

        $db->transComplete();

        return redirect()->to('/client')->with('message', 'Envoi multiple effectue vers ' . $nombreDestinataires . ' numeros, frais total preleve: ' . $totalFrais);
    }

    public function historique(): string
    {
        $clientModel = new ClientModel();
        $data['historique'] = $clientModel->historique(session()->get('client_id'));
        return view('client/historique', $data);
    }
}