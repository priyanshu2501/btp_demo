<?php

$asin = $_GET['asin'];
$servername = "localhost";
$username = "root";
$password = "priyanshu@sql";
$begin = 0;
$end = 20;
$product = "";
$rnames = array();
$reviews = array();
$pid = 0;
if (!empty($_GET['pid'])){
    $pid = $_GET['pid'];
}
$begin=$pid*$end;
if (!$conn = mysql_connect($servername,$username,$password)){
    die("Connection Failed: ");
}
if (!mysql_select_db('btp',$conn)) {
    echo 'Could not select database';
    exit;
}

$query = 'select count(*) as count from all_tmp where asin="'.$asin.'"';
$result = mysql_query($query,$conn);
$row = mysql_fetch_assoc($result);
$num_reviews = $row['count'];

$query = 'select rname,review from all_tmp where asin="'.$asin.'"  limit '.$begin.','.$end;
$result = mysql_query($query,$conn);
while($row = mysql_fetch_assoc($result)){
    // echo '..'.$row['rname'].'..<br>';
    $rnames[] = $row['rname'];
    $reviews[] = $row['review'];
}


$query = 'select title,price from final_products_scored where asin="'.$asin.'"  limit 1';
$result = mysql_query($query,$conn);
$row = mysql_fetch_assoc($result);
$title = $row['title'];
$price = $row['price'];
if($price == ""){
    $price = "Not Available";
}
else {
    $price = '$'.$price;
}
?>

<!DOCTYPE html> 
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Product Home Page</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/shop-item.css" rel="stylesheet">
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
                <a class="navbar-brand" href="index.php">IntelliShop</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <p class="lead">Product Reviews</p>
                <div class="list-group">
                    <a href="product.php?asin=<?php echo $asin;?>" class="list-group-item active">All Reviews</a>
                    <a href="feature.php?asin=<?php echo $asin;?>" class="list-group-item">Feature-based Analysis</a>
                </div>
            </div>

            <div class="col-md-9">

                <div class="thumbnail">
                    <div class="caption-full">
                        <h4><a href="#"><?php echo $title; ?></a>
                        </h4>
                        <img class = "pull-left" src="images/sample_product.jpg" style="width:50%" alt="">
                        <hr><hr>
                    <h4><a href="#">Product Description</a></h4>
                    <hr>
                    <div class="ratings">
                        <h4><?php echo 'Price : '.$price;?></h4>
                    </div>
                                    
                    </div>
                    <div class="ratings">
                        <p class="pull-right"><?php echo $num_reviews;?> reviews</p>
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                        </p>
                    </div>
                </div>

                <div class="well">
                    <?php
                        $count_reviews = sizeof($reviews);
                        for($i=0;$i<$count_reviews;$i = $i+1){
                            echo'<div class="row">
                                <div class="col-md-12">
                                <b>';
                            if ($rnames[$i] != ""){
                                echo $rnames[$i];
                            }
                            else {
                                echo "Anonymous";
                            }
                            echo '</b>
                                <p>'.$reviews[$i].'</p>
                                </div>
                                </div>
                            <hr>';
                        }
                    ?>
                </div>
                <div class="well" align="center">
                    <?php
                        $pages = ceil($num_reviews*1.0/$end);
                        if($pid != ($pages-1))
                            echo '<a href="product.php?asin='.$asin.'&pid='.($pid+1).'">Next Page</a>  ';
                        for ($j = 0;$j < $pages; $j = $j + 1){
                            echo '<a href="product.php?asin='.$asin.'&pid='.$j.'">'.($j+1).'</a> ';
                        }
                    ?>
                </div>
            </div>

        </div>

    </div>
    <!-- /.container -->

    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; IntelliShop 2015</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
