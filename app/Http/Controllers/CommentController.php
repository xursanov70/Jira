<?php

namespace App\Http\Controllers;

use App\Http\Interfaces\CommentInterface;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function __construct(protected CommentInterface $commentInterface)
    {
    }

    public   function createComment(CommentRequest $request)
    {
        return $this->commentInterface->createComment($request);
    }

    public    function updateComment(Request $request, $comment_id)
    {
        return $this->commentInterface->updateComment($request, $comment_id);
    }

    public   function getComments()
    {
        return $this->commentInterface->getComments();
    }

    public  function getMyComments()
    {
        return $this->commentInterface->getMyComments();
    }
}
