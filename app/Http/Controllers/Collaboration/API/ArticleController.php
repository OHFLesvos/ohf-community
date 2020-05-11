<?php

namespace App\Http\Controllers\Collaboration\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collaboration\StoreArticle;
use App\Http\Resources\Collaboration\WikiArticle as WikiArticleResource;
use App\Models\Collaboration\WikiArticle;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(WikiArticle::class, 'article');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageSize = 50;

        return WikiArticleResource::collection(WikiArticle::query()
            ->orderBy('title')
            ->paginate($pageSize));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreArticle $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticle $request)
    {
        $article = new WikiArticle();
        $article->fill($request->all());
        $article->save();

        // TODO tags
        // $article->setTagsFromJson($request->tags);

        return (new WikiArticleResource($article))
            ->response(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collaboration\WikiArticle  $article
     * @return \Illuminate\Http\Response
     */
    public function show(WikiArticle $article)
    {
        return new WikiArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreArticle $request
     * @param  \App\Models\Collaboration\WikiArticle  $article
     * @return \Illuminate\Http\Response
     */
    public function update(StoreArticle $request, WikiArticle $article)
    {
        $article->fill($request->all());
        $article->save();

        // TODO tags
        // $article->setTagsFromJson($request->tags);

        return new WikiArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collaboration\WikiArticle  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(WikiArticle $article)
    {
        $article->delete();

        return response()
            ->json(null);
    }
}
