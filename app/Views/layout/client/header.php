<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/client">Mon compte</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navClient">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navClient">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/client">Solde</a></li>
                    <li class="nav-item"><a class="nav-link" href="/client/depot">Depot</a></li>
                    <li class="nav-item"><a class="nav-link" href="/client/retrait">Retrait</a></li>
                    <li class="nav-item"><a class="nav-link" href="/client/transfert">Transfert</a></li>
                    <li class="nav-item"><a class="nav-link" href="/client/historique">Historique</a></li>
                    <li class="nav-item"><a class="nav-link" href="/logout">Deconnexion</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container my-4">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('message')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('erreur')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('erreur')) ?></div>
        <?php endif; ?>