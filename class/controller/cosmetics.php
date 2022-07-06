<?php

require ROOT_DIR . '/class/interface/cosmetics.php';

class CosmeticsController implements CosmeticsInterface 
{
    public $meta_title = '';

    public $title       = '';
    public $url         = '';
    public $img         = '';
    public $text_button = '';
    public $aff         = '?utm_source=partners&utm_medium=cpa&utm_campaign=691&utm_content=46gog&oid=j05afpv8l&wid=46gog&statid=659_'; // &sub={campaign_name_lat}&sub2={ad_id}&sub3={keyword}

    private $data = [];
    

    public function __construct(array $data, string $product) 
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
        if ( !isset($this->data[$product]) || !array_key_exists('model', $this->data[$product]) || array_key_exists('model', $this->data[$product]) && empty($this->data[$product]['model']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->meta_title = $data['default']['title'];
        }

        return $this->meta_title = strip_tags($this->data[$product]['description'] .' '. $this->data[$product]['vendor'] .' '. $this->data[$product]['model']);
    }

    
    public function title(string $product) 
    {
        if ( !isset($this->data[$product]) || !array_key_exists('model', $this->data[$product]) || array_key_exists('model', $this->data[$product]) && empty($this->data[$product]['model']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->title = sprintf('<h1 class="display-1">%s</h1>', $data['default']['title']);
        }

        return $this->title = sprintf('<h1 class="display-1"><div class="display-3" style="font-weight: 900;">%s</div> <div class="display-6 pb-4">%s</div>%s</h1>', $this->data[$product]['vendor'], $this->data[$product]['description'], $this->data[$product]['model']);
    }


    public function url(string $product)
    {
        if ( !isset($this->data[$product]) || !array_key_exists('url', $this->data[$product]) || array_key_exists('url', $this->data[$product]) && empty($this->data[$product]['url']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->url = $data['default']['url'] . $this->aff;
        }

        return $this->url = $this->data[$product]['url'] . $this->aff;
    }


    public function img(string $product) 
    {
        if ( !isset($this->data[$product]) || !array_key_exists('img', $this->data[$product]) || array_key_exists('img', $this->data[$product]) && empty($this->data[$product]['img']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->img = $data['default']['img'];
        }
        
        return $this->img = $this->data[$product]['img'];
    }


    public function text_button(string $product)
    {
        if ( !isset($this->data[$product]) || !array_key_exists('text_button', $this->data[$product]) || array_key_exists('text_button', $this->data[$product]) && empty($this->data[$product]['text_button']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->text_button = $data['default']['text_button'];
        }

        return $this->text_button = $this->data[$product]['text_button'];
    }
}