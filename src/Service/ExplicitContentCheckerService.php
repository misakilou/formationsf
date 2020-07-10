<?php

namespace App\Service;

use App\Entity\Article;

class ExplicitContentCheckerService
{
    private $banned_word = 'caca';

    /**
     * @param $content
     * @return bool
     */
    public function checkContent($content)
    {
        return strpos(strtolower($content) , $this->banned_word) !== false ;
    }

}