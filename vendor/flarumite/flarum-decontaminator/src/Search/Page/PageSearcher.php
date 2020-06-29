<?php

/*
 * This file is part of flarumite/flarum-decontaminator.
 *
 * Copyright (c) 2020 Flarumite.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarumite\PostDecontaminator\Search\Page;

use Flarum\Search\ApplySearchParametersTrait;
use Flarum\Search\GambitManager;
use Flarum\Search\SearchCriteria;
use Flarum\Search\SearchResults;
use Flarumite\PostDecontaminator\PostDecontaminatorRepository;

class PageSearcher
{
    use ApplySearchParametersTrait;

    /**
     * @var GambitManager
     */
    protected $gambits;

    /**
     * @var PostDecontaminatorRepository
     */
    protected $pages;

    /**
     * @param GambitManager                $gambits
     * @param PostDecontaminatorRepository $pages
     */
    public function __construct(GambitManager $gambits, PostDecontaminatorRepository $pages)
    {
        $this->gambits = $gambits;
        $this->pages = $pages;
    }

    /**
     * @param SearchCriteria $criteria
     *
     * @return SearchResults
     */
    public function search(SearchCriteria $criteria)
    {
        $actor = $criteria->actor;

        $query = $this->pages->query();

        if ($actor !== null && !$actor->isAdmin()) {
            $query->whereIsHidden(0);
        }

        $search = new PageSearch($query->getQuery(), $actor);

        $this->gambits->apply($search, $criteria->query);
        $pages = $query->get();

        return new SearchResults($pages, false);
    }
}
