<?php

require ROOT_DIR . '/class/interface/cosmetics.php';

class CosmeticsController implements CosmeticsInterface 
{
    public string $meta_title   = '';
    public string $title        = '';
    public string $url          = '';
    public string $img          = '';
    public string $text_button  = '';
    public string $partner_link = '?utm_source=partners&utm_medium=cpa&utm_campaign=691&utm_content=46gog&oid=j05afpv8l&wid=46gog&statid=659_'; // &sub={campaign_name_lat}&sub2={ad_id}&sub3={keyword}

    private array $data     = [];
    private array $document = [];
    public array $products  = [];
    

    /**
     * 
     * 
     */
    public function __construct(array $data, string $product, string $get) 
    {
        $this->data = $data;
        $this->document = require ROOT_DIR . '/language/document.php';
        $this->sub($get);

        $this->meta_title($product);
        $this->title($product);
        $this->url($product);
        $this->img($product);
        $this->text_button($product);
         $this->catalog($product);
    }

    
    /**
     * 
     * 
     */
    public function meta_title(string $product) 
    {
        if ( !isset($this->data[$product]) || !array_key_exists('model', $this->data[$product]) || array_key_exists('model', $this->data[$product]) && empty($this->data[$product]['model']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->meta_title = $data['default']['title'];
        }

        return $this->meta_title = strip_tags($this->data[$product]['description'] .' '. $this->data[$product]['vendor'] .' '. $this->data[$product]['model']);
    }

    
    /**
     * 
     * 
     */
    public function title(string $product) 
    {
        if ( !isset($this->data[$product]) || !array_key_exists('model', $this->data[$product]) || array_key_exists('model', $this->data[$product]) && empty($this->data[$product]['model']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->title = sprintf('<h1 class="display-1">%s</h1>', $data['default']['title']);
        }

        return $this->title = sprintf('<h1 class="display-1"><div class="display-3" style="font-weight: 900;">%s</div> <div class="display-6 pb-4">%s</div>%s</h1>', $this->data[$product]['vendor'], $this->data[$product]['description'], $this->data[$product]['model']);
    }


    /**
     * 
     * 
     */
    public function url(string $product)
    {
        if ( isset($this->data[$product]['products']) && !empty($this->data[$product]['products']) )
            return $this->url = $this->document['link_catalog'];

        if ( !isset($this->data[$product]) || !array_key_exists('url', $this->data[$product]) || array_key_exists('url', $this->data[$product]) && empty($this->data[$product]['url']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->url = $data['default']['url'] . $this->partner_link;
        }

        return $this->url = $this->data[$product]['url'] . $this->partner_link;
    }



    /**
     * 
     * 
     */
    private function sub(string $get = '')
    {
        if ( preg_match('~utm_~', $get) )
        {
            parse_str($get, $query);

            $n = 1;
            $sub = '';

            foreach ($query as $key => $value) 
            {
                if ( preg_match('~utm_campaign|utm_content|utm_term~', $key) ) 
                {
                    
                    if ($n == 1)
                        $sub .= '&sub='. $value;

                    else 
                        $sub .= '&sub'. $n .'='. $value;

                    $n++;
                }
            }

            return $this->partner_link .= $sub;
        }
    }


    /**
     * 
     * 
     */
    public function img(string $product) 
    {
        if ( !isset($this->data[$product]) || !array_key_exists('img', $this->data[$product]) || array_key_exists('img', $this->data[$product]) && empty($this->data[$product]['img']) ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->img = $data['default']['img'];
        }
        
        return $this->img = $this->data[$product]['img'];
    }


    /**
     * 
     * 
     */
    public function text_button(string $product)
    {
        if ( isset($this->data[$product]['products']) && !empty($this->data[$product]['products']) )
            return $this->text_button = $this->document['text_button_catalog'];
        
        /*if ( 
            !isset($this->data[$product]) || 
            !array_key_exists('text_button', $this->data[$product]) || 
            array_key_exists('text_button', $this->data[$product]) && empty($this->data[$product]['text_button']) 
        ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->text_button = $data['default']['text_button'];
        }*/

        return $this->text_button = $this->document['text_button_single'];
    }



    /**
     * 
     * 
     */
    public function catalog(string $product) 
    {
        if ( 
            !isset($this->data[$product]) || 
            !array_key_exists('products', $this->data[$product]) || 
            array_key_exists('products', $this->data[$product]) && empty($this->data[$product]['products']) 
        ) 
        {
            $data = require ROOT_DIR . '/data/default.php';
            return $this->products = [];
        }

        foreach ($this->data[$product]['products'] as $product_cat) {
            $this->data[$product_cat]['url'] = $this->data[$product_cat]['url'] . $this->partner_link;
            $this->data[$product_cat]['text_button'] = $this->document['text_button_catalog_product'];
            $this->products[] = $this->data[$product_cat];
        }

    }


}