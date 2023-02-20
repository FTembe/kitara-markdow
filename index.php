<?php
require('vendor/autoload.php');

use Kitara\Parser\Markdown as Parser;

$markdown = new Parser;

echo ($markdown::render('######Francisco Tembe'));
