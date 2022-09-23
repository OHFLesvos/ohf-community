<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreComment;
use App\Http\Resources\Comment as CommentResource;
use App\Models\Comment;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunityVolunteerCommentsController extends Controller
{
    public function index(CommunityVolunteer $cmtyvol, Request $request): JsonResource
    {
        $this->authorize('update', $cmtyvol);
        $this->authorize('viewAny', Comment::class);

        return CommentResource::collection($cmtyvol->comments()
            ->orderBy('created_at', 'asc')
            ->get())
            ->additional([
                'meta' => [
                    'can_create' => $request->user()->can('create', Comment::class),
                ],
            ]);
    }

    public function store(CommunityVolunteer $cmtyvol, StoreComment $request): JsonResource
    {
        $this->authorize('update', $cmtyvol);
        $this->authorize('create', Comment::class);

        $comment = new Comment();
        $comment->fill($request->validated());
        $comment->setCurrentUser();
        $cmtyvol->addComment($comment);

        return (new CommentResource($comment))
            ->additional([
                'message' => __('Comment added.'),
            ]);
    }
}
