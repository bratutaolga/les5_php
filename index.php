<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bratuta blog - Home</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/blog-home.css" rel="stylesheet">

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
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="admin.php">Add Post</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">
        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-8">
                <h1 class="page-header">
                    Welcome to Olga blog
                    <small>Open your mind:)</small>
                </h1>
                <!-- Blog Post -->
                <?php
                class Row{}
                $dsn = "sqlite:blog.sqlite";
                $db = new PDO($dsn);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                if(empty($_GET)) {
                    $sql = "SELECT * FROM post ORDER BY published_date DESC";
                    $pst = $db->prepare($sql);
                    $pst->execute();
                } else {
                    if (isset($_GET['filter']))
                    {
                        $filter = "%{$_GET['filter']}%";
                        $sql = "SELECT * FROM post WHERE title LIKE :filter ORDER BY published_date DESC";
                        $pst = $db->prepare($sql); 
                        $pst->bindParam(':filter', $filter);
                        $pst->execute();
                    }
                    if (isset($_GET['category']))
                    {
                        $filter = "{$_GET['category']}";
                        $sql = "SELECT * FROM post WHERE category LIKE :filter ORDER BY published_date DESC";
                        $pst = $db->prepare($sql);
                        $pst->bindParam(':filter', $filter);
                        $pst->execute();
                    }
                }

                foreach ($pst->fetchAll(PDO::FETCH_CLASS, 'Row') as $row){
                    echo "<h2>";
                    echo "<a href=\"post.php?postid={$row->id}\">{$row->title}</a>";
                    echo "</h2>";
                    echo "<p class='lead'>";
                    echo "by {$row->author}";
                    echo "</p>";
                    echo "<h5>category: {$row->category}</h5>";
                    echo "<p><span class='glyphicon glyphicon-time'></span> Posted on {$row->published_date}</p>";
                    echo "<hr>";
                    echo "<img class='img-responsive' src='{$row->picture}' alt=''>";
                    echo "<hr>";
                    echo "<p>{$row->content}</p>";
                    echo "<a class='btn btn-primary' href=\"post.php?postid={$row->id}\">Read More <span class='glyphicon glyphicon-chevron-right'></span></a>";
                    echo "<hr>";
                }
                ?>
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Blog Search Well -->
                <form method="get">
                <div class="well">
                    <h4>Blog Search</h4>
                    <div class="input-group">
                        <input type="search" class="form-control" name ="filter">
                        <span class="input-group-btn">
                           <button class="btn btn-default" type="submit">
                               <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                    </div>
                    <!-- /.input-group -->
                </div>
                </form>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a  href="index.php?category=Nature">Nature</a>
                                </li>
                                <li><a href="index.php?category=Politics">Politics</a>
                                </li>
                                <li><a href="index.php?category=IT">IT</a>
                                </li>
                                <li><a href="index.php?category=Relations">Relations</a>
                                </li>
                                <li><a href="index.php?category=Other">Other</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.row -->
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
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </footer>
    </div>
    <!-- /.container -->

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
