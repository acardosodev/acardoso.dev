<?php

/*
 * This file is part of flarumite/flarum-decontaminator.
 *
 * Copyright (c) 2020 Flarumite.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarumite\Tests\Decontaminator\Traits;

use Flarumite\PostDecontaminator\Command\CreateProfanity;
use Flarumite\PostDecontaminator\Command\CreateProfanityHandler;
use Flarumite\PostDecontaminator\PostDecontaminatorValidator;

trait ProfanityTestTrait
{
    public function createProfanity($actor)
    {
        $command = $this->getMockBuilder(CreateProfanity::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$actor, $data = [
                'attributes' => $this->attributesArray,
            ]])
            ->getMock();

        $validator = $this->getMockBuilder(PostDecontaminatorValidator::class)
            ->disableOriginalConstructor()
            ->setMethods(['assertValid'])
            ->getMock();

        $validator->method('assertValid')
            ->willReturn('true');

        $postDecontaminatorModel = \Mockery::mock('overload:'.\Flarumite\PostDecontaminator\PostDecontaminatorModel::class);
        $postDecontaminatorModel->shouldReceive('build')
            ->andReturn($this->getAttributes());
        $commandHandler = $this->getMockBuilder(CreateProfanityHandler::class)
            ->enableOriginalConstructor()
            ->setConstructorArgs([$validator])
            ->setMethods(['save'])
            ->getMock();

        $commandHandler->method('save')
            ->willReturn($postDecontaminatorModel);

        return $commandHandler->handle($command);
    }
}
