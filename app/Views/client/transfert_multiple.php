<?= $this->include('layout/client/header') ?>

<h1 class="h3 mb-4">Envoi multiple</h1>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <?php if (empty($prefixes)): ?>
            <div class="alert alert-warning">Aucun prefixe disponible pour votre operateur</div>
        <?php else: ?>
        <form action="/client/transfert-multiple" method="post">
            <div class="mb-3">
                <label class="form-label">Montant total a repartir</label>
                <input type="number" step="0.01" min="1" class="form-control" id="montantTotalMultiple" name="montant_total" required>
            </div>
            <div class="alert alert-secondary" id="apercuRepartitionMultiple">
                Montant par destinataire : 0 Ar
            </div>
            <div id="listeDestinatairesMultiple">
                <div class="input-group mb-2 ligne-destinataire-multiple">
                    <select class="form-select bg-white text-dark border-end-0" name="prefix_destinataire[]" style="max-width: 90px; flex: 0 0 90px;" required>
                        <?php foreach ($prefixes as $prefixe): ?>
                        <option value="<?= esc($prefixe['prefix']) ?>"><?= esc($prefixe['prefix']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="text" class="form-control border-start-0" name="numero_destinataire[]" placeholder="12345678" required>
                    <button type="button" class="btn btn-outline-danger btn-supprimer-destinataire-multiple">X</button>
                </div>
            </div>
            <button type="button" class="btn btn-outline-secondary w-100 mb-3" id="btnAjouterDestinataireMultiple">Ajouter un destinataire</button>
            <button type="submit" class="btn btn-primary w-100 card-important">Envoyer</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<template id="modeleLigneDestinataireMultiple">
    <div class="input-group mb-2 ligne-destinataire-multiple">
        <select class="form-select bg-white text-dark border-end-0" name="prefix_destinataire[]" style="max-width: 90px; flex: 0 0 90px;" required>
            <?php foreach ($prefixes as $prefixe): ?>
            <option value="<?= esc($prefixe['prefix']) ?>"><?= esc($prefixe['prefix']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" class="form-control border-start-0" name="numero_destinataire[]" placeholder="12345678" required>
        <button type="button" class="btn btn-outline-danger btn-supprimer-destinataire-multiple">X</button>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initTransfertMultiple === 'function') {
            initTransfertMultiple('listeDestinatairesMultiple', 'modeleLigneDestinataireMultiple', 'btnAjouterDestinataireMultiple', 'montantTotalMultiple', 'apercuRepartitionMultiple');
        }
    });
</script>

<?= $this->include('layout/client/footer') ?>