<?php

/*
 * This file is part of flarumite/flarum-decontaminator.
 *
 * Copyright (c) 2020 Flarumite.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarumite\PostDecontaminator\Listeners;

use Flarumite\PostDecontaminator\PostDecontaminatorModel;
use Flarumite\PostDecontaminator\Util\DecontaminationProcessor;
use FoF\UserBio\Event\BioChanged;

class SaveBio
{
    private $decontaminationProcessor;

    public function __construct(DecontaminationProcessor $decontaminationProcessor)
    {
        $this->decontaminationProcessor = $decontaminationProcessor;
    }

    /**
     * @param BioChanged $event
     */
    public function handle(BioChanged $event): void
    {
        if ($event->actor->hasPermission('user.bypassDecontaminator')) {
            return;
        }

        PostDecontaminatorModel::query()
            ->where('event', 'save')
            ->each(function (PostDecontaminatorModel $model) use ($event) {
                $this->decontaminationProcessor->processBio($model, $event->user);
            });
    }
}
