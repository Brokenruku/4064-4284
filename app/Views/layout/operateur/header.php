<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>operateur</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>


<body>
    <?= $this->include('layout/operateur/nav') ?>
    <main class="container my-4">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success" role="alert"><?= esc(session()->getFlashdata('message')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('erreur')): ?>
            <div class="alert alert-danger" role="alert"><?= esc(session()->getFlashdata('erreur')) ?></div>
        <?php endif; ?>