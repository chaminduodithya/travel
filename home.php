<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

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

    <!-- home -->
     <section class="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide slide" style="background:url(images/home-slide-1.jpg) no-repeat">
                    <div class="content">
                        <span>explore, discover, travel</span>
                        <h3>Travel Around the World</h3>
                        <a href="package.php" class="btn">Discover more</a>
                    </div>
                </div>

                <div class="swiper-slide slide" style="background:url(images/home-slide-2.jpg) no-repeat">
                    <div class="content">
                        <span>explore, discover, travel</span>
                        <h3>Discover new places</h3>
                        <a href="package.php" class="btn">Discover more</a>
                    </div>
                </div>

                <div class="swiper-slide slide" style="background:url(images/home-slide-3.jpg) no-repeat">
                    <div class="content">
                        <span>explore, discover, travel</span>
                        <h3>Make your tour wothwhile</h3>
                        <a href="package.php" class="btn">Discover more</a>
                    </div>
                </div>
            </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
        </div>
     </section>



    <!-- services -->
        <section class="services">
            <h1 class="heading-title">Our Services </h1>
            <div class="box-container">
                <div class="box">
                    <img src="images/icon-1 (2).png" alt="">
                    <h3>Adventure</h3>
                </div>

                

                <div class="box">
                    <img src="images/icon-2.png" alt="">
                    <h3>Tour guide</h3>
                </div>

                <div class="box">
                    <img src="images/icon-3.png" alt="">
                    <h3>Terkking</h3>
                </div>

                <div class="box">
                    <img src="images/icon-5.png" alt="">
                    <h3>Camp fire</h3>
                </div>

                <div class="box">
                    <img src="images/icon-4.png" alt="">
                    <h3>Off road</h3>
                </div>

                <div class="box">
                    <img src="images/icon-6.png" alt="">
                    <h3>Camping</h3>
                </div>
            </div>
        </section>

    <!-- home about -->

    <section class="home-about">
        <div class="image">
            <img src="images/about-img.jpg" alt="">
        </div>

        <div class="content">
            <h3>About us</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vulputate sapien eget nulla condimentum mollis. Duis 
            elementum interdum nisl id dapibus. Curabitur varius ut dolor in consectetur. Nullam vehicula finibus erat, quis laoreet 
            augue feugiat non. Donec vitae faucibus mi. Curabitur et lobortis nunc. Sed mattis nulla tortor, in luctus justo viverra 
            ut. Ut quam diam, gravida vel lacinia id, tempus ac quam. Maecenas suscipit, nibh non bibendum placerat, velit elit 
            hendrerit arcu, in efficitur massa ligula in purus. Praesent congue ornare mauris, et ultrices ante convallis at.
            </p>
            <a href="about.php" class="btn">Read more</a>
        </div>
    </section>

    <!-- home packages -->
     <section class="home-packages">
        <h1 class="heading-title">
            Our Packages
        </h1>
        <div class="box-container">
            <div class="box">
                <div class="image">
                    <img src="images/img-1.jpg" alt="">
                </div>
                <div class="content">
                    <h3>Tropical Paradise Getaway</h3>
                    <p> Relax in the Maldives with a 7-day luxury beach resort stay, private overwater villa, snorkeling, and romantic beach dinners. ($2,500/person)
                    </p>
                    <a href="book.php" class=btn>Book now</a>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="images/img-2.jpg" alt="">
                </div>
                <div class="content">
                    <h3>European Adventure Tour</h3>
                    <p>Explore Paris, Rome, and Barcelona on a 10-day guided tour featuring the Eiffel Tower, gondola rides, and tapas tasting. ($3,200/person)</p>
                    <a href="book.php" class=btn>Book now</a>
                </div>
            </div>

            <div class="box">
                <div class="image">
                    <img src="images/img-3.jpg" alt="">
                </div>
                <div class="content">
                    <h3>Safari Expedition in Africa</h3>
                    <p>Experience an 8-day wildlife adventure in Kenya and Tanzania, including Maasai Mara tours and hot air balloon rides. ($4,800/person)</p>
                    <a href="book.php" class=btn>Book now</a>
                </div>
            </div>
        </div>
        <div class="load-more"><a href="package.php" class="btn">Load more</a></div>
     </section>


    <!-- home offer -->
     <section class="home-offer">
        <div class="content">
            <h3>Upto 50% off</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vulputate sapien eget nulla condimentum mollis.</p>
            <a href="book.php" class="btn">Book now</a>
        </div>
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