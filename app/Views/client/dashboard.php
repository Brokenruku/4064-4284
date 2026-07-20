<?= $this->include('layout/client/header') ?>

<h1 class="h3 mb-4">Bonjour <?= esc($resume['telephone'] ?? '') ?></h1>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h6 class="card-title">Solde actuel</h6>
                <p class="fs-3 mb-0"><?= number_format($resume['solde'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Depots effectues</h6>
                <p class="fs-3 mb-0"><?= esc($resume['nb_depots'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Frais payes</h6>
                <p class="fs-3 mb-0"><?= number_format($resume['total_frais_payes'] ?? 0, 0, ',', ' ') ?> Ar</p>
            </div>
        </div>
    </div>
</div>

<?= $this->include('layout/client/footer') ?>