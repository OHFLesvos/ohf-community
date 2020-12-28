<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreComment;
use App\Http\Resources\Comment as CommentResource;
use App\Models\Comment;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use Illuminate\Http\Request;

class CommunityVolunteerCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CommunityVolunteer $cmtyvol, Request $request)
    {
        $this->authorize('update', $cmtyvol);
        $this->authorize('viewAny', Comment::class);

        return CommentResource::collection($cmtyvol->comments()
            ->orderBy('created_at', 'asc')
            ->get())
            ->additional([
                'meta' => [
                    'can_create' => $request->user()->can('create', Comment::class)
                ]
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommunityVolunteer $cmtyvol, StoreComment $request)
    {
        $this->authorize('update', $cmtyvol);
        $this->authorize('create', Comment::class);

        $comment = new Comment();
        $comment->setCurrentUser();
        $comment->content = $request->content;
        $cmtyvol->addComment($comment);

        return (new CommentResource($comment))
            ->additional([
                'message' => __('app.comment_added'),
            ]);
    }
}
