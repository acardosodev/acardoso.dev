<?php

/*
 * This file is part of flarumite/flarum-decontaminator.
 *
 * Copyright (c) 2020 Flarumite.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarumite\PostDecontaminator\Api\Controller;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\User\AssertPermissionTrait;
use Flarumite\PostDecontaminator\Api\Serializer\PostDecontaminatorSerializer;
use Flarumite\PostDecontaminator\PostDecontaminatorRepository;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ShowPostDecontaminatorController extends AbstractShowController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = PostDecontaminatorSerializer::class;

    /**
     * @var PostDecontaminatorRepository
     */
    protected $pages;

    /**
     * @param PostDecontaminatorRepository $pages
     */
    public function __construct(PostDecontaminatorRepository $pages)
    {
        $this->pages = $pages;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertAdmin($actor);

        $id = array_get($request->getQueryParams(), 'id');

        return $this->pages->findOrFail($id, $actor);
    }
}
