<?= $this->include('layout/operateur/header') ?>

<h1 class="h3 mb-4">Transfert</h1>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Nouveau transfert</div>
            <div class="card-body">
                <form action="/operateur/transfert" method="post">
                    <div class="mb-3">
                        <label class="form-label">Compte expediteur</label>
                        <select class="form-select" name="expediteur_id" required>
                            <option value="">Choisir un compte</option>
                            <?php foreach ($comptes as $compte): ?>
                                <option value="<?= esc($compte['compte_id']) ?>"><?= esc($compte['telephone']) ?> - <?= esc($compte['operateur']) ?> - solde <?= number_format($compte['solde'], 0, ',', ' ') ?> Ar</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Compte destinataire</label>
                        <select class="form-select" name="destinataire_id" required>
                            <option value="">Choisir un compte</option>
                            <?php foreach ($comptes as $compte): ?>
                                <option value="<?= esc($compte['compte_id']) ?>"><?= esc($compte['telephone']) ?> - <?= esc($compte['operateur']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant</label>
                        <input type="number" step="0.01" min="1" class="form-control" id="montantTransfert" name="montant" required>
                    </div>
                    <div class="alert alert-secondary" id="apercuFraisTransfert">
                        Frais estime : 0 Ar<br>
                        Total debite : 0 Ar
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer le transfert</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Derniers transferts</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Expediteur</th>
                            <th>Destinataire</th>
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
                        <?php foreach ($derniers_transferts as $transfert): ?>
                            <tr>
                                <td><?= esc($comptesParId[$transfert['expediteur_id']]['telephone'] ?? 'Inconnu') ?></td>
                                <td><?= esc($comptesParId[$transfert['destinataire_id']]['telephone'] ?? 'Inconnu') ?></td>
                                <td><?= number_format($transfert['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= esc($transfert['date_transfert']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="tranchesTransfert">
    <?= json_encode($tranches) ?>
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initApercuFrais('montantTransfert', 'tranchesTransfert', 'apercuFraisTransfert');
    });
</script>

<?= $this->include('layout/operateur/footer') ?>