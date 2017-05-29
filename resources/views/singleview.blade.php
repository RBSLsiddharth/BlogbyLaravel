@include('Mainview')
        <!DOCTYPE html>
<html lang="en">
<head>
    <title>Todo List</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid blue;
        border-right: 16px solid green;
        border-bottom: 16px solid red;
        border-left: 16px solid pink;
        width: 120px;
        height: 120px;
        position: absolute;
        top: 50%;
        left: 42%;
        display: none;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<body>

<div class="container" id="container">
    <div class="row">
        <div class="col-sm-10">

            @yield('navbar')
            {{--  @foreach($result as $resultshowing)
                  <p> the result is {{  $resultshowing->Blogtext}} </p>
              @endforeach--}}


            @foreach($result as $task)
                <li class="list-group-item">
                    BLOG created : <br>{{ $task->Blogtext}}
                    <a class="btn btn-primary btn-xs" data-toggle="modal" name="action" value="openit"
                       href='/blog/{{$task->id}}/{{$task->userwhocreated}}'> Open it-> </a>
                </li>
                Generated by {{$task->userwhocreated}}
                <?php $variable = $task->id?>
                <br/>
                <br/>
            @endforeach
        </div>
        <div class="col-sm-2">
            @foreach($listofusers as $task)
                <div class="well">
                    <a> {{$task->name}}</a>
                    <a href='/user/{{$task->email}}'><br>O</a>
                </div>
            @endforeach
        </div>

        <div class="loader"></div>
        <div class="container">

            <div class="row">
                <div class="col-sm-10">
                    @if(strcmp($commentstatus,'empty'))
                        <h2 align="center">Comments</h2>
                        <br/><br/>
                        <ul class="list-group">
                            <form>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="comment"
                                           placeholder="Comments please" name="commentdata" required>
                                </div>

                                <a class="btn btn-default" name="Blogid" value="{{$variable}}"
                                   onclick="return postTheComment()">Submit</a>
                            </form>
                            <br/><br/>
                            @yield('content')
                        </ul>

                        <ul id="commentid">
                            @foreach($comments as $task)
                                <li class="list-group-item">
                                    {{$task->Commentdoneby}} says: <br>{{$task->Comment}}
                                </li>

                            @endforeach
                        </ul>

                    @else
                        <div class="row">
                            <div class="col-sm-10">
                                <h4>Be the first one to comment for the blog</h4>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>


    function postTheComment() {
        if ($('input[name="commentdata"]').val()!='') {
            $('.validation').hide();
            var _token = $('input[name="_token"]').val();
            var commentdata = $('input[name="commentdata"]').val();
            var Blogid = {{$variable}}
     {{--  $.post('{{route('addcomment')}}', {
                _token: _token,
                commentdata: commentdata,
                Blogid: Blogid
            },
            function(data) {
              aler("hi i am here");
                alert(data);
            });--}}

        $(document).ajaxStart(function () {
                $('.loader').show();
            }).ajaxStop(function () {
                $('.loader').hide();
            });


            $.ajax({
                type: 'POST',
                url: " {{ route('addcomment') }}",
                data: {
                    _token: _token,
                    commentdata: commentdata,
                    Blogid: Blogid
                },
                success: function (data) {
                    $('#commentid').append('<li class="list-group-item">' + data.commentDoneBy + "says as:" + '<br/>' + data.commentDataFromController + '</li>');
                },
                error: function (data) {
                    console.log(data.status1)

                    alert("error");
                }

            });
        }else{
            $('#comment').parent().after("<div class='validation' style='display: block'>Please fill this field</div>");
        }

        }
</script>
</body>
</html>
