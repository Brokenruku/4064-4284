<?= $this->include('layout/operateur/header') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Configuration des prefixes</h1>
</div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Prefixes existants</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Prefixe</th>
                            <th>Organisation</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prefixes as $prefixe): ?>
                            <tr>
                                <form action="/operateur/prefixes/<?= esc($prefixe['id']) ?>" method="post" class="d-contents">
                                    <td class="align-middle"><input class="form-control form-control-sm" name="prefix" value="<?= esc($prefixe['prefix']) ?>" required></td>
                                    <td class="align-middle">
                                        <select class="form-select form-select-sm" name="organisation_id" required>
                                            <?php foreach ($organisations as $organisation): ?>
                                                <option value="<?= esc($organisation['id']) ?>" <?= (int) $organisation['id'] === (int) $prefixe['organisation_id'] ? 'selected' : '' ?>><?= esc($organisation['nom']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="align-middle text-nowrap">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Modifier</button>
                                </form>
                                <form class="d-inline" action="/operateur/prefixes/<?= esc($prefixe['id']) ?>/supprimer" method="post" onsubmit="return confirm('Supprimer ce prefixe ?')">
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
            <div class="card-header">Ajouter un prefixe</div>
            <div class="card-body">
                <form action="/operateur/prefixes" method="post">
                    <div class="mb-3">
                        <label class="form-label">Prefixe</label>
                        <input type="text" class="form-control" name="prefix" placeholder="034" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Organisation</label>
                        <select class="form-select" name="organisation_id" required>
                            <?php foreach ($organisations as $organisation): ?>
                                <option value="<?= esc($organisation['id']) ?>"><?= esc($organisation['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Pas d'organisation dans la liste ? Ajoutez-la d'abord dans <a href="/operateur/organisations">Organisations</a>.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 card-important">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>
