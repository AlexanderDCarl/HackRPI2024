<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/x-icon" href="../resources/images/Logo-simple.svg">

    <style>
        #results {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, .5fr));
            gap: 16px;
            padding: 16px;
            justify-items: center;
        }

        .product-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
            width: 100%;
        }


        .product-item:hover {
            transform: scale(1.05);
        }

        .product-item img {
            height: 150px;
            width: auto;
            object-fit: contain;
            border-bottom: 1px solid #ddd;
        }

        .product-details {
            padding: 16px;
        }

        .product-details strong {
            display: block;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .product-details p {
            font-size: 14px;
            color: #555;
        }

        #pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            gap: 20px;
            z-index: 100;
        }

        .pagination-btn {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 10px;
            font-size: 24px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .pagination-btn:hover {
            background-color: #0056b3;
        }

        .pagination-btn.disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .pagination-btn.disabled:hover {
            background-color: #ddd;
        }

        #pagination-container {
            margin-bottom: 40px;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        #sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
        }

        #sidebar h2 {
            margin-top: 0;
        }

        .sidebar-section {
            margin-bottom: 20px;
        }

        #sidebar select, #sidebar input, #sidebar button {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
        }

        #main-content {
            margin-left: 260px;
            padding: 20px;
            flex-grow: 1;
        }

        #results {
            margin-top: 150px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .product-item {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .product-item:hover {
            background-color: #e2e2e2;
            cursor: pointer;
        }

        #pagination {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .navButton {
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        .navButton:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        #innerSidebar{
            margin-top: 150px;
        }

        .store-toggle {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background-color: #efefef;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .store-toggle.active {
            background-color: #007bff;
            color: white;
        }

    </style>
    <link rel="stylesheet" href="../resources/css/style.css">
</head>
<body>
    <div id="navbar">
        <img id="logo" src="../resources/images/logo.svg" onclick="window.location.href = '../index.html';" alt="Logo">

        <div id="navbar-wrap">
                    <span id="search-bar">
                        <input type="text" id="search-input" placeholder="Search products...">
                        <button class="search-button" onclick="" aria-label="Search">
                            <img src="../resources/images/Search.svg" alt="Search Icon">
                        </button>

                        <button id="shopping-cart" onclick="window.location.href = '../resources/php/cart.php';">
                            <img src="../resources/images/Cart.svg" alt="Cart">
                            <label>Go to Cart</label>
                        </button>
                        <div id="dropdown-menu">
                    <div id="dropdown-grid">
                        <div class="cell" onclick="querySearchBar('produce')">
                            <img src="../resources/images/produce-category.png" alt="Produce">
                            <p>Produce</p>
                        </div>
                        <div class="cell" onclick="querySearchBar('school supplies')">
                            <img src="../resources/images/school-category.png" alt="School Supplies">
                            <p>School Supplies</p>
                        </div>
                        <div class="cell" onclick="querySearchBar('beverages')">
                            <img src="../resources/images/drinks-category.png" alt="Beverages">
                            <p>Beverages</p>
                        </div>
                        <div class="cell" onclick="querySearchBar('household&essentials')">
                            <img src="../resources/images/household-category.png" alt="Household Essentials">
                            <p>Household Essentials</p>
                        </div>
                        <div class="cell" onclick="querySearchBar('meat')">
                            <img src="../resources/images/meat-category.png" alt="Meat">
                            <p>Meat</p>
                        </div>
                        <div class="cell" onclick="querySearchBar('cleaning&supplies')">
                            <img src="../resources/images/cleaning-category.png" alt="Cleaning Supplies">
                            <p>Cleaning Supplies</p>
                        </div>
                    </div>
                </div>
                    </span>

            <div class="widgets">
                <a href="../index.html">About</a>
                <a href="mailto:peterl8@rpi.edu,carla2@rpi.edu,loaizs@rpi.edu" target="_blank">Contact</a>
                <a href="../resources/php/login.php">Login</a>
                <a href="../resources/php/logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div id="sidebar">
        <div id="innerSidebar">
            <h2>Filter & Sort</h2>
            <div class="sidebar-section">
                <h3>Sort By</h3>
                <select id="sortDropdown">
                    <option value="Relevance">Relevance</option>
                    <option value="priceLowToHigh">Price: Low to High</option>
                    <option value="priceHighToLow">Price: High to Low</option>
                    <option value="ratingLowToHigh">Rating: Low to High</option>
                    <option value="ratingHighToLow">Rating: High to Low</option>
                </select>
            </div>
            <div class="sidebar-section">
                <h3>Filter Stores</h3>
                <button class="store-toggle" data-store="Amazon">Amazon</button>
                <button class="store-toggle" data-store="Walmart">Walmart</button>
                <button class="store-toggle" data-store="Costco">Costco</button>
                <button class="store-toggle" data-store="Target">Target</button>
            </div>
        </div>
    </div>

    <div id="main-content">
        <div id="results"></div>
        <div id="pagination">
            <button id="prevPage" class="navButton">Prev</button>
            <button id="nextPage" class="navButton">Next</button>
        </div>
    </div>

    <script src="../resources/js/api.js"></script>
</body>
</html>