<?php

class Article
{

    private $id;
    private $title;
    private $category;

    public function __construct($id, $title, $category)
    {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function withId($id)
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }
}
