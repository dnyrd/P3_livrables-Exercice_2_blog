<?php 
    /** 
     * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun. 
     * Et un formulaire pour ajouter un article. 
     */
?>

<h2>Edition des articles</h2>

<div class="adminArticle">
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="content"><?= $article->getContent(200) ?></div>
            <div><a class="submit" href="index.php?action=showUpdateArticleForm&id=<?= $article->getId() ?>">Modifier</a></div>
            <div><a class="submit" href="index.php?action=deleteArticle&id=<?= $article->getId() ?>" <?= Utils::askConfirmation("Êtes-vous sûr de vouloir supprimer cet article ?") ?> >Supprimer</a></div>
        </div>
    <?php } ?>
</div>

<div class="adminActions">
    <a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>
    <a class="submit" href="index.php?action=stats">Voir les statistiques</a>
</div>

<style>
.adminActions {
    margin-top: 20px;
}

.adminActions .submit {
    margin-right: 10px;
}

.adminArticle {
    margin-bottom: 20px;
    background-color: var(--headerColor); /* Orange au lieu de vert */
    color: white;
}

.adminActions .adminButton {
    margin-right: 10px;
    background-color: var(--headerColor); /* Orange au lieu de vert foncé */
}

.adminActions .adminButton:hover {
    background-color: var(--titleColor); /* Rouge au survol */
}

</style>
