<?php

$asin = $_GET['asin'];
$servername = "localhost";
$username = "root";
$password = "priyanshu@sql";
$product = "";
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

$features = array();
$scores = array();
$reviewsS = array();
$query = 'select feature,score from final_products_scored where asin="'.$asin.'"';
$result = mysql_query($query,$conn);
while($row = mysql_fetch_assoc($result)){
    $features[] = $row['feature'];
    $scores[] = round($row['score']);
}

for($i = 0;$i < sizeof($features);$i = $i+1){
    $feature = $features[$i];
    // echo $feature.'<br>';
    $pos_reviews = array();
    $neg_reviews = array();
    $query = 'select review from final_products_reviews where asin="'.$asin.'" and feature="'.$feature.'" and sentiment="pos"';
    // echo $query.'11<br/>';
    $result = mysql_query($query,$conn);
    while($row = mysql_fetch_assoc($result))
        $pos_reviews[] = $row['review'];
    $query = 'select review from final_products_reviews where asin="'.$asin.'" and feature="'.$feature.'" and sentiment="neg"';
    $result = mysql_query($query,$conn);
    while($row = mysql_fetch_assoc($result))
        $neg_reviews[] = $row['review'];
    $reviews[] = $pos_reviews;
    $reviews[] = $neg_reviews;
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
    <div class="container" >

        <div class="row">

            <div class="col-md-3">
                <p class="lead">Product Reviews</p>
                <div class="list-group">
                    <a href="product.php?asin=<?php echo $asin;?>" class="list-group-item">All Reviews</a>
                    <a href="feature.php?asin=<?php echo $asin;?>" class="list-group-item active">Feature-based Analysis</a>
                    <?php
                        echo '<a href="#" class="list-group-item feature-item" onclick="showSummary()" id="summary-btn" style="display:none;padding-left:30px">Summary</a>';
                        for($i=0;$i<sizeof($features);$i=$i+1) {
                            echo '<a href="#" class="list-group-item feature-item" onclick="showReviews('.$i.')"style="display:none;padding-left:30px">'.ucwords($features[$i]).'</a>';
                        }
                    ?>
                </div>
            </div>

            <div class="col-md-9" style="height:100%">

                <div>
<!--                    <img class="img-responsive" src="http://placehold.it/800x300" alt="">
 -->                <div class="caption-full" >
                        <h4><a href="#"><?php echo $title; ?></a></h4>
                    </div> 
                    <hr>
                    <div class="caption-full metadata">
                        <img class = "pull-left metadata" src="images/sample_product.jpg" style="width:40%" alt="">
                        <hr><hr>
                        <h4><a href="#" class="metadata">Product Description</a></h4>
                        <hr>
                        <div class="ratings metadata">
                            <h4><?php echo 'Price : '.$price;?></h4>
                        </div>
                                    
                    </div>
                    <div class="ratings metadata">
                        <p>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star"></span>
                            <span class="glyphicon glyphicon-star-empty"></span>
                        </p>
                    </div>
                    <div class="feature-progress-bars">
                        <div class="progress-bars" id="bar1" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspReading Corpus of Reviews</div>
                        <div class="progress-bars" id="bar2" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspRegex based Review Transformation to Processable Text</div>
                        <div class="progress-bars" id="bar3" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspTokenizing corpus</div>
                        <div class="progress-bars" id="bar4" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspImplicit Feature Extraction</div>
                        <div class="progress-bars" id="bar5" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspFeature Clustering and Filtering</div>
                        <div class="progress-bars" id="bar6" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspSentiment Words Extraction</div>
                        <div class="progress-bars" id="bar7" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspSentiment Words Association with Corresponding Features</div>
                        <div class="progress-bars" id="bar8" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspFeature Scoring</div>
                        <div class="progress-bars" id="bar9" style="font-size:18px;color:green;display:none">&#10004&nbsp&nbsp&nbspFeature-based Review Clustering into Positive and Negative Reviews</div>
                    </div>
                    <div class="datagrid" style="display:none;">
                        <table class="summary">
                            <thead>
                                <tr>
                                    <th>Feature</th>
                                    <th>Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                    for($i=0;$i<sizeof($features);$i=$i+1) {
                                        if($i % 2 == 0) {
                                            echo '<tr><td>'.ucwords($features[$i]).'</td><td>';
                                            for($j=0;$j<$scores[$i];$j=$j+1)
                                                echo '<span class="glyphicon glyphicon-star"></span>';
                                            for($j=0;$j<5-$scores[$i];$j=$j+1)
                                                echo '<span class="glyphicon glyphicon-star-empty"></span>';
                                            echo '</td></tr>';
                                        }
                                        else {
                                            echo '<tr class="alt"><td>'.ucwords($features[$i]).'</td><td>';
                                            for($j=0;$j<$scores[$i];$j=$j+1)
                                                echo '<span class="glyphicon glyphicon-star"></span>';
                                            for($j=0;$j<5-$scores[$i];$j=$j+1)
                                                echo '<span class="glyphicon glyphicon-star-empty"></span>';
                                            echo '</td></tr>';
                                        }
                                    }

                                ?>
                            </tbody>
                        </table>
                        <?php
                        for($i=0;$i<sizeof($features);$i=$i+1) {
                            if(sizeof($reviews[$i*2]) > 0)
                            {
                                echo '<table class="pos-reviews feature-pos'.$i.'" style="margin-bottom:1cm">';
                                echo '<thead><tr><th>Positive Reviews</th></tr></thead>';
                                echo '<tbody>';
                                for($j=0;$j<sizeof($reviews[$i*2]);$j=$j+1) {
                                        if($j % 2 == 0) {
                                            echo '<tr><td>'.$reviews[$i*2][$j].'</td>';
                                            echo '</tr>';
                                        }
                                        else {
                                            echo '<tr><td>'.$reviews[$i*2][$j].'</td>';
                                            echo '</tr>';
                                        }

                                }
                                echo '</tbody>';
                                echo '</table>';
                                // echo '<div class="gap gap-between-tables'.$i.'" style="height:30px;display:none"></div>';
                            }
                            if(sizeof($reviews[$i*2+1])>0)
                            {
                                echo '<table class="neg-reviews feature-neg'.$i.'">';
                                echo '<thead><tr><th>Negative Reviews</th></tr></thead>';
                                echo '<tbody>';
                                for($j=0;$j<sizeof($reviews[$i*2+1]);$j=$j+1) {
                                        if($j % 2 == 0) {
                                            echo '<tr><td>'.$reviews[$i*2+1][$j].'</td>';
                                            echo '</tr>';
                                        }
                                        else {
                                            echo '<tr><td>'.$reviews[$i*2+1][$j].'</td>';
                                            echo '</tr>';
                                        }

                                }
                                echo '</tbody>';
                                echo '</table>';   
                            }
                        }
                        ?>
                    </div>
                </div>
                <div>
                
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
                    <!-- <p>Copyright &copy; IntelliShop 2015</p> -->
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
<script type="text/javascript">
    console.log('here');
    var count = 9;
    $(".pos-reviews").hide();
    $(".neg-reviews").hide();

    $(".metadata").hide();
    setTimeout(function f(){start_bar(1)},500);
    function start_bar(barid){
        $("#bar"+barid).fadeIn("slow");

        if(barid < count){
            console.log('here'+barid);
            setTimeout(function f(){start_bar(barid+1)},500);   
        }
        else{
            $(".feature-progress-bars").fadeOut("fast");
            $(".feature-item").fadeIn("slow");
            $("#summary-btn").click();
        }
    }
    function showSummary() {
        $(".pos-reviews").hide();
        $(".neg-reviews").hide();
        $(".gap").hide();
        $(".datagrid").show();   
        $(".summary").fadeIn("fast");
    }
    function showReviews(id) {
        console.log('Here');
        $(".pos-reviews").hide();
        $(".neg-reviews").hide();
        $(".summary").hide();
        $(".datagrid").show();   
        $(".gap-between-tables"+id).show();
    
        $(".feature-pos"+id).fadeIn("fast");
        $(".feature-neg"+id).fadeIn("fast");
        

        // $(".feature-pos"+id).fadeIn("slow");
        // $(".feature-neg"+id).fadeIn("slow");

    }
</script>
</html>
