<?php

/*
 * This file is part of flarumite/flarum-decontaminator.
 *
 * Copyright (c) 2020 Flarumite.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarumite\PostDecontaminator\Util;

use Flarum\Discussion\Discussion;
use Flarum\Extension\ExtensionManager;
use Flarum\Flags\Command\CreateFlag;
use Flarum\Group\Group;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Flarumite\PostDecontaminator\PostDecontaminatorModel;
use Illuminate\Contracts\Bus\Dispatcher;

class DecontaminationProcessor
{
    private $matchedWord = null;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var Dispatcher
     */
    protected $bus;

    protected $flagsEnabled = false;

    public function __construct(ExtensionManager $extension, Dispatcher $bus, SettingsRepositoryInterface $settings)
    {
        $this->bus = $bus;
        $this->settings = $settings;

        if ($extension->isEnabled('flarum-flags') && class_exists(CreateFlag::class)) {
            $this->flagsEnabled = true;
        }
    }

    public function process(PostDecontaminatorModel $model, Post $post): void
    {
        if (!is_string($post->content)) {
            return;
        }

        if (preg_match($model->regex, trim($post->content), $this->matchedWord)) {
            $trimmedContent = trim($post->content);
            $post->content = $this->processRegEx($model, $trimmedContent, true);
            if ($this->flagsEnabled && $model->flag) {
                $post->afterSave(function ($post) use ($model) {
                    $this->raiseFlag($post, $model);
                });
            }
        }
    }

    public function processDiscussion(PostDecontaminatorModel $model, Discussion $discussion, $renamed = false): void
    {
        if (!is_string($discussion->title)) {
            return;
        }

        if (preg_match($model->regex, trim($discussion->title), $this->matchedWord)) {
            $trimmedTitle = trim($discussion->title);
            $discussion->title = $this->processRegEx($model, $trimmedTitle);

            if ($this->flagsEnabled && $model->flag) {
                if ($renamed) {
                    $post = Post::where('discussion_id', $discussion->id)->where('number', 1)->first();
                    if ($post !== null) {
                        $this->raiseFlag($post, $model);
                    }
                } else {
                    $discussion->afterSave(function ($discussion) use ($model) {
                        $post = Post::where('discussion_id', $discussion->id)->where('number', 1)->first();
                        if ($post !== null) {
                            $this->raiseFlag($post, $model);
                        }
                    });
                }
            }
        }
    }

    public function processBio(PostDecontaminatorModel $model, User $user): void
    {
        if (!is_string($user->bio)) {
            return;
        }

        if (preg_match($model->regex, trim($user->bio), $this->matchedWord)) {
            $trimmedBio = trim($user->bio);
            $user->bio = $this->processRegEx($model, $trimmedBio);
        }
    }

    private function processRegEx(PostDecontaminatorModel $model, string $content, bool $mark = false): ?string
    {
        if (empty($model->replacement)) {
            return $content;
        }

        $replacement = $model->replacement;

        if ($mark) {
            $replacement = '[FILTERED]'.$replacement.'[/FILTERED]';
        }

        return preg_replace($model->regex, $replacement, $content);
    }

    public function raiseFlag(Post $post, PostDecontaminatorModel $model, $matches = ''): void
    {
        $actor = User::find($post->user_id);

        if (!(bool) $this->settings->get('flarum-flags.can_flag_own')) {
            $actor = $this->findAdminUser();
        }

        if ($matches !== '') {
            $matches = ' ['.$matches.']';
        }

        $data = $this->buildDataArray(
            $model->name,
            $actor->id,
            $post->id
        );

        $this->bus->dispatch(new CreateFlag($actor, $data));
    }

    public function buildDataArray($name, $userId, $postId)
    {
        return [
            'type'       => 'flags',
            'attributes' => [
                'reason'       => null,
                'reasonDetail' => $name,
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'users',
                        'id'   => $userId,
                    ],
                ],
                'post' => [
                    'data' => [
                        'type' => 'posts',
                        'id'   => $postId,
                    ],
                ],
            ],
        ];
    }

    private function findAdminUser(): ?User
    {
        return User::leftJoin('group_user', 'group_user.group_id', 'users.id')
            ->where('group_user.group_id', Group::ADMINISTRATOR_ID)
            ->first();
    }
}
