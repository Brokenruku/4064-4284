<?= $this->include('layout/operateur/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Commission vers les autres operateurs</h1>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Pourcentages configures</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Organisation</th>
                            <th>Pourcentage</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commissions as $commission): ?>
                            <tr>
                                <form action="/operateur/commission-supp/<?= esc($commission['id']) ?>" method="post" class="d-contents">
                                    <td class="align-middle">
                                        <select class="form-select form-select-sm" name="organisation_id" required>
                                            <?php foreach ($organisations as $organisation): ?>
                                                <option value="<?= esc($organisation['id']) ?>" <?= (int) $organisation['id'] === (int) $commission['organisation_id'] ? 'selected' : '' ?>><?= esc($organisation['nom']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="align-middle"><input type="number" step="0.01" class="form-control form-control-sm" name="pourcentage" value="<?= esc($commission['pourcentage']) ?>" required></td>
                                    <td class="align-middle text-nowrap">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button>
                                </form>
                                <form class="d-inline" action="/operateur/commission-supp/<?= esc($commission['id']) ?>/supprimer" method="post" onsubmit="return confirm('Supprimer cette commission ?')">
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
            <div class="card-header">Ajouter une commission</div>
            <div class="card-body">
                <form action="/operateur/commission-supp" method="post">
                    <div class="mb-3">
                        <label class="form-label">Organisation destinataire</label>
                        <select class="form-select" name="organisation_id" required>
                            <?php foreach ($organisations as $organisation): ?>
                                <option value="<?= esc($organisation['id']) ?>"><?= esc($organisation['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pourcentage sur le frais</label>
                        <input type="number" step="0.01" class="form-control" name="pourcentage" placeholder="10" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 card-important">Enregistrer</button>
                </form>
                <p class="text-muted small mt-3 mb-0">Ce pourcentage s'applique sur le frais du transfert quand le destinataire appartient a cette organisation, et non a la notre.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>
