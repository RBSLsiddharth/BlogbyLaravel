<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Blogmodel extends Model
{
    private $Blogid;

    public function addinDatabase($data)
    {

        if (!$data == '') {
            DB::table('Blog')->insert(
                ['Blogtext' => $data, 'userwhocreated' => Auth::user()->email]);
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function addthecomment($data,$Blogid){
        $this->Blogid = $Blogid;
        if (!$data == '') {
            DB::table('commenttable')->insert(
                ['Blogid' => $Blogid, 'Comment' => $data,'Commentdoneby' =>Auth::user()->email]);

            \Mail::raw($data,function($message)
            {
                $Blogger = DB::table('Blog')->select('userwhocreated')->where('id',$this->Blogid)->first();
               foreach ($Blogger as $task){
                   $Blogger= $task;
               }
                $message->to($Blogger, 'Siddharth')->subject('Comments from The HappyBlogging');

            });

            $result = true;

        } else {
            $result = false;
        }
        return $result;
    }

    public function opencomment($id){
        return DB::table('commenttable')->where('Blogid',$id)->get();

    }
    public function showtheresult()
    {
        $result = DB::table('Blog')->get();
        return $result;
    }

    public function openit($id)
    {

        return DB::table('Blog')->where('id', $id)->get();

    }

    //to display according to emailid
    public function   openasperemail($email)
    {

        return DB::table('Blog')->where('userwhocreated', $email)->get();

    }

    public function listofuser()
    {
        return DB::table('users')->select('name', 'email')->get();
    }

}