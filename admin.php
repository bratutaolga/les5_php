<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bratuta blog - Add new post</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/blog-post.css" rel="stylesheet">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">My Blog</a>
            </div>
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <div class="well">
                    <h4>Add new post</h4>
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Title:</label>
                            <input class="form-control" type="text" name="title">
                            <br/>
                            <label>By:</label>
                            <input class="form-control" type="text" name="author">
                            <br/>
                            <label>Category:</label>
                            <select class="form-control" name="category">
                                <option disabled>Choose category</option>
                                <option value="Nature">Nature</option>
                                <option value="Politics">Politics</option>
                                <option value="IT">IT</option>
                                <option value="Relations">Relations</option>
                                <option value="Other">Other</option>
                            </select>
                            <br/>
                            <label>Content:</label>
                            <textarea class="form-control" rows="3" name="content"></textarea><br/>
                            <input type="file"  name="picture"/>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <hr>
        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; 2017</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>
    </div>
    <!-- /.container -->

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>

<?php

try {
    $dsn = "sqlite:blog.sqlite";
    $db = new PDO($dsn);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //$sql = "CREATE TABLE post (
              //id integer PRIMARY KEY AUTOINCREMENT,
              //title TEXT NOT NULL,
              //author TEXT NOT NULL,
              //category TEXT NOT NULL,
              //content TEXT NOT NULL,
              //picture TEXT NOT NULL,
              //published_date TEXT NOT NULL
            //)";
    //$db->exec($sql);
    //$sql = "CREATE TABLE comment (
              //id integer PRIMARY KEY AUTOINCREMENT,
             // author TEXT NOT NULL,
              //content TEXT NOT NULL,
             // published_date TEXT NOT NULL,
             // id_post integer NOT NULL
          //  )";
   // $db->exec($sql);
    $dir = "uploads";
    if (!is_dir($dir)) {
        mkdir($dir);
    }
    $db->beginTransaction(); 

    if (!empty($_POST)) {
        if (isset($_POST['title']) && isset($_POST['content'])&& isset($_POST['author'])&& isset($_POST['category'])) {
            extract($_POST);
            $published_date = date('Y-m-d H:i:s');
            $pic = "$dir/1.jpg";
            if (!empty($_FILES)) {
                if (isset($_FILES['picture'])) {
                    if ($_FILES['picture']['error'] == UPLOAD_ERR_OK) {
                        $src = $_FILES['picture']['tmp_name'];
                        $fname = $_FILES['picture']['name'];
                        $pic = "$dir/$fname";
                        move_uploaded_file($src, $pic);
                    }
                }
            }
            $db->exec("INSERT INTO post(title, author, category, content, picture, published_date) 
                        values ('$title', '$author', '$category', '$content', '$pic', '$published_date')");
        }
    }
    $db->commit();
}
catch (PDOException $ex) {
    $db->rollBack();
    echo "<p style='color: red'>" . $ex->getMessage() . "</p>";
}
