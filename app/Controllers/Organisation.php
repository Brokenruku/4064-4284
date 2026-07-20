<?php

namespace App\Controllers;

use App\Models\OrganisationModel;

class Organisation extends BaseController
{
    public function index(): string
    {
        $model = new OrganisationModel();
        $data['organisations'] = $model->orderBy('nom', 'ASC')->findAll();
        return view('operateur/organisations', $data);
    }

    public function store()
    {
        $model = new OrganisationModel();
        $model->insert([
            'nom' => $this->request->getPost('nom'),
        ]);
        return redirect()->to('/operateur/organisations')->with('message', 'Organisation ajoutee avec succes');
    }

    public function update($id)
    {
        $model = new OrganisationModel();
        $model->update($id, [
            'nom' => $this->request->getPost('nom'),
        ]);
        return redirect()->to('/operateur/organisations')->with('message', 'Organisation modifiee avec succes');
    }

    public function delete($id)
    {
        $model = new OrganisationModel();
        $model->delete($id);
        return redirect()->to('/operateur/organisations')->with('message', 'Organisation supprimee avec succes');
    }
}
