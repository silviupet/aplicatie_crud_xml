<?php
require 'init.php';

$isEdit = isset($_GET['id']);
$formSubmitted = count($_POST) > 0;

if ($isEdit) {
    $article = (new Catalog())->getArticleWithId((int)$_GET['id']);
    if (null === $article) {
        header('Location: index.php');
        exit();
    }
}

$errors = [];
if ($formSubmitted) {
    $id = (int)$_POST['id'];
    $title = $_POST['title'];
    $category = $_POST['category'];

    if (strlen(trim($title)) === 0) {
        $errors['title'] = 'Titlul articolului nu poate fi gol';
    }

    if (strlen(trim($category)) === 0) {
        $errors['category'] = 'Categoria articolului trebuie specificata';
    }

    if (count($errors) === 0) {
        $article = new Article($id ?: null, $title, $category);
        $catalog = new Catalog();
        
        if ($id <= 0) {
            $catalog->addArticle($article);
        } else {
            $catalog->updateArticle($article);
        }

        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Catalog - <?php echo $isEdit ? 'Editeaza articol' : 'Adauga articol' ?></title>
</head>
<body>
    
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $isEdit ? (int)$_GET['id'] : '' ?>" />

        <?php if (count($errors) > 0): ?>
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p>
            <label for="title">Nume:</label>
            <input type="text" name="title" id="title" value="<?php echo isset($article) ? $article->getTitle() : '' ?>" />
        </p>
        <p>
            <label for="category">Category:</label>
            <input type="text" name="category" id="category" value="<?php echo isset($article) ? $article->getCategory() : '' ?>" />
        </p>

        <p>
            <input type="submit" value="Salveaza" />
        </p>
    </form>
</body>
</html>