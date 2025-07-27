<?php
session_start();
include 'db.php';

// Handle sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
$query = "SELECT * FROM beverage WHERE product_code IN ('drinks', 'cake', 'bread')";

switch ($sort) {
    case 'alphabetical':
        $query .= " ORDER BY product_name ASC";
        break;
    case 'reverse':
        $query .= " ORDER BY product_name DESC";
        break;
}

// Execute the query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>7 Brews and Bites</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .cart-items-container {
            display: none; /* Hide by default */
        }
        .cart-items-container.active {
            display: block; /* Show when active */
        }
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745; /* Green color */
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: opacity 0.5s ease-in-out;
            opacity: 0;
            font-size: 16px;
        }
        .user-links {
            margin-left: auto;
            display: flex;
            gap: 20px;
        }
        .user-links a {
            color: white; 
            text-decoration: none;
            font-size: 16px;
        }
        .user-links a:hover {
            text-decoration: underline;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
		.faq-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .faq {
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }

        .question {
            background: #f1f1f1;
            padding: 15px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .answer {
            padding: 15px;
            display: none; /* Hidden by default */
            background: #fff;
        }
		.cart-item {
    font-size: 15px; /* Increase the font size of the cart item */
    margin-bottom: 10px; /* Add space between items */
}

.cart-item .quantity {
    margin: 0 10px; /* Space between buttons and quantity */
    font-weight: bold; /* Make quantity text bold */
}

.cart-item button {
    cursor: pointer;
    margin: 0 5px; /* Space between increment and decrement buttons */
    font-size: 20px; /* Increase button font size */
    font-weight: bold; /* Make button text bold */
    padding: 5px 10px; /* Increase button size with padding */
    border: 1px solid #ccc; /* Add border for better visibility */
    border-radius: 5px; /* Rounded corners */
    background-color: #f0f0f0; /* Light background color */
    transition: background-color 0.3s; /* Smooth background transition */
}

.cart-item button:hover {
    background-color: #e0e0e0; /* Darker background on hover */
}
textarea {
    width: 100%; /* Full width */
    padding: 10px; /* Padding inside the textarea */
    border: 1px solid #ccc; /* Border color */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size */
    resize: vertical; /* Allow vertical resizing */
    margin-top: 10px; /* Space above the textarea */
}

/* Optional: Style for when the textarea is focused */
textarea:focus {
    border-color: #28a745; /* Change border color on focus */
    outline: none; /* Remove default outline */
}
 .email-link {
        display: inline-flex;
        align-items: center;
        font-size: 24px; /* Increase font size */
        color: #ffffff; /* Link color */
        text-decoration: none;
        padding: 10px 15px; /* Add padding */
        border: 2px solid ##73480d; /* Optional: border around the link */
        border-radius: 5px; /* Optional: rounded corners */
        transition: background-color 0.3s, color 0.3s; /* Smooth transition */
    }
    
    .email-link:hover {
        background-color: #73480d; /* Change background on hover */
        color: white; /* Change text color on hover */
    }

    .email-link span {
        margin-right: 8px; /* Space between icon and text */
        font-size: 30px; /* Increase icon size */
    }
    </style>
</head>
<body>

<!-- Toast Notification -->
<div id="toast" class="toast" style="display:none;">Item added to cart!</div>

<!-- Header section starts -->
<header class="header">
    <a href="#" class="logo">
        <img src="images/logo1.png" alt="7 Brews and Bites logo">
    </a>
    <nav class="navbar">
        <a href="#home">home</a>
        <a href="#about">about</a>
        <a href="#menu">menu</a>
        <a href="#products">product</a>
        <a href="#review">review</a>
        <a href="#contact">contact</a>
        <a href="#blogs">blogs</a>
        <a href="#faqs">FAQ's</a>
    </nav>
    <div class="user-links">
        <div class="icons">
            
            <div class="fas fa-search" id="search-btn" aria-label="Search"></div>
            <div class="fas fa-shopping-cart" id="cart-btn" aria-label="Cart"></div>
            <div class="fas fa-bars" id="menu-btn" aria-label="Menu"></div>
        </div>

        <div class="cart-items-container">
            <div id="cart-items"></div>
            <div class="total-price">Total: ₱<span id="total-price">0</span></div>
            <form action="checkout.php" method="POST">
                <input type="hidden" name="cart" id="cart-input">
                <button type="submit" class="btn" id="checkout-btn">Checkout Now</button>
            </form>
        </div>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</header>
<!-- Header section ends -->
<section class="home" id="home" style="background-image: url('images/home1-img.jpg'); background-size: cover; background-position: center;">

    <div class="content">
            <h3>fresh coffee in the morning</h3>
            <p style="color: white; font-weight: bold;">At 7 Brews and Bites, we source our beans from the best local farms, ensuring every cup is fresh and full of flavor. Our skilled baristas are dedicated to crafting the perfect brew for every coffee lover.</p>
            <p style="color: white; font-weight: bold;">Join us for a delightful experience where every sip tells a story. From classic brews to unique blends, we have something for everyone!</p>
            <a href="#menu" class="btn">get yours now</a>
    </div>

 </section> 
<section class="about" id="about">
    <h1 class="heading"> <span>about</span> us </h1>

    <div class="row">

        <div class="image">
            <img src="images/about-img.jpeg" alt="">
         </div>
         
         <div class="content">
          <h3>What makes our coffee special?</h3>
          <p>At 7 Brews and Bites, we are dedicated to putting the spotlight on Philippine Coffee, supporting local farmers through fair trade, and sourcing the finest beans from across the Philippines, including Sagada & Benguet in the North, and Mt. Apo, Mt. Kitanglad, and Mt. Matutum in the South. As a platform for social impact and Philippine artistry, we take pride in being an avenue for young brands and communities to express their creativity and artistry. Our social enterprise partners, including Anthill Fabric Gallery, Theo & Philo Artisan Chocolates, Kalsada Coffee PH, The Dream Coffee and Hope in a Bottle, reflect our commitment to supporting local talent and sustainable practices. Embracing the Philippine brand of hospitality, our coffee shops embody Filipino warmth, making every customer feel welcome in a place they can call home. Discover the best of Philippine coffee and support local communities with Bo's Coffee, your go-to destination for homegrown brews. Find a location near you or shop online today.</p>
          
        </div>
            
    </div>

</section> 
<!-- Menu section starts -->
<section class="menu" id="menu">
    <h1 class="heading"> our <span>menu</span> </h1>
	<br>
    <!-- Sorting Dropdown -->
    <label for="sort">Sort by:</label>
    <select id="sort" onchange="window.location.href='index.php?sort=' + this.value;">
        <option value="default">Default</option>
		<option value="alphabetical" <?php echo $sort === 'alphabetical' ? 'selected' : ''; ?>>Alphabetical (A-Z)</option>
        <option value="reverse" <?php echo $sort === 'reverse' ? 'selected' : ''; ?>>Alphabetical (Z-A)</option>
    </select>
<br>
<br>
   <div class="box-container" id="product-list">

    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="box">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" class="product-image">
                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                <div class="price">₱<?php echo htmlspecialchars($row['price']); ?></div>
                <a href="#" class="btn add-to-cart" data-name="<?php echo htmlspecialchars($row['product_name']); ?>" data-price="<?php echo htmlspecialchars($row['price']); ?>">add to cart</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No items available.</p>
    <?php endif; ?>
</div>

</section>
<!-- Menu section ends -->
<section class="products" id="products">
    <h1 class="heading"> our <span>products</span> </h1>

  <div class="box-container">
    <?php
    // Updated query to select only products with product_code "product"
    $query = "SELECT * FROM beverage WHERE product_code = 'product'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()): ?>
           <div class="box">
                <div class="image">
				<img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" class="product-image">
				</div>
				<div class="content">
                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                <div class="price">₱<?php echo htmlspecialchars($row['price']); ?></div>
                <a href="#" class="btn add-to-cart" data-name="<?php echo htmlspecialchars($row['product_name']); ?>" data-price="<?php echo htmlspecialchars($row['price']); ?>">add to cart</a>
            </div>
			</div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No products available.</p>
    <?php endif; ?>
</div>
</section>
<!-- Products section ends -->
<section class="payment" id="method">
    <h1 class="heading"> payment <span>method</span></h1>
<div class="gcash-payment">
	<center>
    <h2 style="color:white;">Pay with GCash, Maya or Bank Transfer using</h2>
    <img src="images/instapay.png" alt="instapay" style="width:30%; height:10%;">
    <h2 style="color:white;">Enjoy a seamless payment experience.</h2>
	</center>
</div>
</section>
<!-- Review section starts -->
<section class="review" id="review">
    <h1 class="heading">Feedback <span></span></h1>

    <div class="box-container">
        <?php
        // Fetch reviews from the database
        include 'db.php'; // Include your database connection file

        $reviewsStmt = $conn->prepare("SELECT r.review AS review_text, r.rating, u.username FROM reviews r JOIN users u ON r.user_id = u.id limit 4");
        $reviewsStmt->execute();
        $reviews = $reviewsStmt->get_result();

        // Check if there are any reviews
        if ($reviews->num_rows > 0) {
            while ($review = $reviews->fetch_assoc()) {
                $ratingStars = str_repeat('<i class="fas fa-star"></i>', floor($review['rating'])) . 
                               str_repeat('<i class="fas fa-star-half-alt"></i>', $review['rating'] - floor($review['rating']));
                ?>
                <div class="box">
                    <img src="images/quote-img.png" alt="" class="quote">
                    <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                    <h3><?php echo htmlspecialchars($review['username']); ?></h3>
                    <div class="stars">
                        <?php echo $ratingStars; ?>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No reviews available.</p>";
        }
        ?>
    </div>
</section>


 <!-- review section ends-->

 <!-- contact section starts -->
<section class="contact" id="contact">
    <h1 class="heading"> <span>contact</span> us </h1>
    <div class="row">
	<div style="width: 80%;">
        <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d30619.983974721068!2d120.56817561562725!3d16.399515466860557!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3391a16879def13f%3A0x8edef534be3a75c0!2sBaguio%2C%20Benguet!5e0!3m2!1sen!2sph!4v1727222076992!5m2!1sen!2sph" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
     </div>   
      
                <a href="https://mail.google.com/mail/u/0/#inbox?compose=CllgCHrhTmfrzFPRCdkJCxMdJVKDzVkShQhxjMJLNPLlXxzZJfdRMLPQblQDJPlWmnbfkrnhmgB" class="email-link"><a href="https://mail.google.com/mail/u/0/#inbox?compose=CllgCHrhTmfrzFPRCdkJCxMdJVKDzVkShQhxjMJLNPLlXxzZJfdRMLPQblQDJPlWmnbfkrnhmgB" class="email-link" target="_blank">
				<span class="fas fa-envelope"></span> Email Us.
				</a>
			
            
       
    </div>
</section>


 <!-- contact section ends -->

 <!-- delivery section starts -->
<section class="delivery" id="delivery">
    <h1 class="heading"> Delivery <span>Options</span> </h1>
    
        <div class="delivery-options">
            <div class="option">
                <h3>Same-day Delivery</h3>
                <p>Order now for same-day delivery via Grab and Lalamove!</p>
            </div>
          
        </div>
    </div>
</section>
<!-- delivery section ends -->


 <!-- blogs section starts -->

 <section class="blogs" id="blogs">

    <h1 class="heading"> our <span>blogs</span> </h1>

    <div class="box-container">

        <div class="box">
            <div class="image">
                <img src="images/blogs-1.jpg" alt="">
            </div>
            <div class="content">
                <a href="#" class="title">cookies</a>
                <span> by admin / 21st May 2024</span>
                <p>The company itself is a very successful company. No, they said</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="images/blogs-2.jpg" alt="">
            </div>
            <div class="content">
                <a href="#" class="title">bread</a>
                <span> by admin / 21st May 2024</span>
                <p>The business is a highly successful one in and of itself. No, they declared.</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="images/blogs-3.jpg" alt="">
            </div>
            <div class="content">
                <a href="#" class="title">cinnamon roll</a>
                <span> by admin / 21st May 2024</span>
                <p>The company itself is a very successful company. No, they said</p>
                <a href="#" class="btn">read more</a>
            </div>
        </div>

    </div>

</section>

 <!-- blogs section ends -->

<!-- faq section starts -->
<section class="faqs" id="faqs">
    <h1 class="heading"> Frequently <span>Asked Questions</span> </h1>
    
    <div class="faq-container">
        <div class="faq">
            <h3 class="question">What are your opening hours? <span class="fas fa-chevron-down"></span></h3>
            <p class="answer">We are open from 7 AM to 10 PM, every day of the week.</p>
        </div>

        <div class="faq">
            <h3 class="question">Do you offer takeout or delivery? <span class="fas fa-chevron-down"></span></h3>
            <p class="answer">Yes, we offer both takeout and delivery services for all our menu items.</p>
        </div>

        <div class="faq">
            <h3 class="question">Can I book a table in advance? <span class="fas fa-chevron-down"></span></h3>
            <p class="answer">Yes, you can book a table by calling us or through our website.</p>
        </div>

        <div class="faq">
            <h3 class="question">What discounts do customers receive? <span class="fas fa-chevron-down"></span></h3>
            <p class="answer">All  of our customers can enjoy discount on all coffee and food items.</p>
        </div>

        <div class="faq">
            <h3 class="question">How can I contact you? <span class="fas fa-chevron-down"></span></h3>
            <p class="answer">You can contact us through the contact form on our website or call us directly.</p>
        </div>
    </div>
</section>
<!-- faq section ends -->

 <!-- footer section starts -->

 <section class="footer">
    <div class="share">
        <a href="#" class="fab fa-facebook-f"></a>
        <a href="#" class="fab fa-twitter"></a>
        <a href="#" class="fab fa-instagram"></a>
        <a href="#" class="fab fa-tiktok"></a>
        <a href="#" class="fab fa-youtube"></a>
    </div>

<!-- Footer section starts -->
<footer class="footer">
    <div class="footer-content" style="color: white;">
        <h2>7 Brews and Bites</h2>
        <h3>Your favorite spot for delicious coffee and snacks!</h3>

    </div>
</footer>
<!-- Footer section ends -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cart = [];
    const cartItemsContainer = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');
    const cartInput = document.getElementById('cart-input');

    function addToCart(item) {
        const existingItem = cart.find(cartItem => cartItem.name === item.product_name);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            item.quantity = 1;
            cart.push(item);
        }
        updateCartDisplay();
        showToast();
    }

    function updateCartDisplay() {
        cartItemsContainer.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('cart-item');
            cartItem.innerHTML = `
                ${item.name} - ₱${item.price} x 
                <button class="decrement" data-index="${index}">-</button>
                <span class="quantity">${item.quantity}</span>
                <button class="increment" data-index="${index}">+</button>
            `;
            cartItemsContainer.appendChild(cartItem);
            total += item.price * item.quantity;
        });

        totalPriceElement.textContent = total.toFixed(2);
        cartInput.value = JSON.stringify(cart); // Prepare cart for form submission
        attachQuantityListeners();
    }

    function attachQuantityListeners() {
        document.querySelectorAll('.increment').forEach(button => {
            button.addEventListener('click', (event) => {
                const index = event.target.getAttribute('data-index');
                cart[index].quantity++;
                updateCartDisplay();
            });
        });

        document.querySelectorAll('.decrement').forEach(button => {
            button.addEventListener('click', (event) => {
                const index = event.target.getAttribute('data-index');
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                } else {
                    cart.splice(index, 1); // Remove item if quantity is 0
                }
                updateCartDisplay();
            });
        });
    }

    function showToast() {
        const toast = document.getElementById('toast');
        toast.style.display = 'block';
        toast.style.opacity = 1;
        setTimeout(() => {
            toast.style.opacity = 0;
            setTimeout(() => {
                toast.style.display = 'none';
            }, 500);
        }, 2000);
    }

    // Add to cart functionality for menu items
    const menuItems = document.querySelectorAll('.menu .box .add-to-cart');
    menuItems.forEach(item => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            const productName = item.getAttribute('data-name');
            const productPrice = parseFloat(item.getAttribute('data-price'));
            addToCart({ name: productName, price: productPrice });
        });
    });

    // Add to cart functionality for product items
    const productItems = document.querySelectorAll('.products .box .add-to-cart');
    productItems.forEach(item => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            const productName = item.getAttribute('data-name');
            const productPrice = parseFloat(item.getAttribute('data-price'));
            addToCart({ name: productName, price: productPrice });
        });
    });

    const cartBtn = document.getElementById('cart-btn');
    const cartContainer = document.querySelector('.cart-items-container');

    cartBtn.addEventListener('click', () => {
        cartContainer.classList.toggle('active');
    });

    // FAQ toggle functionality
    const questions = document.querySelectorAll('.question');
    questions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
        });
    });
});
function sendEmail() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const number = document.getElementById('number').value;
    const message = document.getElementById('message').value;

    const subject = encodeURIComponent('New Contact Message from ' + name);
    const body = encodeURIComponent(`Name: ${name}\nEmail: ${email}\nNumber: ${number}\nMessage: ${message}`);

    const mailtoLink = `mailto:brewsnbites@gmail.com?subject=${subject}&body=${body}`;
    window.location.href = mailtoLink;
}
</script>

</body>
</html>
