<?php

require 'init.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $catalog = new Catalog();
    $article = $catalog->getArticleWithId($id);

    if (null !== $article) {
        $catalog->deleteArticle($article);
    }
}

header('Location: index.php');
exit();

