<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\ClientModel;

class Login extends BaseController
{
    public function index(): string
    {
        $prefixeModel = new PrefixeModel();
        $data['prefixes'] = $prefixeModel->orderBy('prefix', 'ASC')->findAll();
        return view('login', $data);
    }

    public function authentifier()
    {
        $prefixId = $this->request->getPost('prefix_id');
        $numero = $this->request->getPost('numero');

        $clientModel = new ClientModel();
        $client = $clientModel->trouverParPrefixEtNumero($prefixId, $numero);

        if (! $client) {
            return redirect()->to('/login')->with('erreur', 'Numero non reconnu');
        }

        session()->set([
            'client_id' => $client['id'],
            'telephone' => $client['telephone_complet'],
            'operateur' => $client['operateur'],
        ]);

        return redirect()->to('/client');
    }

    public function deconnexion()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}