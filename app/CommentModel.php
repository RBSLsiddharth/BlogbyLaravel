<?php
/**
 * Created by PhpStorm.
 * User: siddharh
 * Date: 30/5/17
 * Time: 6:05 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use Symfony\Component\Yaml\Tests\B;

class CommentModel extends Model
{
    private $Blogid;
    private $name;
    private $Blogger;
    private $emailvariable;

    public function addthecomment($data,$Blogid){
        $this->Blogid = $Blogid;
        $result =false;
        $commentdata = $data;
        $commentdoneby = Auth::user()->email;
        if (!$data == '') {

            $commentid= DB::table('commenttable')->insertGetId(
                ['Blogid' => $Blogid, 'Comment' => $data,'Commentdoneby' =>Auth::user()->email]);

            if($this->notifyBloggerWhileCommenting($data,$Blogid)) {

                $this->notifyOthersWhileCommenting($data);
                $result = true;

            }

        }
        else {
            $result = false;

        }
        return array($result,$commentdata,$commentdoneby,$commentid);
    }


    public function notifyOthersWhileCommenting($data){
        $users = Commenttable::where ('Blogid',$this->Blogid)
            ->select('Commentdoneby')
            ->distinct()
            ->get();

        foreach ($users as $task){
            if(strcmp($task->Commentdoneby,Auth::user()->email)){
                $this->emailvariable=$task->Commentdoneby;
                \Mail::raw($data, function ($message) {
                    $message->to($this->emailvariable,'frommyside' )
                        ->subject('Someone has also commented on the same blog as you');
                });
            }
        }

    }

    public function notifyBloggerWhileCommenting($data,$Blogid)
    {
        $this->Blogger = (new Blogmodel())->bloggerWhoCreatedBlog($Blogid);


        //to check and the mail should not be sent to the one who is the owner of the blog and is commenting on its own blog; otherwise send the mail to others

        try {
            if(strcmp($this->Blogger,Auth::user()->email)) {
                \Mail::raw($data, function ($message)  {
                    foreach ((new Blogmodel())->listOfUser() as $name) {
                        //this is for fetching out the name of the blogger
                        if (!strcmp($name->email, $this->Blogger)) {
                            $this->name = $name->name;
                            break;
                         }
                    }
                    $message->to($this->Blogger, $this->name)->subject('Comments from The HappyBlogging');
                });

            }
            return true;
        } catch(Exception $e){
            return false;
        }
    }
    public function opencomment($id){
        return Commenttable::where('Blogid',$id)->get();

    }

    public function deletecomment($commentid){
        return Commenttable::where('commentid',$commentid)->delete();
    }

    public function updatecomment($commentid,$commentdata){
        return Commenttable::where('commentid',$commentid)
            ->update(['Comment'=>$commentdata]);//will do some updation over the given commentid
    }
}