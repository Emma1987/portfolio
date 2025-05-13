<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@Symfony' => true,
        '@PER-CS' => true,
        '@DoctrineAnnotation' => true,
    ])
    ->setFinder($finder)
;
