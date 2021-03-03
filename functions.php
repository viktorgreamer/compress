<?php
function scandirrecurcive($dir) {

    $tree = [];
    $dirs = scandir($dir);
    if ($dirs) {
        foreach ($dirs as $item) {
            if ($item == ".." || $item == ".") continue;
            if (pathinfo($item, PATHINFO_EXTENSION)) {
                $tree[] = $dir . "/" . $item;
            } else {
                $tree = array_merge($tree,scandirrecurcive($dir . "/" . $item));
            }
        }
    }

    return $tree;

}