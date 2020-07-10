<?php


namespace App\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AgeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('age', [$this , 'getAge']) ,
        ];
    }

    /**
     * Return the diff between the datenow and the passed arg
     * @param \DateTime $dateTime
     * @return string
     */

    public function getAge(\DateTime $dateTime){

        $now = new \DateTime('now');
        $diff = $now->diff($dateTime);
        return $diff->format('%y');

    }
}