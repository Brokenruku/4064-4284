<?= $this->include('layout/client/header') ?>

<h1 class="h3 mb-4">Historique des operations</h1>

<div class="card">
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

<?= $this->include('layout/client/footer') ?>