<?php

namespace Helper;

final class Debug {

public static function dump_block(string $titre, $value) : void {
    echo "<h2 style='margin: 16px; color: chartreuse'>";
    echo $titre;
    echo "</h2>";
    var_dump($value);
}
}