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
<body>

<div class="container">
    <h2 align="center">Welcome to the Daily Blog</h2>
    <br/><br/>
    <ul class="list-group">

        <form method="get" action="\add">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <input type="text" class="form-control" id="todo" name="data"
                       placeholder="AS YOU CAN MENTION YOUR BLOG HERE" required>
            </div>

            <button type="submit" class="btn btn-default" name="action" value="add">Submit</button>


            <a href="\show" type="button" class="btn btn-default" name="action" value="show">Show</a>

        </form>
        <br/><br/>

        @yield('content')
        <?php // echo $content; ?>

    </ul>
</div>

</body>
</html>
