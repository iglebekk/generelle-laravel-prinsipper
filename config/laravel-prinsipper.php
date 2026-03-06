<?php

return [
    'source' => null,
    'targets' => [
        'docs/laravel-prinsipper.md',
        'AGENTS.md',
        'CLAUDE.md',
        'GEMINI.md',
    ],
    'overwrite' => [
        'docs/laravel-prinsipper.md',
    ],
    'section_header' => '## Generelle Laravel-prinsipper (synkronisert)',
    'start_marker' => '<!-- LARAVEL-PRINSIPPER:START -->',
    'end_marker' => '<!-- LARAVEL-PRINSIPPER:END -->',
];
