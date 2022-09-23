<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreComment;
use App\Http\Resources\Comment as CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentsController extends Controller
{
    public function show(Comment $comment): JsonResource
    {
        $this->authorize('view', $comment);

        return new CommentResource($comment);
    }

    public function update(StoreComment $request, Comment $comment): JsonResource
    {
        $this->authorize('update', $comment);

        $comment->content = $request->content;
        $comment->save();

        return (new CommentResource($comment))
            ->additional([
                'message' => __('Comment updated.'),
            ]);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()
            ->json([
                'message' => __('Comment deleted.'),
            ]);
    }
}
