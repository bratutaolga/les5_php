<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bratuta blog - Post</title>

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
                <!-- Blog Post -->
                <?php
                class Row{}
                $dsn = "sqlite:blog.sqlite";
                $db = new PDO($dsn);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                if(!empty($_GET)) {
                    $filter = "{$_GET['postid']}";
                }
                else
                {
                    $tmp = "SELECT max(id) FROM post";
					$result= $db->query($tmp);
                    while($item = $result->fetch()) {
                    $filter =  $item['max(id)'];
                    }
                }
                $sql = "SELECT * FROM post WHERE id = :filter";
                $pst = $db->prepare($sql);
                $pst->bindParam(':filter', $filter);
                $pst->execute();
                $sql_com = "SELECT * FROM comment WHERE id_post = :filter order by published_date DESC ";
                $pst_com = $db->prepare($sql_com);
                $pst_com->bindParam(':filter', $filter);
                $pst_com->execute();

                foreach ($pst->fetchAll(PDO::FETCH_CLASS, 'Row') as $row){
                echo "<h1> {$row->title} </h1>";
                echo "<p class='lead'>";
                echo "by {$row->author}";
                echo "</p>";
                echo "<h5>category: {$row->category}</h5>";
                echo "<hr>";
                echo "<p><span class='glyphicon glyphicon-time'></span> Posted on {$row->published_date}</p>";
                echo "<hr>";
                echo "<img class='img-responsive' src='{$row->picture}' alt=''>";
                echo "<hr>";
                echo "<p class='lead'>{$row->content}</p>";
                echo "<hr>";
                }?>
                <!-- Blog Comments -->

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form method="post">
                        <div class="form-group">
                            <textarea class="form-control" rows="3" name ="content"></textarea>
                            <label>Author:</label>
                            <input class="form-control" type="text" name="author">
                            <br/>
                        </div>
                        <button type="submit" class="btn btn-primary">Add comment</button>
                    </form>
                </div>

                <hr>
                <?php
                foreach ($pst_com->fetchAll(PDO::FETCH_CLASS, 'Row') as $row){
                    echo "<div class='media'>";
                    echo "<a class='pull-left' href='#'>";
                    echo "<img class='media-object' src='uploads/user.jpg' alt=''>";
                    echo "</a>";
                    echo "<div class='media-body'>";
                    echo "<h4 class='media-heading'>{$row->author}";
                    echo "<small>{$row->published_date}</small>";
                    echo "</h4>";
                    echo "{$row->content}";
                    echo "</div>";
                    echo "</div>";
                }

                try {
                    $dsn = "sqlite:blog.sqlite";
                    $db = new PDO($dsn);
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $db->beginTransaction();

                    if (!empty($_POST)) {
                        if (isset($_POST['author']) && isset($_POST['content'])) {
                            extract($_POST);
                            $post_id = $_GET['postid'];
                            $published_date = date('Y-m-d H:i:s');
                            $db->exec("INSERT INTO comment(author, content, published_date, id_post) 
                        values ('$author', '$content', '$published_date', '$post_id')");
                        }
                    }
                    $db->commit();
                }
                catch (PDOException $ex) {
                    $db->rollBack();
                    echo "<p style='color: red'>" . $ex->getMessage() . "</p>";
                }
                ?>
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
