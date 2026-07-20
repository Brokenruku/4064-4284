<?= $this->include('layout/operateur/header') ?>

<br>
<br>
<br>
<br>
<br>
<br>

<div class="row g-3 mb-4">
    <div class="card card-important">
        <div class="card-body">
            <h6 class="card-title">Clients</h6>
            <p class="fs-4 mb-0"><?= esc($statistiques['total_clients'] ?? 0) ?></p>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="card card-important">
            <div class="card-body">
                <h6 class="card-title">Masse monetaire</h6>
                <p class="fs-4 mb-0"><?= number_format($statistiques['masse_monetaire'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="card card-important">
            <div class="card-body">
                <h6 class="card-title">Gains retrait</h6>
                <p class="fs-4 mb-0"><?= number_format($statistiques['total_frais_retraits'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="card card-important">
            <div class="card-body">
                <h6 class="card-title">Gains transfert</h6>
                <p class="fs-4 mb-0"><?= number_format($statistiques['total_frais_transferts'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Gains par operateur</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Operateur</th>
                    <th>Clients</th>
                    <th>Masse monetaire</th>
                    <th>Gains retrait</th>
                    <th>Gains transfert</th>
                    <th>Gains totaux</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($performance as $ligne): ?>
                    <tr>
                        <td><?= esc($ligne['operateur']) ?></td>
                        <td><?= esc($ligne['nb_clients']) ?></td>
                        <td><?= number_format($ligne['volume_retraits'] + $ligne['volume_transferts'], 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['gains_retraits'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['gains_transferts'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['gains_totaux'] ?? 0, 0, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <a href="/operateur/depot" class="text-decoration-none">
            <div class="card h-100 card-crimson">
                <div class="card-body">
                    <h5 class="card-title">Depot</h5>
                    <p class="card-text">Enregistrer un depot pour un client</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/operateur/retrait" class="text-decoration-none">
            <div class="card h-100 card-crimson">
                <div class="card-body">
                    <h5 class="card-title">Retrait</h5>
                    <p class="card-text">Enregistrer un retrait avec frais applique</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/operateur/transfert" class="text-decoration-none">
            <div class="card h-100 card-crimson">
                <div class="card-body">
                    <h5 class="card-title">Transfert</h5>
                    <p class="card-text">Transferer entre deux comptes clients</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/operateur/prefixes" class="text-decoration-none">
            <div class="card h-100 card-crimson">
                <div class="card-body">
                    <h5 class="card-title">Prefixes</h5>
                    <p class="card-text">Configurer les prefixes valables de l'operateur</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/operateur/modifier-frais" class="text-decoration-none">
            <div class="card h-100 card-crimson">
                <div class="card-body">
                    <h5 class="card-title">Baremes de frais</h5>
                    <p class="card-text">Modifier les tranches de frais retrait et transfert</p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="/operateur/comptes" class="text-decoration-none">
            <div class="card h-100 card-crimson">
                <div class="card-body">
                    <h5 class="card-title">Comptes clients</h5>
                    <p class="card-text">Voir le solde et l'historique de chaque client</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>