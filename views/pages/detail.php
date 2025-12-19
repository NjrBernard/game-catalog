<?php
$game ??= [];  
$id ??= 0;
?>

<?php if(!$game): ?>
    
    <h1>Le jeu demandé n'est pas trouvé</h1>
<?php else: ?>
    <div class="card">
    <h1><?= '#' . $game['id'] . ' ' . $game['title'] ?></h1>

    <p class="attribut">Plateforme: <?= $game['platform'] ?></p>
    <p class="attribut">Genre: <?= $game['genre'] ?></p>
    <p class="attribut">Note: <?= $game['rating'] ?></p>
    <p class="attribut">Année de sortie: <?= $game['releaseYear'] ?></p>

    </div>
<?php endif; ?>