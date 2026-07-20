<?= $this->include('layout/operateur/header') ?>

<h1 class="h3 mb-4">Baremes de frais</h1>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Frais de retrait</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Frais</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tranches_retrait as $tranche): ?>
                            <tr>
                                <form action="/operateur/modifier-frais/retrait/<?= esc($tranche['id']) ?>" method="post" class="d-contents">
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="montant_min" value="<?= esc($tranche['montant_min']) ?>" required></td>
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="montant_max" value="<?= esc($tranche['montant_max']) ?>" required></td>
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="frais" value="<?= esc($tranche['frais']) ?>" required></td>
                                    <td class="text-nowrap">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button>
                                </form>
                                <form class="d-inline" action="/operateur/modifier-frais/retrait/<?= esc($tranche['id']) ?>/supprimer" method="post" onsubmit="return confirm('Supprimer cette tranche ?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Ajouter une tranche de retrait</div>
            <div class="card-body">
                <form action="/operateur/modifier-frais/retrait" method="post" class="row g-2">
                    <div class="col-4">
                        <input type="number" step="0.01" class="form-control" name="montant_min" placeholder="Min" required>
                    </div>
                    <div class="col-4">
                        <input type="number" step="0.01" class="form-control" name="montant_max" placeholder="Max" required>
                    </div>
                    <div class="col-4">
                        <input type="number" step="0.01" class="form-control" name="frais" placeholder="Frais" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Frais de transfert</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Frais</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tranches_transfert as $tranche): ?>
                            <tr>
                                <form action="/operateur/modifier-frais/transfert/<?= esc($tranche['id']) ?>" method="post" class="d-contents">
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="montant_min" value="<?= esc($tranche['montant_min']) ?>" required></td>
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="montant_max" value="<?= esc($tranche['montant_max']) ?>" required></td>
                                    <td><input type="number" step="0.01" class="form-control form-control-sm" name="frais" value="<?= esc($tranche['frais']) ?>" required></td>
                                    <td class="text-nowrap">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button>
                                </form>
                                <form class="d-inline" action="/operateur/modifier-frais/transfert/<?= esc($tranche['id']) ?>/supprimer" method="post" onsubmit="return confirm('Supprimer cette tranche ?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Ajouter une tranche de transfert</div>
            <div class="card-body">
                <form action="/operateur/modifier-frais/transfert" method="post" class="row g-2">
                    <div class="col-4">
                        <input type="number" step="0.01" class="form-control" name="montant_min" placeholder="Min" required>
                    </div>
                    <div class="col-4">
                        <input type="number" step="0.01" class="form-control" name="montant_max" placeholder="Max" required>
                    </div>
                    <div class="col-4">
                        <input type="number" step="0.01" class="form-control" name="frais" placeholder="Frais" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>