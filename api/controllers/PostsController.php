<?php

declare(strict_types=1);

namespace Gewaer\Api\Controllers;

use Canvas\Api\Controllers\BaseController as CanvasBaseController;
use Gewaer\Models\Posts;
use Gewaer\Models\Status;
use Gewaer\Dto\Posts as PostDto;
use Gewaer\Mapper\PostMapper;
use Canvas\Http\Request;
use Phalcon\Mvc\ModelInterface;

/**
 * Class BaseController.
 *
 * @package Gewaer\Api\Controllers
 *
 */
class PostsController extends CanvasBaseController
{
    /*
       * fields we accept to create
       *
       * @var array
       */
    protected $createFields = [
        'sites_id', 'post_types_id', 'category_id', 'title', 'slug', 'summary', 'content', 'media_url', 'likes_count', 'post_parent_id',
        'shares_count', 'comment_count', 'status', 'is_published', 'comment_status', 'featured', 'weight', 'premium', 'published_at','is_live'
    ];

    /*
     * fields we accept to create
     *
     * @var array
     */
    protected $updateFields = [
        'sites_id', 'post_types_id', 'category_id', 'title', 'slug', 'summary', 'content', 'media_url', 'likes_count', 'post_parent_id',
        'shares_count', 'comment_count', 'status', 'is_published', 'comment_status', 'featured', 'weight', 'premium', 'published_at','is_live'
    ];

    /**
     * set objects.
     *
     * @return void
     */
    public function onConstruct()
    {
        $this->model = new Posts();
        $this->model->users_id = $this->userData->getId();
        $this->model->companies_id = $this->userData->currentCompanyId();
        $this->model->sites_id = $this->site->getId();

        $this->additionalSearchFields = [
            ['is_deleted', ':', '0'],
            ['companies_id', ':', $this->userData->currentCompanyId()],
        ];
    }

    /**
     * Format output.
     *
     * @param mixed $results
     * @return void
     */
    protected function processOutput($results)
    {
        //add a mapper
        $this->dtoConfig->registerMapping(Posts::class, PostDto::class)
            ->useCustomMapper(new PostMapper());

        if (is_array($results) && isset($results['data'])) {
            $results['data'] = $this->mapper->mapMultiple($results['data'], PostDto::class);
            return  $results;
        }

        return is_iterable($results) ?
            $this->mapper->mapMultiple($results, PostDto::class)
            : $this->mapper->map($results, PostDto::class);
    }
}
