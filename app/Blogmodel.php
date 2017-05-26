<?php

namespace App;


use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class Blogmodel extends Model
{
    private $Blogid;
    private $name;
    private $emailvariable;
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
        $result =false;
        if (!$data == '') {
            DB::table('commenttable')->insert(
                ['Blogid' => $Blogid, 'Comment' => $data,'Commentdoneby' =>Auth::user()->email]);

         if($this->notifyBloggerWhileCommenting($data)) {

             $this->notifyOthersWhileCommenting($data);
              $result = true;

         }

        }
        else {
            $result = false;
        }
        return array($result);
    }


    public function notifyOthersWhileCommenting($data){
        $users = DB::table('commenttable')->select('Commentdoneby')->where ('Blogid',$this->Blogid)->distinct()->get();
            foreach ($users as $task){
                if(strcmp($task->Commentdoneby,Auth::user()->email)){
                    $this->emailvariable=$task->Commentdoneby;
                    \Mail::raw($data, function ($message) {
                        $message->to($this->emailvariable,'frommyside' )->subject('Someone has also commented on the same blog as you');
                    });
                }
            }

    }



    public function notifyBloggerWhileCommenting($data)
    {
        try {
            \Mail::raw($data, function ($message) {
                $Blogger = DB::table('Blog')->select('userwhocreated')->where('id', $this->Blogid)->first();

                foreach ($Blogger as $task) {
                    $Blogger = $task;
                }
                foreach ($this->listofuser() as $name) {
                    if (!strcmp($name->email, $Blogger)) {
                        $this->name = $name->name;
                    }
                }
                $message->to($Blogger, $this->name)->subject('Comments from The HappyBlogging');
            });
            return true;

        } catch(Exception $e){
           return false;
        }
        }



    public function opencomment($id){
        return DB::table('commenttable')->where('Blogid',$id)->get();

    }

    //to show the list of all blogs
    public function showtheresult()
    {
        $result = DB::table('Blog')->get();
        return $result;
    }

    //to open a particular blog
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