<?php

class Catalog
{

    private const ERR_ARTICLE_NOT_FOUND = 'Articolul cu id-ul %s nu exista in baza de date';

    private const SEQ_PATH =  ROOT_FOLDER . '/.seq';
    private const DB_PATH = ROOT_FOLDER . '/db.xml';

    private $catalog;

    public function __construct()
    {
        $this->catalog = new SimpleXMLElement(self::DB_PATH, 0, true);
    }

    public function addArticle(Article $article)
    {
        $id = $this->nextId();

        $articleElement = $this->catalog->addChild('article');
        $articleElement->addAttribute('id', $id);
        $articleElement->addChild('title', $article->getTitle());
        $articleElement->addChild('category', $article->getCategory());

        $this->catalog->asXML(self::DB_PATH);

        return $article->withId($id);
    }

    public function updateArticle(Article $article)
    {
        foreach ($this->catalog as $articleElement) {
            $articleId = (string)$articleElement['id'];

            if ($articleId == $article->getId()) {
                $articleElement->title = $article->getTitle();
                $articleElement->category = $article->getCategory();
                $this->catalog->asXML(self::DB_PATH);

                return $article;
            }
        }

        throw new Exception(sprintf(self::ERR_ARTICLE_NOT_FOUND, $article->getId()));
    }

    public function deleteArticle(Article $article)
    {
        $offset = 0;
        foreach ($this->catalog as $articleElement) {
            $articleId = (string)$articleElement['id'];

            if ($articleId == $article->getId()) {
                unset($this->catalog->article[$offset]);
                $this->catalog->asXML(self::DB_PATH);

                return;
            }

            $offset++;
        }

        throw new Exception(sprintf(self::ERR_ARTICLE_NOT_FOUND, $article->getId()));
    }

    public function getAllArticles()
    {
        $list = [];

        foreach ($this->catalog as $articleElement) {
            $articleId = (string)$articleElement['id'];
            $articleTitle = (string)$articleElement->title;
            $articleCategory = (string)$articleElement->category;

            $list[] = new Article($articleId, $articleTitle, $articleCategory);
        }

        return $list;
    }

    public function getArticleWithId($id)
    {
        foreach ($this->catalog as $articleElement) {
            $articleId = (string)$articleElement['id'];

            if ($articleId == $id) {
                $articleTitle = (string)$articleElement->title;
                $articleCategory = (string)$articleElement->category;

                return new Article($articleId, $articleTitle, $articleCategory);
            }
        }

        return null;
    }

    private function nextId()
    {
        if (!file_exists(self::SEQ_PATH)) {
            if (false === file_put_contents(self::SEQ_PATH, $id = 1, LOCK_EX)) {
                throw new Exception('Nu am putut genera urmatorul id');
            }

            return $id;
        }

        if (false === ($content = file_get_contents(self::SEQ_PATH))) {
            throw new Exception('Nu am putut genera urmatorul id');
        }

        $id = (int)trim($content);

        if (false === file_put_contents(self::SEQ_PATH, ++$id, LOCK_EX)) {
            throw new Exception('Nu am putut genera urmatorul id');
        }

        return $id;
    }
}
