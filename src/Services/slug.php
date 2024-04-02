<?php
namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class Slugger
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function someMethod(): void
    {
        $slug = $this->slugger->slug('...');
    }
    
    public static function slugify($text, string $divider = '-')
    {
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
    }
}

