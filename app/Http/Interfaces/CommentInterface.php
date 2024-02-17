<?php

namespace App\Http\Interfaces;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

interface CommentInterface
{
    function createComment(CommentRequest $request);
    function updateComment(Request $request, $comment_id);
    function getComments();
    function getMyComments();
}