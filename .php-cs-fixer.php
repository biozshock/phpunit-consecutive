<?php

require 'vendor/autoload.php';

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

return (new PhpCsFixer\Config())
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRules([
        '@Symfony'  => true,
        'phpdoc_to_comment' => false,
        'protected_to_private' => false,
        'ordered_imports' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'no_unused_imports' => true,
        'no_alternative_syntax' => true,
        'multiline_whitespace_before_semicolons' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
        ],
    ])
    ->setFinder($finder);
