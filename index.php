<?php
require('vendor/autoload.php');

use Kitara\Parser\Markdown as Parser;

$markdown = new Parser;

var_dump($markdown::render('


######Francisco Tembe



'));
