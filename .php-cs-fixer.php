<?php

$config = new PhpCsFixer\Config();

$finder = PhpCsFixer\Finder::create()
    ->notPath(['bootstrap/cache', 'storage', 'vendor'])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->name('*.php')
    ->in(__DIR__);

$rules = [
    'align_multiline_comment' => true,
    'array_indentation' => true,
    'array_syntax' => [
        'syntax' => 'short',
    ],
    'backtick_to_shell_exec' => true,
    'binary_operator_spaces' => ['operators' => ['|' => 'no_space']],
    'blank_line_after_namespace' => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement' => true,
    'braces' => true,
    'cast_spaces' => true,
    'class_attributes_separation' => [
        'elements' => [
            'method' => 'one',
        ],
    ],
    'class_definition' => [
        'single_line' => true,
    ],
    'class_keyword_remove' => false,
    'clean_namespace' => true,
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'combine_nested_dirname' => true,
    'compact_nullable_typehint' => true,
    'concat_space' => [
        'spacing' => 'one',
    ],
    'date_time_immutable' => true,
    'declare_equal_normalize' => true,
    'dir_constant' => true,
    'elseif' => true,
    'encoding' => true,
    'error_suppression' => true,
    'escape_implicit_backslashes' => true,
    'explicit_indirect_variable' => true,
    'explicit_string_variable' => true,
    'fopen_flag_order' => true,
    'fopen_flags' => [
        'b_mode' => false,
    ],
    'full_opening_tag' => true,
    'fully_qualified_strict_types' => true,
    'function_declaration' => true,
    'function_to_constant' => true,
    'function_typehint_space' => true,
    'heredoc_indentation' => true,
    'heredoc_to_nowdoc' => true,
    'implode_call' => true,
    'include' => true,
    'increment_style' => false,
    'indentation_type' => true,
    'is_null' => true,
    'line_ending' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => [
        'syntax' => 'short',
    ],
    'logical_operators' => true,
    'lowercase_cast' => true,
    'lowercase_keywords' => true,
    'lowercase_static_reference' => true,
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'mb_str_functions' => true,
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
    ],
    'modernize_types_casting' => true,
    'multiline_comment_opening_closing' => true,
    'multiline_whitespace_before_semicolons' => true,
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true, // xxxx
    'new_with_braces' => true,
    'no_alternative_syntax' => true,
    'no_binary_string' => true,
    'no_blank_lines_after_class_opening' => true,
    'no_blank_lines_after_phpdoc' => true,
    'no_blank_lines_before_namespace' => false,
    'no_break_comment' => true,
    'no_closing_tag' => true,
    'no_empty_comment' => true,
    'no_empty_phpdoc' => true,
    'no_empty_statement' => true,
    'no_extra_blank_lines' => [
        'tokens' => [
            'continue',
            'curly_brace_block',
            'extra',
            'parenthesis_brace_block',
            'square_brace_block',
            'throw',
            'use_trait',
            'switch',
            'case',
            'default',
        ],
    ],
    'no_homoglyph_names' => true,
    'no_leading_import_slash' => true,
    'no_leading_namespace_whitespace' => true,
    'no_mixed_echo_print' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_null_property_initialization' => true,
    'no_short_bool_cast' => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_around_offset' => true,
    'no_spaces_inside_parenthesis' => true,
    'no_superfluous_elseif' => true,
    'no_superfluous_phpdoc_tags' => false,
    'no_trailing_comma_in_singleline_array' => true,
    'no_trailing_whitespace' => true,
    'no_trailing_whitespace_in_comment' => true,
    'no_unneeded_control_parentheses' => true,
    'no_unneeded_curly_braces' => true,
    'no_unneeded_final_method' => true,
    'no_unreachable_default_argument_value' => true,
    'no_unset_cast' => true,
    'no_unused_imports' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'no_whitespace_before_comma_in_array' => true,
    'no_whitespace_in_blank_line' => true,
    'normalize_index_brace' => true,
    'not_operator_with_space' => false,
    'not_operator_with_successor_space' => false,
    'nullable_type_declaration_for_default_null_value' => true,
    'object_operator_without_whitespace' => true,
    'ordered_class_elements' => [
        'order' => [
            'use_trait',
            'constant', 'constant_public', 'constant_protected', 'constant_private',
            'property_static', 'property_public_static', 'property_protected_static', 'property_private_static',
            'property', 'property_public', 'property_protected', 'property_private',
            'construct', 'destruct', 'magic',
            'method', 'method_public', 'method_protected', 'method_private',
            'method_static', 'method_public_static', 'method_protected_static', 'method_private_static',
            'phpunit',
        ],
    ],
    'pow_to_exponentiation' => true,
    'return_assignment' => true,
    'return_type_declaration' => true,
    'self_accessor' => true,
    'semicolon_after_instruction' => true,
    'short_scalar_cast' => true,
    'simple_to_complex_string_variable' => true,
    'simplified_null_return' => true,
    'single_blank_line_at_eof' => true,
    'single_blank_line_before_namespace' => true,
    'single_class_element_per_statement' => true,
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'single_line_comment_style' => true,
    'single_quote' => true,
    'single_trait_insert_per_statement' => true,
    'space_after_semicolon' => [
        'remove_in_empty_for_expressions' => true,
    ],
    'standardize_increment' => true,
    'standardize_not_equals' => true,
    'switch_case_semicolon_to_colon' => true,
    'switch_case_space' => true,
    'ternary_operator_spaces' => true,
    'ternary_to_null_coalescing' => true,
    'trailing_comma_in_multiline' => true,
    'trim_array_spaces' => true,
    'unary_operator_spaces' => true,
    'visibility_required' => true,
    'whitespace_after_comma_in_array' => true,
];

return $config->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setHideProgress(true)
    ->setParallelConfig(
        PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect()
    );
