<?php

class CommentController 
{
    /**
     * Ajoute un commentaire.
     * @return void
     */
    public function addComment() : void
    {
        // Récupération des données du formulaire.
        $pseudo = Utils::request("pseudo");
        $content = Utils::request("content");
        $idArticle = Utils::request("idArticle");

        // On vérifie que les données sont valides.
        if (empty($pseudo) || empty($content) || empty($idArticle)) {
            throw new Exception("Tous les champs sont obligatoires. 3");
        }

        // On vérifie que l'article existe.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($idArticle);
        if (!$article) {
            throw new Exception("L'article demandé n'existe pas.");
        }

        // On crée l'objet Comment.
        $comment = new Comment([
            'pseudo' => $pseudo,
            'content' => $content,
            'idArticle' => $idArticle
        ]);

        // On ajoute le commentaire.
        $commentManager = new CommentManager();
        $result = $commentManager->addComment($comment);

        // On vérifie que l'ajout a bien fonctionné.
        if (!$result) {
            throw new Exception("Une erreur est survenue lors de l'ajout du commentaire.");
        }

        // On redirige vers la page de l'article.
        Utils::redirect("showArticle", ['id' => $idArticle]);
    }

    /**
     * Supprime un commentaire (accessible sans authentification).
     * @return void
     */
    public function deleteComment() : void
    {
        // Récupération des données
        $id = Utils::request("id", -1);
        $idArticle = Utils::request("idArticle", -1);

        // Vérification que le commentaire existe
        $commentManager = new CommentManager();
        $comment = $commentManager->getCommentById($id);
        
        if (!$comment) {
            throw new Exception("Le commentaire demandé n'existe pas.");
        }

        // Suppression du commentaire
        $result = $commentManager->deleteComment($comment);

        if (!$result) {
            throw new Exception("Une erreur est survenue lors de la suppression du commentaire.");
        }

        // Redirection vers la page de l'article
        Utils::redirect("showArticle", ['id' => $idArticle]);
    }
}
