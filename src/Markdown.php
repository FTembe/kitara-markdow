<?php

namespace Kitara\Parser;

class Markdown
{

    public static $rules = array(
        '/\n(#+)(.*)/' => 'self::heading',                                  // headers
        '/```(.*?)```/s' => 'self::codeBlock',                              // code blocks
        '/\!\[([^\[]+)\]\(([^\)]+)\)/' => '<img src=\'\2\' alt=\'\1\' />',  // images
		'/\[([^\[]+)\]\(([^\)]+)\)/' => '<a href=\'\2\'>\1</a>',            // links
		'/(\*\*|__)(.*?)\1/' => '<strong>\2</strong>',                      // bold
		'/(\*|_)(.*?)\1/' => '<em>\2</em>',                                 // emphasis
		'/\~\~(.*?)\~\~/' => '<del>\1</del>',                               // del
		'/\:\"(.*?)\"\:/' => '<q>\1</q>',                                   // quote
		'/`(.*?)`/' => '<code>\1</code>',                                   // inline code
		'/\n\*(.*)/' => 'self::ul_list',                                    // ul lists
		'/\n[0-9]+\.(.*)/' => 'self::ol_list',                              // ol lists
		'/\n(&gt;|\>)(.*)/' => 'self::blockquote',                          // blockquotes
		'/\n-{5,}/' => "\n<hr />",                                          // horizontal rule
		'/\n([^\n]+)\n/' => 'self::paragraph',                              // add paragraphs
		'/<\/ul>\s?<ul>/' => '',                                            // fix extra ul
		'/<\/ol>\s?<ol>/' => '',                                            // fix extra ol
		'/<\/blockquote><blockquote>/' => "\n"                              // fix extra blockquote

    );

    private static function heading($regs)
    {
        list($tmp, $size, $content) = $regs;
        $level = strlen($size);
        return sprintf('<h%d>%s</h%d>', $level, trim($content), $level);
    }
    private static function paragraph ($regs) {
		$line = $regs[1];
		$trimmed = trim ($line);
		if (preg_match ('/^<\/?(ul|ol|li|h|p|bl|table|tr|th|td|code)/', $trimmed)) {
			return "\n" . $line . "\n";
		}
		if (! empty ($trimmed)) {
			return sprintf ("\n<p>%s</p>\n", $trimmed);
		}
		return $trimmed;
	}

	private static function ul_list ($regs) {
		$item = $regs[1];
		return sprintf ("\n<ul>\n\t<li>%s</li>\n</ul>", trim ($item));
	}

	private static function ol_list ($regs) {
		$item = $regs[1];
		return sprintf ("\n<ol>\n\t<li>%s</li>\n</ol>", trim ($item));
	}

	private static function blockquote ($regs) {
		$item = $regs[2];
		return sprintf ("\n<blockquote>%s</blockquote>", trim ($item));
	}

    private static function codeBlock($regs)
    {
        $item = $regs[1];
        $item = htmlentities($item, ENT_COMPAT);
        $item = str_replace("\n\n", '<br>', $item);
        $item = str_replace("\n", '<br>', $item);

        return sprintf("<pre><code>%s</code></pre>", trim($item));
    }

    public static function convertMarkDownToHtml($text)
    {
        $text = "\n" . $text . "\n";
        foreach (self::$rules as $regex => $replacement) {
            if (is_callable($replacement)) {
                $text = preg_replace_callback($regex, $replacement, $text);
            } else {
                $text = preg_replace($regex, $replacement, $text);
            }
        }
        return trim($text);
    }

    public static function render($text, $to = "HTML")
    {
        if ($to == 'HTML')
            return self::convertMarkDownToHtml($text);
        return $text;
    }
}
