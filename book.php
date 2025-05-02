<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book</title>

    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>

    <!--font awesome link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- custome css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- header -->
    <section class="header">
        <a href="home.php" class="logo">
            travel.
        </a>

        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About Us</a>
            <a href="package.php">Package</a>
            <a href="book.php">Book</a>
        </nav>

        <div id="menu-btn" class="fas fa-bars"></div>
    </section>


    <div class="heading" style="background:url(images/heading-bg-3.png) no-repeat">
        <h1>Book Now</h1>
    </div>

    <!-- booking section -->
     <section class="booking">
        <h1 class="heading-title">
            Book your trip today!
        </h1>

        <form action="book_form.php" method="post" class="book-form">
            <div class="flex">
                <div class="inputBox">
                    <span>Name:</span>
                    <input type="text" name="name" placeholder="enter your name">
                </div>

                <div class="inputBox">
                    <span>E-mail:</span>
                    <input type="email" name="email" placeholder="enter your email">
                </div>

                <div class="inputBox">
                    <span>Phone:</span>
                    <input type="number" name="number" placeholder="enter your phone">
                </div>

                <div class="inputBox">
                    <span>Address:</span>
                    <input type="text" name="address" placeholder="enter your address">
                </div>

                <div class="inputBox">
                    <span>Where to:</span>
                    <input type="text" name="location" placeholder="place you want to visit">
                </div>

                <div class="inputBox">
                    <span>How many:</span>
                    <input type="text" name="guests" placeholder="number of guests">
                </div>

                <div class="inputBox">
                    <span>Arrivals:</span>
                    <input type="date" name="arrival">
                </div>

                <div class="inputBox">
                    <span>Leaving:</span>
                    <input type="date" name="leaving">
                </div>
            </div>

            <input type="submit" value="submit" class="btn" name="Send">

        </form>
     </section>

    <!-- footer -->
        <section class="footer">
            <div class="box-container">
                <div class="box">
                    <h3>Quick Links</h3>
                    <a href="home.php"><i class="fas fa-angle-right"></i> Home</a>
                    <a href="about.php"><i class="fas fa-angle-right"></i> About Us</a>
                    <a href="package.php"><i class="fas fa-angle-right"></i> Package</a>
                    <a href="book.php"><i class="fas fa-angle-right"></i> Book</a>
                </div>

                <div class="box">
                    <h3>Extra Links</h3>
                    <a href="#"><i class="fas fa-angle-right"></i> Ask questions</a>
                    <a href="#"><i class="fas fa-angle-right"></i> About Us</a>
                    <a href="#"><i class="fas fa-angle-right"></i> Privacy policy</a>
                    <a href="#"><i class="fas fa-angle-right"></i> Terms of use</a>
                </div>

                <div class="box">
                    <h3>Contact Info</h3>
                    <a href="#"><i class="fas fa-phone"></i> 707-345-7350</a>
                    <a href="#"><i class="fas fa-phone"></i> 802-654-3822</a>
                    <a href="#"><i class="fas fa-envelope"></i> JackVJackson@rhyta.com</a>
                    <a href="#"><i class="fas fa-map"></i> 4571 Meadow LaneFremont, CA 94539</a>
                </div>

                <div class="box">
                    <h3>Follow Us</h3>
                    <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="#"><i class="fab fa-linkedin"></i> Linkedin</a>
                </div>
            </div>

            <div class="credit"> ©TravelSrilanka | all rights reserved!</div>
        </section>







    <!-- swiper js link -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- custom js file link -->
     <script src="js/script.js"></script>
</body>
</html>