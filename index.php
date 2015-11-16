<?php


$servername = "localhost";
$username = "root";
$password = "priyanshu@sql";
$begin = 0;
$end = 21;
$product = "";
$pid = "";
$asins = array();
$titles = array();
$scores = array();
$prices = array();
$imgs = array();

if (!empty($_GET["product"])) {
    $product = $_GET["product"];
    if ($_GET["pid"]){
        $pid = $_GET["pid"];
    }
    else{
        $pid = 0;
    }
    $end2 = $end;
    $end = $end*$_GET["pid"];
    $begin = $end - $end2;   
}
else {
    $product = "Cell Phones";
    $pid = 0;
        # code...
}    
if (!$conn = mysql_connect($servername,$username,$password)){
    die("Connection Failed: ");
}

if (!mysql_select_db('btp',$conn)) {
    echo 'Could not select database';
    exit;
}

$sql = 'select distinct asin from all_tmp where category="'.$product.'" and asin in (select distinct asin from final_products_scored where title != "" and price is not NULL and price != 0) limit '.$begin.','.$end;
// echo $sql;
$result = mysql_query($sql,$conn);

if (!$result){
    die("Result did not come: ");
}
while ($row = mysql_fetch_assoc($result)) {
    
    // $asins[] = $row['asin'];
    $asins[] = $row['asin'];
    // echo '**'.$row['asin'].'**<br/>';
    $query = 'select title,price from final_products_scored where asin="'.$row['asin'].'"';
    // echo $query;
    $result2 = mysql_query($query,$conn);
    $row2 = mysql_fetch_assoc($result2);
    // echo '**'.$row2['title'].'**'.$row['asin'].'<br/>';
    $titles[] = $row2['title'];
        // echo $row['asin'].'<br>';

    $price = $row2['price'];
    if($price == "" or $price==0){
        $prices[] = "Not Available";
    }
    else {
        $prices[] = '$'.$price;
    }

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

    <title>Shopping Arena</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/shop-homepage.css" rel="stylesheet">

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
                <p class="lead">Product</p>
                <div class="list-group">
                    <a href="index.php?product=Cell Phones&pid=<?php echo $begin+1;?>" class="list-group-item">Cell Phones</a>
                    <a href="index.php?product=Headsets&pid=<?php echo $begin+1;?>" class="list-group-item">Headsets</a>
                    <a href="index.php?product=Laptops&pid=<?php echo $begin+1;?>" class="list-group-item">Laptops</a>
                    <a href="index.php?product=Digital Cameras&pid=<?php echo $begin+1;?>" class="list-group-item">Digital Cameras</a>

                </div>
            </div>

            <div class="col-md-9">

                <div class="row carousel-holder">

                    <div class="col-md-12">
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img class="slide-image" src="http://placehold.it/800x300" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="http://placehold.it/800x300" alt="">
                                </div>
                                <div class="item">
                                    <img class="slide-image" src="http://placehold.it/800x300" alt="">
                                </div>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <?php
                        for ($i = $begin; $i < $end; $i = $i + 1)
                        {
                            echo'<div class="col-sm-4 col-lg-4 col-md-4">
                                <a href ="product.php?asin='.$asins[$i-$begin].'">
                                    <div class="thumbnail">

                                        <img src="images/sample_product.jpg" alt="">
                                        <div class="caption">
                                            <h4><a href="product.php?asin='.$asins[$i-$begin].'">'.$titles[$i-$begin].'</a></h4>
                                            <h4 class="pull-left">'.$prices[$i-$begin].'</h4>
                                        </div>
                                        <div class="ratings">
                                            <p class="pull-right">15 reviews</p>
                                            <p>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon glyphicon-star"></span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </div>';
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
