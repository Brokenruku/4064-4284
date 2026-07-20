<?= $this->include('layout/operateur/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Configuration des organisations</h1>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Organisations existantes</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Organisation</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($organisations as $organisation): ?>
                            <tr>
                                <form action="/operateur/organisations/<?= esc($organisation['id']) ?>" method="post">
                                    <td class="align-middle"><input class="form-control form-control-sm" name="nom" value="<?= esc($organisation['nom']) ?>" required></td>
                                    <td class="align-middle text-nowrap">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button>
                                </form>
                                <form class="d-inline" action="/operateur/organisations/<?= esc($organisation['id']) ?>/supprimer" method="post" onsubmit="return confirm('Supprimer cette organisation ?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Ajouter une organisation</div>
            <div class="card-body">
                <form action="/operateur/organisations" method="post">
                    <div class="mb-3">
                        <label class="form-label">Nom de l'organisation</label>
                        <input type="text" class="form-control" name="nom" placeholder="Telma Mobile" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 card-important">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>
