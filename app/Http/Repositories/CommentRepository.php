<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\CommentInterface;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentRepository implements CommentInterface{

    public function createComment(CommentRequest $request)
    {
        $user = User::select('*')->where('username', $request->partner_username)->first();

        if(!$user){
            return response()->json(["message" => "User mavjud emas!"]);
        }
        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'partner_username' => $request->partner_username,
            'comment' => $request->comment,
        ]);
        return response()->json(["message" => "Fikr qoldirildi!", "data" => $comment]);
    }

    public function updateComment(Request $request, $comment_id)
    {
        $comment = Comment::find($comment_id);
        if (!$comment){
            return response()->json(["message" => "Comment mavjud emas!"]);
        }
        $comment->update([
            'partner_username' => $request->partner_username,
            'comment' => $request->comment,
        ]);
        return response()->json(["message" => "Comment updated successfully!"]);
    }


    public function getComments()
    {
        $get = Comment::select('comments.id as comment_id', 'partner_username', 'username', 'comment')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('partner_username', Auth::user()->username)
            ->orderBy('comments.id', 'asc')
            ->paginate(15);
        return CommentResource::collection($get);
    }

    public function getMyComments()
    {
        $get = Comment::select('comments.id as comment_id', 'partner_username', 'username', 'comment')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('users.id', Auth::user()->id)
            ->orderBy('comments.id', 'asc')
            ->paginate(15);
            return CommentResource::collection($get);
    }
}