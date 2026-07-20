<?= $this->include('layout/operateur/header') ?>

<h1 class="h3 mb-4">Historique du compte <?= esc($resume['telephone'] ?? '') ?></h1>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6 class="card-title">Solde actuel</h6>
                <p class="fs-4 mb-0"><?= number_format($resume['solde'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Depots</h6>
                <p class="fs-4 mb-0"><?= esc($resume['nb_depots'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Retraits</h6>
                <p class="fs-4 mb-0"><?= esc($resume['nb_retraits'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Frais payes</h6>
                <p class="fs-4 mb-0"><?= number_format($resume['total_frais_payes'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Historique des operations</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Operation</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Variation</th>
                    <th>Solde apres</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historique as $ligne): ?>
                    <tr>
                        <td><?= esc($ligne['operation']) ?></td>
                        <td><?= number_format($ligne['montant'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['frais_appliques'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= esc($ligne['variation']) ?></td>
                        <td><?= number_format($ligne['solde_apres'], 0, ',', ' ') ?> Ar</td>
                        <td><?= esc($ligne['date_operation']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="/operateur/comptes" class="btn btn-secondary mt-3">Retour a la liste</a>

<?= $this->include('layout/operateur/footer') ?>