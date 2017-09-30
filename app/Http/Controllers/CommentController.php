<?php
namespace App\Http\Controllers;

use App\Blogmodel;
use App\CommentModel;
use App\TodoModel;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentControllerObject;
    private $listofusers;
    public function __construct()
    {
        $this->middleware('auth');
        $this->commentControllerObject = new CommentModel();
        $this->blogmodelObject = new Blogmodel();
        $this->listofusers =$this->blogmodelObject->listofuser();
    }

   //to add the comments
    function addComments(Request $request)
    {
        $result = $this->commentControllerObject->addthecomment($request->get('commentdata'), $request->get('Blogid'));
        if ($result[0] == false) {
            return response()->json(['status1' => false]);
        } else {
            return response()->json(['status1' => true, 'commentDataFromController' => $result[1], 'commentDoneBy' => $result[2], 'commentid' => $result[3]]);
        }


    }


//to delete the current deletecomment
    function deleteComments(Request $request)
    {

        if ($this->commentControllerObject->deletecomment($request->get('commentid'))) {
            return response()->json(['status1' => true]);
        } else {
            return response()->json(['status1' => false]);
        }
    }


//updating the comment
    function updateComments(Request $request)
    {
        $this->commentControllerObject->updatecomment($request->get('commentid'), $request->get('commentdata'));
        return response()->json(['status1' => true, 'updated' => 'updated']);
    }


}