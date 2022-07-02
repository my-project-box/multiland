<?php

require ROOT_DIR . '/class/interface/default.php';

class DefaultController implements DefaultInterface
{
    public $meta_title = '';

    public $title       = '';
    public $url         = '';
    public $img         = '';
    public $text_button = '';
    public $aff         = '?utm_source=partners&utm_medium=cpa&utm_campaign=691&utm_content=46gog&oid=j05afpv8l&wid=46gog&statid=659_';

    private $data = [];

    public function __construct(array $data = [], string $product = '') 
    {
        $this->data = $data;
        $this->meta_title($product);
        $this->title($product);
        $this->url($product);
        $this->img($product);
        $this->text_button($product);
    }


    public function meta_title(string $product) 
    {
        return $this->meta_title = strip_tags($this->data[$product]['title']);
    }

    
    public function title(string $product) 
    {
        return $this->title = sprintf('<h1 class="display-1">%s</h1>', $this->data[$product]['title']);
    }


    public function url(string $product)
    {
        return $this->url = $this->data[$product]['url'] . $this->aff;
    }


    public function img(string $product) 
    {
        return $this->img = $this->data[$product]['img'];
    }


    public function text_button(string $product)
    {
        return $this->text_button = $this->data[$product]['text_button'];
    }
}