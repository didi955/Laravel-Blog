<?php

namespace App\Utilities;

class FilterContent
{

    public static function apply($content, array $filter = [])
    {
        $filteredContent = $content;

        if (in_array('script', $filter)) {
            $filteredContent = self::filterScript($filteredContent);
        }
        if(in_array('style', $filter)){
            $filteredContent = self::filterStyle($filteredContent);
        }

        return $filteredContent;
    }

    private static function filterScript($content)
    {
        $filteredContent = $content;

        $filteredContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $filteredContent);

        return $filteredContent;
    }

    private static function filterStyle($content)
    {
        $filteredContent = $content;

        $filteredContent = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $filteredContent);

        return $filteredContent;
    }

}
