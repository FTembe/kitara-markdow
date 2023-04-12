## A simple regex-based Markdown parser in PHP.

This Markdown parser supports basic formatting such as bold and italic text, headers, links, and paragraphs. It uses regular expressions to search for patterns in the Markdown text and replace them with corresponding HTML tags.

## Installation 

To install this package no can use composer
```bash 
composer require ftembe/kitara-markdown
```
Or 
```bash
git clone https://github.com/FTembe/kitara-markdown.git
```
## Usage

```php

require('vendor/autoload.php');

use Kitara\Parser\Markdown as Parser;

$markdown = new Parser;

echo $markdown::render('## My name is Francisco Tembe');

```
