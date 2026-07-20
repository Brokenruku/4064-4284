<?= $this->include('layout/operateur/header') ?>

<h1 class="h3 mb-4">Situation des comptes clients</h1>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Telephone</th>
                    <th>Operateur</th>
                    <th>Solde</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comptes as $compte): ?>
                    <tr>
                        <td><?= esc($compte['telephone']) ?></td>
                        <td><?= esc($compte['operateur']) ?></td>
                        <td><?= number_format($compte['solde'], 0, ',', ' ') ?> Ar</td>
                        <td><a href="/operateur/comptes/<?= esc($compte['compte_id']) ?>" class="btn btn-sm btn-outline-primary">Voir historique</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('layout/operateur/footer') ?>