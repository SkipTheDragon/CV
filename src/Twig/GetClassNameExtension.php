<?php

namespace App\Twig;

use ReflectionClass;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GetClassNameExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('class', [$this, 'getClass']),
        ];
    }

    public function getClass($object)
    {
        return (new ReflectionClass($object))->getShortName();
    }
}
