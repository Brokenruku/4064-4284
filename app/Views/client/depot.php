<?= $this->include('layout/client/header') ?>

<h1 class="h3 mb-4">Faire un depot</h1>

<div class="card" style="max-width: 480px;">
    <div class="card-body">
        <form action="/client/depot" method="post">
            <div class="mb-3">
                <label class="form-label">Montant</label>
                <input type="number" step="0.01" min="1" class="form-control" name="montant" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Deposer</button>
        </form>
    </div>
</div>

<?= $this->include('layout/client/footer') ?>