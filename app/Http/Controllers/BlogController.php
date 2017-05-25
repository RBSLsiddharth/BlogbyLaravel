<?php


namespace App\Http\Controllers;

use App\Blogmodel;
use App\Http\Controllers\Auth\LoginController;
use App\TodoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class BlogController extends Controller
{
    public $Blogmodalobject;
    private $listofusers;

    public function __construct()
    {
        $this->middleware('auth');
        $this->Blogmodalobject = new Blogmodel();
        $this->listofusers = $this->Blogmodalobject->listofuser();

    }


    public function handleReq(Request $request)
    {

        if ($request->input('action') . equalTo('add')) {

            $result = $this->Blogmodalobject->addinDatabase($request->get('data'));
            if ($result == false) {
                return view('base');
            } else {
                return view('successfullyupload');
            }
        }
    }

    function toshowtheblog()
    {
        $result = $this->Blogmodalobject->showtheresult();
        return view('toshowblog', ['result' => $result, 'listofusers' => $this->listofusers]);
    }


//to display the further specific blog
    function openit($id, $email)
    {
        $Blogmodalobject = new Blogmodel();
        $result = $Blogmodalobject->openit($id);
        $comments = $Blogmodalobject->opencomment($id);
        return view('singleview', ['result' => $result, 'listofusers' => $this->listofusers,'comments'=>$comments,'commentstatus'=>'full']);
    }

    function openasperuser($email)
    {
        $result = $this->Blogmodalobject->openasperemail($email);
      return view('singleview', ['result' => $result, 'listofusers' => $this->listofusers,'commentstatus'=>'empty','email'=>$email]);
    }


    function comments(Request $request){

        $result = $this->Blogmodalobject->addthecomment($request->get('commentdata'),$request->get('Blogid'));
        if($result == false){

            return view('base');

        }else{
            return view('successfullyupload');
        }
    }
}