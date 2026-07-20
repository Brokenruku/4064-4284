<?php

namespace App\Controllers;

use App\Models\PrefixeModel;

class Prefixe extends BaseController
{
    public function index(): string
    {
        $model = new PrefixeModel();
        $data['prefixes'] = $model->orderBy('nom', 'ASC')->findAll();
        return view('operateur/prefixes', $data);
    }

    public function store()
    {
        $model = new PrefixeModel();
        $model->insert([
            'prefix' => $this->request->getPost('prefix'),
            'nom'    => $this->request->getPost('nom'),
        ]);
        return redirect()->to('/operateur/prefixes')->with('message', 'Prefixe ajoute avec succes');
    }

    public function update($id)
    {
        $model = new PrefixeModel();
        $model->update($id, [
            'prefix' => $this->request->getPost('prefix'),
            'nom'    => $this->request->getPost('nom'),
        ]);
        return redirect()->to('/operateur/prefixes')->with('message', 'Prefixe modifie avec succes');
    }

    public function delete($id)
    {
        $model = new PrefixeModel();
        $model->delete($id);
        return redirect()->to('/operateur/prefixes')->with('message', 'Prefixe supprime avec succes');
    }
}