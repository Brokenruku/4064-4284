<?= $this->include('layout/operateur/header') ?>

<h1 class="h3 mb-4">Retrait</h1>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Nouveau retrait</div>
            <div class="card-body">
                <form action="/operateur/retrait" method="post">
                    <div class="mb-3">
                        <label class="form-label">Compte client</label>
                        <select class="form-select" id="compteRetrait" name="compte_id" required>
                            <option value="">Choisir un compte</option>
                            <?php foreach ($comptes as $compte): ?>
                                <option value="<?= esc($compte['compte_id']) ?>" data-solde="<?= esc($compte['solde']) ?>"><?= esc($compte['telephone']) ?> - <?= esc($compte['operateur']) ?> - solde <?= number_format($compte['solde'], 0, ',', ' ') ?> Ar</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Montant</label>
                        <input type="number" step="0.01" min="1" class="form-control" id="montantRetrait" name="montant" required>
                    </div>
                    <div class="alert alert-secondary" id="apercuFraisRetrait">
                        Frais estime : 0 Ar<br>
                        Total debite : 0 Ar
                    </div>
                    <button type="submit" class="btn btn-primary w-100 card-important">Enregistrer le retrait</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Derniers retraits</div>
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
                        <?php foreach ($derniers_retraits as $retrait): ?>
                            <tr>
                                <td><?= esc($comptesParId[$retrait['numero_telephone_id']]['telephone'] ?? 'Inconnu') ?></td>
                                <td><?= number_format($retrait['montant'], 0, ',', ' ') ?> Ar</td>
                                <td><?= esc($retrait['date_retrait']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="tranchesRetrait">
    <?= json_encode($tranches) ?>
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initApercuFrais('montantRetrait', 'tranchesRetrait', 'apercuFraisRetrait');
    });
</script>

<?= $this->include('layout/operateur/footer') ?>