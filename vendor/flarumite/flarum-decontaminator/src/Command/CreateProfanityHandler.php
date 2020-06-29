<?php

/*
 * This file is part of flarumite/flarum-decontaminator.
 *
 * Copyright (c) 2020 Flarumite.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarumite\PostDecontaminator\Command;

use Flarum\User\AssertPermissionTrait;
use Flarumite\PostDecontaminator\PostDecontaminatorModel;
use Flarumite\PostDecontaminator\PostDecontaminatorValidator;

class CreateProfanityHandler
{
    use AssertPermissionTrait;

    protected $validator;

    public function __construct(PostDecontaminatorValidator $validator)
    {
        $this->validator = $validator;
    }

    public function handle(CreateProfanity $command)
    {
        $actor = $command->actor;
        $data = $command->data;

        $this->assertAdmin($actor);

        $page = PostDecontaminatorModel::build(
            array_get($data, 'attributes.name'),
            array_get($data, 'attributes.regex'),
            array_get($data, 'attributes.replacement'),
            array_get($data, 'attributes.flag'),
            array_get($data, 'attributes.event')
        );

        $this->validator->assertValid($page->getAttributes());

        $page->save();

        return $page;
    }
}
