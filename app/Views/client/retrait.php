<?= $this->include('layout/client/header') ?>

<h1 class="h3 mb-4">Faire un retrait</h1>

<div class="card" style="max-width: 480px;">
    <div class="card-body">
        <form action="/client/retrait" method="post">
            <div class="mb-3">
                <label class="form-label">Solde disponible</label>
                <input type="text" class="form-control" value="<?= number_format($resume['solde'] ?? 0, 0, ',', ' ') ?> Ar" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" min="1" class="form-control" id="montantRetraitClient" name="montant" required>
            </div>
            <div class="alert alert-secondary" id="apercuFraisRetraitClient">
                Frais estime : 0 Ar<br>
                Total debite : 0 Ar
            </div>
            <button type="submit" class="btn btn-primary w-100">Retirer</button>
        </form>
    </div>
</div>

<script type="application/json" id="tranchesRetraitClient">
    <?= json_encode($tranches) ?>
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initApercuFrais === 'function') {
            initApercuFrais('montantRetraitClient', 'tranchesRetraitClient', 'apercuFraisRetraitClient');
        }
    });
</script>

<?= $this->include('layout/client/footer') ?>