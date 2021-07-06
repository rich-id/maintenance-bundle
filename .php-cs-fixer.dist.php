<?php

declare(strict_types=1);

$workingDirectory = strpos(__DIR__, 'vendor/richcongress/static-analysis') === false
    ? __DIR__
    : __DIR__ . '/../../../..';

$finder = (new PhpCsFixer\Finder())
    ->in(realpath($workingDirectory))
    ->exclude('var')
    ->exclude('reports')
    ->exclude('coverage')
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR2'    => true,
        'braces'   => [
            'position_after_functions_and_oop_constructs' => 'next',
        ],
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
            ],
        ],
        'concat_space'               => ['spacing' => 'one'],
        'declare_strict_types'       => true,
        'increment_style'            => ['style' => 'post'],
        'native_function_invocation' => [
            'include' => ['@internal'],
            'scope'   => 'namespaced',
            'strict'  => true,
        ],
        'no_null_property_initialization' => true,
        'no_superfluous_elseif'           => true,
        'no_useless_else'                 => true,
        'phpdoc_line_span'                => [
            'property' => 'single',
            'const'    => 'single',
            'method'   => 'single',
        ],
        'phpdoc_no_useless_inheritdoc' => true,
        'simplified_if_return'         => true,
        'single_line_throw'            => false,
        'void_return'                  => true,
        'yoda_style'                   => false,
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
