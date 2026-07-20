<?php
$totalRetrait = 0;
$totalTransfert = 0;
foreach ($par_operation as $ligne) {
    if ($ligne['type_operation'] === 'Retrait') {
        $totalRetrait = (float) $ligne['total_gains'];
    }
    if ($ligne['type_operation'] === 'Transfert') {
        $totalTransfert = (float) $ligne['total_gains'];
    }
}
$totalGains = $totalRetrait + $totalTransfert;
$pourcentageRetrait = $totalGains > 0 ? round(($totalRetrait / $totalGains) * 100) : 0;
$pourcentageTransfert = 100 - $pourcentageRetrait;
$rayon = 70;
$circonference = 2 * M_PI * $rayon;
$segmentRetrait = $circonference * ($pourcentageRetrait / 100);
?>
<?= $this->include('layout/operateur/header') ?>

<h1 class="h3 mb-4">Situation des gains</h1>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h6 class="card-title">Gain total</h6>
                <p class="fs-4 mb-0"><?= number_format($totalGains, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h6 class="card-title">Gain retrait</h6>
                <p class="fs-4 mb-0"><?= number_format($totalRetrait, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info">
            <div class="card-body">
                <h6 class="card-title">Gain transfert</h6>
                <p class="fs-4 mb-0"><?= number_format($totalTransfert, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6 class="card-title">Masse monetaire</h6>
                <p class="fs-4 mb-0"><?= number_format($statistiques['masse_monetaire'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Repartition des gains</div>
            <div class="card-body d-flex flex-column align-items-center">
                <svg viewBox="0 0 180 180" width="180" height="180">
                    <circle cx="90" cy="90" r="<?= $rayon ?>" fill="none" stroke="#0dcaf0" stroke-width="24"></circle>
                    <circle cx="90" cy="90" r="<?= $rayon ?>" fill="none" stroke="#ffc107" stroke-width="24" stroke-dasharray="<?= $segmentRetrait ?> <?= $circonference ?>" transform="rotate(-90 90 90)"></circle>
                </svg>
                <div class="mt-3 w-100">
                    <div class="d-flex justify-content-between">
                        <span><span class="badge bg-warning">&nbsp;</span> Retrait</span>
                        <span><?= $pourcentageRetrait ?> %</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><span class="badge bg-info">&nbsp;</span> Transfert</span>
                        <span><?= $pourcentageTransfert ?> %</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">Detail par type d'operation</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Nombre</th>
                            <th>Gain total</th>
                            <th>Gain moyen</th>
                            <th>Gain min</th>
                            <th>Gain max</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($par_operation as $ligne): ?>
                            <tr>
                                <td><?= esc($ligne['type_operation']) ?></td>
                                <td><?= esc($ligne['nombre_operations']) ?></td>
                                <td><?= number_format($ligne['total_gains'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($ligne['gain_moyen'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($ligne['gain_min'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($ligne['gain_max'] ?? 0, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Gains mensuels</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Type</th>
                            <th>Nombre</th>
                            <th>Frais percus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mensuels as $ligne): ?>
                            <tr>
                                <td><?= esc($ligne['mois']) ?></td>
                                <td><?= esc($ligne['type']) ?></td>
                                <td><?= esc($ligne['nb_operations']) ?></td>
                                <td><?= number_format($ligne['total_frais'] ?? 0, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Repartition par tranche de montant</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Tranche</th>
                            <th>Nombre</th>
                            <th>Gains</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($repartition as $ligne): ?>
                            <tr>
                                <td><?= esc($ligne['type_operation']) ?></td>
                                <td><?= esc($ligne['tranche_montant']) ?></td>
                                <td><?= esc($ligne['nombre_operations']) ?></td>
                                <td><?= number_format($ligne['gains_totaux'] ?? 0, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Gains transfert : meme operateur vs autres operateurs</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Categorie</th>
                            <th>Nombre</th>
                            <th>Gain (net commission)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($par_operation_detail as $ligne): ?>
                            <tr>
                                <td><?= esc($ligne['type_operation']) ?></td>
                                <td><?= esc($ligne['categorie']) ?></td>
                                <td><?= esc($ligne['nombre_operations']) ?></td>
                                <td><?= number_format($ligne['total_gains'] ?? 0, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Situation des montants a envoyer a chaque operateur</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Organisation</th>
                            <th>Transferts</th>
                            <th>Volume</th>
                            <th>Montant a envoyer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($situation_montants as $ligne): ?>
                            <tr>
                                <td><?= esc($ligne['organisation']) ?></td>
                                <td><?= esc($ligne['nombre_transferts']) ?></td>
                                <td><?= number_format($ligne['volume_transferts'] ?? 0, 0, ',', ' ') ?> Ar</td>
                                <td><?= number_format($ligne['montant_a_envoyer'] ?? 0, 0, ',', ' ') ?> Ar</td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($situation_montants)): ?>
                            <tr>
                                <td colspan="4" class="text-muted text-center py-3">Aucun transfert vers un autre operateur pour le moment</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Performance par operateur</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Operateur</th>
                    <th>Clients</th>
                    <th>Retraits</th>
                    <th>Volume retraits</th>
                    <th>Gains retraits</th>
                    <th>Transferts</th>
                    <th>Volume transferts</th>
                    <th>Gains transferts</th>
                    <th>Gains totaux</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($performance as $ligne): ?>
                    <tr>
                        <td><?= esc($ligne['operateur']) ?></td>
                        <td><?= esc($ligne['nb_clients']) ?></td>
                        <td><?= esc($ligne['nb_retraits']) ?></td>
                        <td><?= number_format($ligne['volume_retraits'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['gains_retraits'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= esc($ligne['nb_transferts']) ?></td>
                        <td><?= number_format($ligne['volume_transferts'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['gains_transferts'] ?? 0, 0, ',', ' ') ?> Ar</td>
                        <td><?= number_format($ligne['gains_totaux'] ?? 0, 0, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>