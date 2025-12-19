<?php
$errors = $errors ?? [];
?>
<h1>Ajouter un nouveau jeu</h1>
<form method="post" action="/games/add-new-game">
<input type="text" class="list-card" name="title" placeholder="Nom"/>
    <?php if (!empty($errors['title'])): ?><small><?= $errors['title'] ?></small><?php endif; ?>
<input type="text" class="list-card" name="platform" placeholder="Plateforme"/>
    <?php if (!empty($errors['platform'])): ?><small><?= $errors['platform'] ?></small><?php endif; ?>
<input type="text" class="list-card" name="genre" placeholder="Genre"/>
    <?php if (!empty($errors['genre'])): ?><small><?= $errors['genre'] ?></small><?php endif; ?>
<input type="number" class="list-card" name="releaseYear" placeholder="Année de sortie"/>
    <?php if (!empty($errors['releaseYear'])): ?><small><?= $errors['releaseYear'] ?></small><?php endif; ?>
<input type="number" class="list-card" name="rating" placeholder="Note"/>
    <?php if (!empty($errors['rating'])): ?><small><?= $errors['rating'] ?></small><?php endif; ?>
<input type="text" class="list-card" name="description" placeholder="Description"/>
    <?php if (!empty($errors['description'])): ?><small><?= $errors['description'] ?></small><?php endif; ?>
<input type="text" class="list-card" name="notes" placeholder="Notes"/>
    <?php if (!empty($errors['notes'])): ?><small><?= $errors['notes'] ?></small><?php endif; ?>

<button class="btn-random" type="submit">Ajouter</button>
</form>