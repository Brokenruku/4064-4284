<?= $this->include('layout/operateur/header') ?>

<h1 class="h3 mb-4">Depot</h1>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Nouveau depot</div>
            <div class="card-body">
                <form action="/operateur/depot" method="post">
                    <div class="mb-3">
                        <label class="form-label">Compte client</label>
                        <select class="form-select" name="compte_id" required>
                            <option value="">Choisir un compte</option>
                            <?php foreach ($comptes as $compte): ?>
                                <option value="<?= esc($compte['compte_id']) ?>"><?= esc($compte['telephone']) ?> - <?= esc($compte['operateur']) ?> - solde <?= number_format($compte['solde'], 0, ',', ' ') ?> Ar</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant</label>
                        <input type="number" step="0.01" min="1" class="form-control" name="montant" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer le depot</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Derniers depots</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $comptesParId = [];
                        foreach ($comptes as $compte) {
                            $comptesParId[$compte['compte_id']] = $compte;
                        }
                        ?>
                        <?php foreach ($derniers_depots as $depot): ?>
                            <tr>
                                <td><?= esc($comptesParId[$depot['numero_telephone_id']]['telephone'] ?? 'Inconnu') ?></td>
                                <td><?= number_format($depot['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= esc($depot['date_depot']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>