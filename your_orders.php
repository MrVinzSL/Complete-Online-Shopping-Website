<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'cshells';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(empty($_SESSION['user_id']))  
{
	header('location:login.php');
}
else
{
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>My Orders</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style type="text/css" rel="stylesheet">
    
        
    .indent-small {
        margin-left: 5px;
    }

    .form-group.internal {
        margin-bottom: 0;
    }

    .dialog-panel {
        margin: 10px;
    }

    .datepicker-dropdown {
        z-index: 200 !important;
    }

    .panel-body {
        background: #e5e5e5;
        /* Old browsers */
        background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* FF3.6+ */
        background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
        /* Chrome,Safari4+ */
        background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* Opera 12+ */
        background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
        /* IE10+ */
        background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
        /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
        font: 600 15px "Open Sans", Arial, sans-serif;
    }

    label.control-label {
        font-weight: 600;
        color: #777;
    }

    /* 
table { 
	width: 750px; 
	border-collapse: collapse; 
	margin: auto;
	
	}

/* Zebra striping */
    /* tr:nth-of-type(odd) { 
	background: #eee; 
	}

th { 
	background: #404040; 
	color: white; 
	font-weight: bold; 
	
	}

td, th { 
	padding: 10px; 
	border: 1px solid #ccc; 
	text-align: left; 
	font-size: 14px;
	
	} */
    @media only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px) {

        /* table { 
	  	width: 100%; 
	}

	
	table, thead, tbody, th, td, tr { 
		display: block; 
	} */


        /* thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	
	tr { border: 1px solid #ccc; } */

        /* td { 
		
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}

	td:before { 
		
		position: absolute;
	
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
		
		content: attr(data-column);

		color: #000;
		font-weight: bold;
	} */

    }
    </style>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

</head>

<style>
        .button-row {
            display: flex; /* Arrange items in a row */
            justify-content: center; /* Center items horizontally */
            gap: 20px; /* Add space between buttons */
            margin: 20px 0; /* Add spacing around the row */
        }

        .btnLog {
            font-size: 1.1rem;
            padding: 8px 0;
            border-radius: 5px;
            outline: none;
            border: none;
            width: 500px; /* Adjust width to fit text */
            background: rgb(148, 126, 4);
            color: white;
            cursor: pointer;
            transition: 0.9s;
            text-align: center; /* Center text inside buttons */
            text-decoration: none; /* Remove underline */
            margin-bottom: 0; /* Remove space below button */
        }

        .btnLog:hover {
            background: #463f02;
        }
    </style>

<body>
    


    <header id="header" class="header-scroll top-header headrom">

    <div class="button-row">
        <a href="cart.php" class="btnLog">Back To Shopping</a>
    </div>

    </header>
    <div class="page-wrapper">



        <div class="inner-page-hero bg-image" data-image-src="images/img/pimg.jpg">
            <div class="container"> </div>

        </div>
        <div class="result-show">
            <div class="container">
                <div class="row">


                </div>
            </div>
        </div>

        <section class="restaurants-page">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                    </div>
                    <div class="col-xs-12">
                        <div class="bg-gray">
                            <div class="row">

                                <table class="table table-bordered table-hover">
                                    <thead style="background: #404040; color:white;">
                                        <tr>

                                            <th>Address</th>
                                            <th>Mobile Number</th>
                                            <th>Total Amount</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>


                                        <?php 
				
						$query_res= mysqli_query($conn,"select * from orders where user_id='".$_SESSION['user_id']."'");
												if(!mysqli_num_rows($query_res) > 0 )
														{
															echo '<td colspan="6"><center>You have No orders Placed yet. </center></td>';
														}
													else
														{			      
										  
										  while($row=mysqli_fetch_array($query_res))
										  {
						
							?>
                                        <tr>
                                            <td data-column="Item"> <?php echo $row['address']; ?></td>
                                            <td data-column="Quantity"> <?php echo $row['mobile_number']; ?></td>
                                            <td data-column="price">$<?php echo $row['total_amount']; ?></td>
                                            <td data-column="status">
                                                <?php 
																			$status=$row['status'];
																			if($status=="" or $status=="NULL")
																			{
																			?>
                                                <button type="button" class="btn btn-info"><span class="fa fa-bars" aria-hidden="true"></span> Dispatch</button>
                                                <?php 
																			  }
																			   if($status=="Pending")
																			 { ?>
                                                <button type="button" class="btn btn-warning">On The Way! ⏳</button>
                                                <?php
																				}
																			if($status=="Delivered")
																				{
																			?>
                                                <button type="button" class="btn btn-success">Delivered 🚛</button>
                                                <?php 
																			} 
																			?>
                                                <?php
																			if($status=="Cancelled")
																				{
																			?>
                                                <button type="button" class="btn btn-danger">Cancelled 😪</button>
                                                <?php 
																			} 
																			?>






                                            </td>
                                            <td data-column="Date"> <?php echo $row['order_date']; ?></td>
                                            <td data-column="Action"> <a href="delete_orders.php?order_del=<?php echo $row['order_id'];?>" onclick="return confirm('Are you sure you want to cancel your order?');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10">Cancel Order 🚮 </a>
                                            </td>

                                        </tr>


                                        <?php }} ?>




                                    </tbody>
                                </table>



                            </div>

                        </div>



                    </div>



                </div>
            </div>
        </div>
    </section>
    </div>


    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>

<!--Footer-->
<section class="footer">
        <div class="footer-box">
            <h3>C Shells Store</h3>
            <p>Thank you for choosing C Shell – Where Style Meets Convenience!</p>
        </div>
        <div class="social">
            <a href="https://www.facebook.com/profile.php?id=61550031967493"><i class='bx bxl-facebook' ></i></a>
            <a href="#"><i class='bx bxl-twitter' ></i></a>
            <a href="#"><i class='bx bxl-instagram' ></i></a>
            <a href="#"><i class='bx bxl-youtube' ></i></a>
        </div>
    
    
    <div class="footer-box">
        <h3>Suppot</h3>
        <li><a href="#">Products</a></li>
        <li><a href="#">Help and Support</a></li>
        <li><a href="#">Return Policy</a></li>
        <li><a href="#">Terms of use</a></li>
    </div>
    <div class="footer-box">
        <h3>View Guides</h3>
        <li><a href="#">Featurs</a></li>
        <li><a href="#">Careers</a></li>
        <li><a href="#">Blog Post</a></li>
        <li><a href="#">Developers</a></li>
    </div>
    <div class="contact">
        <h3>Contact</h3>
        <span><i class='bx bx-map'></i>Ambalangoda,Southern Province,Sri Lanka</span>
        <span><i class='bx bx-phone-call'></i>+94 77 123 4567</span>
        <span><i class='bx bx-envelope'></i>cshells@web.com</span>
    
    </section>

</body>

</html>
<?php
}
?>