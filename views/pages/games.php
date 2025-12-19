<?php
?>


<?php
$games ??= [];

?>

<h2>Liste des jeux</h2>
<h3>Classés par notes</h3>
    
        <?php foreach ($games as $game): ?>
            <div class="list-card">
                <div class="assoce"><?= $game['title'] ?></div>
                    <div class="valeur"><a href="/games/<?= $game['id'] ?>">Naviguer vers le détail</a></div>
                
            </div>


<?php endforeach; ?>


