<?php

declare(strict_types=1);

namespace Gewaer\Api\Controllers;

use Canvas\Api\Controllers\BaseController as CanvasBaseController;
use Gewaer\Models\Posts;
use Gewaer\Dto\Posts as PostDto;
use Gewaer\Mapper\PostMapper;
use Phalcon\Http\Request;
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
        'shares_count', 'comment_count', 'status', 'is_published', 'comment_status', 'featured', 'weight', 'premium'
    ];

    /*
     * fields we accept to create
     *
     * @var array
     */
    protected $updateFields = [
        'sites_id', 'post_types_id', 'category_id', 'title', 'slug', 'summary', 'content', 'media_url', 'likes_count', 'post_parent_id',
        'shares_count', 'comment_count', 'status', 'is_published', 'comment_status', 'featured', 'weight', 'premium'
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

        return $results instanceof \Phalcon\Mvc\Model\Resultset\Simple ?
            $this->mapper->mapMultiple(iterator_to_array($results), PostDto::class)
            : $this->mapper->map($results, PostDto::class);
    }

    /**
     * Process the update request and return the object.
     *
     * @param Request $request
     * @param ModelInterface $record
     * @throws Exception
     * @return ModelInterface
     */
    protected function processEdit(Request $request, ModelInterface $record): ModelInterface
    {
        //process the input
        $request = $this->processInput($request->getPutData());

        $record->updateOrFail($request, $this->updateFields);

        //en base a la lista de tags lo agrego

        return $record;
    }

    /**
     * Process the create request and trecurd the boject.
     *
     * @return ModelInterface
     * @throws Exception
     */
    protected function processCreate(Request $request): ModelInterface
    {
        //process the input
        $request = $this->processInput($request->getPostData());

        $this->model->saveOrFail($request, $this->createFields);

        return $this->model;
    }
}
