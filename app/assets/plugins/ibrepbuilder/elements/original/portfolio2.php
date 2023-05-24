<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Portfolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
<link href="/assets/plugins/ibrepbuilder/elements/bundles/original_portfolios.css" rel="stylesheet"></head>
<body>

    <div id="page" class="page">

    	<div class="item portfolio" id="portfolio2">

    		<div class="container">

    			<div class="row margin-bottom-40">

    				<div class="col-md-12">

    					<p class="lead text-center editContent">
    						We're awesome - see some of our work below
    					</p>

    				</div><!-- /.col-md-12 -->

    			</div><!-- /.row -->

    			<div class="row margin-bottom-20">

                    <?php
                    $repeat = ($_GET["e"] === "null") ? 1 : $_GET["e"];
                    for($i = 0; $i < $repeat; $i++) {
                        echo '

                				<div class="col-md-3">

            						<a href="" class="over">
                						<img src="/assets/plugins/ibrepbuilder/elements/bundles/692dabfba88338b4b58e5c7537b983bd.jpg" class="img-rounded width-100" alt="" id="testImage">
            						</a>

                				</div><!-- /.col-md-3 col -->

                				<div class="col-md-3">

                				   	<a href="" class="over">
                				   		<img src="/assets/plugins/ibrepbuilder/elements/bundles/a0a1ecbbfa5b513c69a74401d5bd2a8e.jpg" class="img-rounded width-100" alt="">
                				   	</a>

                				</div><!-- /.col-md-3 col -->

                				<div class="col-md-3">

                				   	<a href="" class="over">
                				   		<img src="/assets/plugins/ibrepbuilder/elements/bundles/8cc3c1d112023692c3280583803a265e.jpg" class="img-rounded width-100" alt="">
                				   	</a>

                				</div><!-- /.col-md-3 col -->

                				<div class="col-md-3">

                				   	<a href="" class="over">
                				   		<img src="/assets/plugins/ibrepbuilder/elements/bundles/4fc4b19bda814b8243e262879590cb2d.jpg" class="img-rounded width-100" alt="">
                				   	</a>

                				</div><!-- /.col-md-3 col -->

                			</div><!-- /.row -->

                			<div class="row margin-bottom-20">

                				<div class="col-md-3">

                				   	<a href="" class="over">
                				   		<img src="/assets/plugins/ibrepbuilder/elements/bundles/f1b94538dc68cbea3086aef8a32fee04.jpg" class="img-rounded width-100" alt="">
                				   	</a>

                				</div><!-- /.col-md-3 col -->

                				<div class="col-md-3">

                				   	<a href="" class="over">
                				   		<img src="/assets/plugins/ibrepbuilder/elements/bundles/f0794b211199b521f245b512597a7fef.jpg" class="img-rounded width-100" alt="">
                				   	</a>

                				</div><!-- /.col-md-3 col -->

                				<div class="col-md-3">

                				   	<a href="" class="over">
                				   		<img src="/assets/plugins/ibrepbuilder/elements/bundles/db55efd4295a01ac1a6d4ca8b354958c.jpg" class="img-rounded width-100" alt="">
                				   	</a>

                				</div><!-- /.col-md-3 col -->

                				<div class="col-md-3">

                				   	<a href="" class="over">
                				   		<img src="/assets/plugins/ibrepbuilder/elements/bundles/5a111297f589fb0d2656c3867331be53.jpg" class="img-rounded width-100" alt="">
                				   	</a>

                				</div><!-- /.col-md-3 col -->' ;} ?>

    			</div><!-- /.row -->

    		</div><!-- /.container -->

    	</div><!-- /.item -->

    </div><!-- /#page -->

<script type="text/javascript" src="/assets/plugins/ibrepbuilder/elements/bundles/original_portfolios.bundle.js"></script></body>
</html>
