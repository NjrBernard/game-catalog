<?php

$games = $featuredGames ?? [];
$total = $total ?? 0;
?>

<div >
<h1>Top <?= count($games) ?> featured games</h1>
<h2>Sur un total de <?= $total ?> jeux</h2><br><br>
</div>

<table class="tableau"> 

<?php $i = 0; foreach ($games as $game) : $i++ ?>
        
        <tr class="ligne ">
        <td class="classement <?php if($i === 1) { echo 'first'; } 
                        elseif($i === 2) { echo 'second'; } 
                        elseif($i === 3) { echo 'third'; } ?>"> <?=$i ?></td>
        <td> <?= $game['title'] ?> </td>

        </tr>
    
<?php  endforeach; ?>

</table>
<br>
    <a href="/random-game"><button class="btn-random">Jeu aléatoire</button></a>
