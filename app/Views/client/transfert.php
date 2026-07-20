<?= $this->include('layout/client/header') ?>

<h1 class="h3 mb-4">Faire un transfert</h1>

<div class="card" style="max-width: 480px;">
    <div class="card-body">
        <form action="/client/transfert" method="post">
            <div class="mb-3">
                <label class="form-label">Numero destinataire</label>
                <div class="input-group">
                    <select class="form-select bg-white text-dark border-end-0" name="prefix_destinataire" style="max-width: 90px; flex: 0 0 90px;" required>
                        <?php foreach ($prefixes as $prefixe): ?>
                        <option value="<?= esc($prefixe['prefix']) ?>"><?= esc($prefixe['prefix']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control border-start-0" name="numero_destinataire" placeholder="12345678" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" min="1" class="form-control" id="montantTransfertClient" name="montant" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 card-important">Transferer</button>
        </form>
    </div>
</div>

<script type="application/json" id="tranchesTransfertClient">
    <?= json_encode($tranches) ?>
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initApercuFrais === 'function') {
            initApercuFrais('montantTransfertClient', 'tranchesTransfertClient', 'apercuFraisTransfertClient');
        }
    });
</script>

<?= $this->include('layout/client/footer') ?>