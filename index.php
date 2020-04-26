<?php

require 'init.php';

$articles = (new Catalog())->getAllArticles();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catalog - Home</title>
</head>
<body>
    <?php if (count($articles) === 0): ?>
    <div>
        <p><strong>Nu am gasit niciun articol in baza de date</strong></p>
        <p><a href="save.php">Adauga un articol</a></p>
    </div>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div style="border: 1px dashed black; padding: 10px; margin: 10px">
                <h1><?php echo htmlentities($article->getTitle()); ?></h1>
                <p><strong>Categorie:</strong> <?php echo htmlentities($article->getCategory()); ?></p>
                <p><a href="save.php?id=<?php echo $article->getId(); ?>">Editeaza</a>&nbsp;<a href="delete.php?id=<?php echo $article->getId(); ?>">Sterge</a></p>
            </div>
        <?php endforeach; ?>
        <div style="margin: 10px">
            <p><a href="save.php">Adauga un articol</a></p>
        </div>
    <?php endif; ?>
</body>
</html>