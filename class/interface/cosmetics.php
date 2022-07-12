<?php

interface CosmeticsInterface
{
    public function meta_title(string $product);
    public function title(string $product);
    public function url(string $product);
    public function img(string $product);
    public function text_button(string $product);
    public function catalog(string $product);

}