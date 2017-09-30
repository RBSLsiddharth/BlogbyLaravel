<?php

namespace App;


use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Mockery\Exception;

class Blogmodel extends Model
{


    public function addBlogInDatabase($data)
    {

        if (!$data == '') {
            /*DB::table('Blog')->insert(
                ['Blogtext' => $data, 'userwhocreated' => Auth::user()->email]);*/
            $blog = new Blog;
            $blog->Blogtext = $data;
            $blog->userwhocreated = Auth::user()->email;
            $blog->save();
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }


    //to show the list of all blogs
    public function toShowAllTheBlogs()
    {
        /*$result = DB::table('Blog')->get();*/
        $result = Blog::all();


        return $result;

    }

    public function bloggerWhoCreatedBlog($Blogid){/*
        $Blogger = DB::table('Blog')->select('userwhocreated')->where('id', $Blogid)->first();*/
        $blogger = Blog::find($Blogid);
        return $blogger->userwhocreated;
    }


    //to open a particular blog
    public function openTheParticularBlog($id)
    {

        return Blog::where('id',$id)->get();

    }

    //to display according to emailid
    public function   openAsPerEmail($email){
        return Blog::where('userwhocreated', $email)->get();

    }

    public function listOfUser()
    {
        return DB::table('users')->select('name', 'email')->get();

    }



}