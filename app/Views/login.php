<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/login.css">
</head>
<body>

  <div class="glass-card text-center">
    <h3 class="fw-normal mb-4 text-uppercase" style="letter-spacing: 2px;">Login</h3>

    <?php if (session()->getFlashdata('erreur')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('erreur')) ?></div>
    <?php endif; ?>

    <form class="w-100 px-3" id="loginForm" action="/login" method="post">
      <div class="input-group mb-3">
        <select class="form-select bg-dark text-white " name="prefix_id" style="max-width: 90px; flex: 0 0 90px;" required>
          <?php foreach ($prefixes as $prefixe): ?>
          <option value="<?= esc($prefixe['id']) ?>"><?= esc($prefixe['prefix']) ?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" class="form-control glass-input border-start-0" name="numero" placeholder="Numero Telephone" aria-label="Numero" required>
      </div>

      <div class="my-3">
        <button type="submit" class="btn btn-glass text-uppercase fw-bold">Login</button>
      </div>

        <a href="/operateur/creation-operation" class="glass-link">connexion admin operateur</a>
      </div>
    </form>
  </div>

<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/login.js"></script>

</body>
</html>