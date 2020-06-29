<?php

/*
 * This file is part of askvortsov/flarum-copy-links.
 *
 * Copyright (c) 2020 Alexander Skvortsov.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Askvortsov\FlarumCopyLinks;

use Flarum\Extend;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js'),
        
    
    new Extend\Locales(__DIR__ . '/resources/locale')
];
