<?php


namespace App\Http\Controllers;

use App\Blogmodel;
use App\CommentModel;
use App\TodoModel;
use Illuminate\Http\Request;


class BlogController extends Controller
{
    protected $Blogmodalobject;
    private $listofusers;

    public function __construct()
    {
        $this->middleware('auth');
        $this->Blogmodalobject = new Blogmodel();
        $this->listofusers = $this->Blogmodalobject->listofuser();
    }

    function toShowTheBlog()
    {
        $result = $this->Blogmodalobject->toShowAllTheBlogs();
        return view('toshowblog', [
            'result' => $result,
            'listofusers' => $this->listofusers
        ]);
    }

    // index => filtering
    // show/view/display
    // add/edit
    // save
    // delete

//to handle the request
    public function handleReq(Request $request)
    {

        if ($request->input('action') . equalTo('add')) {

            $result = $this->Blogmodalobject->addBlogInDatabase($request->get('data'));
            if ($result == false) {
                return view('base');
            } else {
                return view('successfullyupload');
            }
        }
    }

//to display the further specific blog
    function openIt($id, $email)
    {
        $commentmodelobject = new CommentModel();
        $result = $this->Blogmodalobject->openTheParticularBlog($id);
        $comments = $commentmodelobject->opencomment($id);
        return view('singleview', ['result' => $result, 'listofusers' => $this->listofusers, 'comments' => $comments, 'commentstatus' => 'full']);
    }


    //to display as per user's email
    function openAsPerUserEmail($email)
    {
        $result = $this->Blogmodalobject->openAsPerEmail($email);
        return view('singleview', ['result' => $result, 'listofusers' => $this->listofusers, 'commentstatus' => 'empty', 'email' => $email]);
    }






}