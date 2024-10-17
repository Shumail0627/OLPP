<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/web.png">
    <title>About Us - OLPP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #4caf50;
            overflow: hidden;
            padding: 10px;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .navbar .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar .navbar-brand .logo-bold {
            font-weight: bold;
            color: #000;
            font-size: 24px;
            margin-right: 5px;
        }

        .navbar .navbar-brand .logo-thin {
            font-weight: 300;
            color: #000;
            font-size: 16px;
        }

        .navbar .navbar-menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar .navbar-menu li {
            margin-left: 20px;
        }

        .navbar .navbar-menu li a {
            color: #000;
            text-decoration: none;
            padding: 10px;
        }

        .navbar .navbar-menu li a:hover {
            background-color: #575757;
            border-radius: 5px;
        }

        .navbar .navbar-search {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .navbar .navbar-search input[type="text"] {
            padding: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .navbar .navbar-search button {
            padding: 6px 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
        }

        .navbar .navbar-search button:hover {
            background-color: #45a049;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 50px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .content {
            max-width: 50%;
        }

        .content h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }

        .content p {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
        }

        .content .cta-btn {
            background-color: #007bff;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

        .content .cta-btn:hover {
            background-color: #0056b3;
        }

        .image-container {
            max-width: 40%;
            position: relative;
        }

        .image-container img {
            width: 100%;
            border-radius: 10px;
        }


        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 20px;
            }

            .content, .image-container {
                max-width: 100%;
            }

            .image-container {
                margin-top: 20px;
            }

            .content h1 {
                font-size: 36px;
            }

            .content p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <span class="logo-bold">OLPP</span>
                <span class="logo-thin">One Laptop Per Pakistani</span>
            </div>
            <ul class="navbar-menu">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="all_customers.php">Customers</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <form class="navbar-search" method="GET" action="search_results.php">
                <input type="text" name="query" placeholder="Search..." id="search-box" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="content">
            <h1>About OLPP</h1>
            <p>
                One Laptop Per Pakistani (OLPP) is an initiative by Rehan Allahwala aimed at providing laptops to every citizen of Pakistan. 
                With a vision of creating an educated, empowered society through technology, OLPP helps individuals access information, 
                improve their skills, and contribute to the country's development.
            </p>
            <p>
                OLPP offers laptops through affordable installment plans, ensuring that the latest technology is accessible to everyone, 
                regardless of their financial background. By leveraging technology and education, OLPP is fostering a new generation 
                of empowered individuals, ready to take on the challenges of the digital world.
            </p>
            <a href="contact.php" class="cta-btn">Contact Us</a>
        </div>
        <div class="image-container">
            <img src="images/web.png" alt="OLPP Logo">
            <div class="image-decor"></div>
        </div>
    </div>
</body>
</html>
