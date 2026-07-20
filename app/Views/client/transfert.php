<?= $this->include('layout/client/header') ?>

<h1 class="h3 mb-4">Faire un transfert</h1>

<div class="mb-3" style="max-width: 480px;">
    <a href="/client/transfert-multiple" class="btn btn-outline-secondary btn-sm">Envoi multiple</a>
</div>

<div class="card" style="max-width: 480px;">
    <div class="card-body">
        <form action="/client/transfert" method="post">
            <div class="mb-3">
                <label class="form-label">Numero destinataire</label>
                <div class="input-group">
                    <select class="form-select bg-white text-dark border-end-0" name="prefix_destinataire" id="prefixDestinataireTransfertClient" style="max-width: 90px; flex: 0 0 90px;" required>
                        <?php foreach ($prefixes as $prefixe): ?>
                        <option value="<?= esc($prefixe['prefix']) ?>" data-organisation="<?= esc($prefixe['organisation']) ?>"><?= esc($prefixe['prefix']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control border-start-0" name="numero_destinataire" placeholder="12345678" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" min="1" class="form-control" id="montantTransfertClient" name="montant" required>
            </div>
            <div class="mb-3 form-check" id="blocFraisRetraitClient" style="display: none;">
                <input type="checkbox" class="form-check-input" id="inclureFraisRetraitClient" name="inclure_frais_retrait" value="1">
                <label class="form-check-label" for="inclureFraisRetraitClient">Inclure les frais de retrait pour le destinataire</label>
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
        if (typeof initOptionFraisRetrait === 'function') {
            initOptionFraisRetrait('prefixDestinataireTransfertClient', '<?= esc($compte['operateur'] ?? '') ?>', 'blocFraisRetraitClient', 'inclureFraisRetraitClient');
        }
    });
</script>

<?= $this->include('layout/client/footer') ?>