<?php
/**
 * VK Logistics - Ganesh Statue Booking Website
 * Main Landing & Booking Page
 */

require_once __DIR__ . '/includes/functions.php';

$settings = get_all_settings();
$unit_price = (float)($settings['unit_price'] ?? 14.99);
$shipping_charge = (float)($settings['shipping_charge'] ?? 3.99);
$currency_symbol = $settings['currency_symbol'] ?? '£';
$paypal_client_id = $settings['paypal_client_id'] ?? 'sb';
$csrf_token = get_csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape_output(APP_TITLE); ?> | VK Logistics</title>
    <meta name="description" content="Book your Ganesh Statue / Vinayaka Vigraha for Ganesh Chaturthi with VK Logistics. Doorstep delivery anywhere in the United Kingdom.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800&family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    
    <!-- PayPal JavaScript SDK (Async loaded) -->
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo urlencode($paypal_client_id); ?>&currency=GBP&components=buttons"></script>
</head>
<body>

    <!-- Festive Top Notice Bar -->
    <div class="top-festive-bar">
        🌸 <span class="highlight">Ganesh Chaturthi Special Delivery</span> — Deliveries available exclusively across the <span class="highlight">United Kingdom 🇬🇧</span>
    </div>

    <!-- Header Section -->
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <a href="index.php" class="brand-logo">
                    <div class="brand-logo-icon">VK</div>
                    <div class="brand-logo-text">
                        <span class="brand-name">VK Logistics</span>
                        <span class="brand-tagline">Ganesh Booking UK</span>
                    </div>
                </a>

                <button class="mobile-toggle" id="mobile-menu-toggle" aria-label="Toggle Menu">☰</button>

                <nav>
                    <ul class="nav-menu" id="nav-menu">
                        <li><a href="#hero" class="nav-link">Home</a></li>
                        <li><a href="#product-section" class="nav-link">Ganesh Statue</a></li>
                        <li><a href="#how-it-works" class="nav-link">How It Works</a></li>
                        <li><a href="#trust-section" class="nav-link">Delivery</a></li>
                        <li><a href="#faq-section" class="nav-link">FAQ</a></li>
                        <li><a href="#booking-section" class="btn-gold scroll-to-booking">🛕 Book Now</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section" id="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <span class="hero-tag">✨ Ganesh Chaturthi 2026 Special</span>
                    <h1 class="hero-title">Bring Home <span>Bappa</span> This Ganesh Chaturthi</h1>
                    <p class="hero-subtitle">
                        Book your Ganesh Statue with VK Logistics and get it conveniently delivered to your doorstep anywhere in the UK.
                    </p>

                    <div class="price-badge-box diya-glow">
                        <div>
                            <div class="price-main">ONLY £14.99</div>
                            <div class="price-sub">+ Shipping</div>
                        </div>
                        <div class="uk-badge">🇬🇧 UK Delivery Only</div>
                    </div>

                    <div class="hero-actions">
                        <a href="#booking-section" class="btn-saffron btn-pulse scroll-to-booking">
                            🛕 BOOK YOUR GANESH
                        </a>
                        <a href="#how-it-works" class="btn-outline-maroon">
                            How It Works
                        </a>
                    </div>

                    <div class="hero-trust-list">
                        <div class="trust-item">
                            <div class="check-icon">✓</div> Simple Booking
                        </div>
                        <div class="trust-item">
                            <div class="check-icon">✓</div> Secure Payment
                        </div>
                        <div class="trust-item">
                            <div class="check-icon">✓</div> UK Delivery
                        </div>
                        <div class="trust-item">
                            <div class="check-icon">✓</div> Booking Reference Provided
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-card-frame">
                        <div class="hero-img-wrapper">
                            <img src="assets/images/ganesh_hero.png" alt="Majestic Ganesh Statue VK Logistics UK">
                        </div>
                        <div class="hero-floating-badge">
                            <div>
                                <div class="floating-badge-title">Vinayaka Vigraha</div>
                                <div class="floating-badge-sub">Hand-crafted Eco Ganesh Statue</div>
                            </div>
                            <div class="text-gold" style="font-weight: 800; font-size: 1.2rem;">£14.99</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUCT STATUE SECTION -->
    <section class="product-section" id="product-section">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-subtitle-tag">Premium Collection</span>
                <h2 class="section-title">Your Ganesh Statue</h2>
                <p class="section-desc">Celebrate Ganesh Chaturthi with devotion, tradition and convenience.</p>
            </div>

            <div class="product-card">
                <div class="product-gallery">
                    <img src="assets/images/ganesh_product.png" alt="Ganesh Statue Vinayaka Vigraha">
                </div>
                <div class="product-details">
                    <h3 class="product-title">Ganesh Statue / Vinayaka Vigraha</h3>
                    
                    <div class="product-price-row">
                        <span class="product-price-amount">£14.99</span>
                        <span class="product-shipping-tag">+ Shipping (Calculated separately)</span>
                    </div>

                    <p class="product-desc">
                        A beautifully handcrafted Ganesh Idol designed to bring blessings, peace, and prosperity to your home. Carefully packaged and shipped safely anywhere within the UK.
                    </p>

                    <div class="included-box">
                        <div class="included-title">What's Included:</div>
                        <ul class="included-list">
                            <li><span class="text-saffron">✓</span> 1 Ganesh Statue / Vinayaka Vigraha</li>
                            <li><span class="text-saffron">✓</span> Protective Safe Shipping Packaging</li>
                            <li><span class="text-saffron">✓</span> Unique VK Logistics Tracking Reference</li>
                        </ul>
                    </div>

                    <!-- Quantity Calculator -->
                    <div class="qty-calculator-box">
                        <div class="qty-row">
                            <span class="qty-label">Select Quantity:</span>
                            <div class="qty-controls">
                                <button type="button" class="qty-btn minus">-</button>
                                <input type="number" id="quantity-input" class="qty-input" value="1" min="1" max="20" readonly>
                                <button type="button" class="qty-btn plus">+</button>
                            </div>
                        </div>
                        <div class="calc-summary-row">
                            <span id="calc-breakdown">1 × £14.99 + £3.99 shipping</span>
                            <span class="calc-total" id="calc-grand-total">£18.98</span>
                        </div>
                    </div>

                    <a href="#booking-section" class="btn-saffron scroll-to-booking" style="width: 100%;">
                        Book Your Ganesh Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS SECTION -->
    <section class="how-it-works-section" id="how-it-works">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-subtitle-tag">Simple & Seamless</span>
                <h2 class="section-title">How It Works</h2>
                <p class="section-desc">4 quick steps to receive your Ganesh Statue anywhere in the United Kingdom.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3 class="step-title">Choose Your Ganesh</h3>
                    <p class="step-desc">Select the required quantity of Ganesh statues for your celebration.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3 class="step-title">Enter Delivery Details</h3>
                    <p class="step-desc">Provide your full UK delivery street address and postcode.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3 class="step-title">Make Payment</h3>
                    <p class="step-desc">Pay securely using PayPal or direct Bank Transfer.</p>
                </div>

                <div class="step-card">
                    <div class="step-number">4</div>
                    <h3 class="step-title">Receive Confirmation</h3>
                    <p class="step-desc">Get your unique VK Logistics booking reference number instantly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TRUST SECTION BANNER -->
    <section class="trust-banner-section" id="trust-section">
        <div class="container">
            <div class="section-header text-center" style="margin-bottom: 36px;">
                <h2 class="section-title" style="color: var(--color-gold-light);">Book With Confidence</h2>
                <p class="section-desc" style="color: rgba(255, 255, 255, 0.8);">Your trusted logistics partner across the United Kingdom</p>
            </div>

            <div class="trust-grid">
                <div class="trust-card">
                    <div class="trust-icon">🔒</div>
                    <div class="trust-label">Secure Payment</div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">🇬🇧</div>
                    <div class="trust-label">UK Delivery</div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">📦</div>
                    <div class="trust-label">VK Logistics Delivery</div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">✓</div>
                    <div class="trust-label">Booking Confirmation</div>
                </div>
                <div class="trust-card">
                    <div class="trust-icon">🔢</div>
                    <div class="trust-label">Unique Reference Number</div>
                </div>
            </div>
        </div>
    </section>

    <!-- BOOKING FORM SECTION -->
    <section class="booking-section" id="booking-section">
        <div class="container">
            <div class="booking-panel">
                <div class="booking-header">
                    <div>
                        <h3>Complete Your Booking</h3>
                        <p>Enter your contact details and UK delivery address below.</p>
                    </div>
                    <div class="uk-badge" style="background: rgba(212, 175, 55, 0.2); color: var(--color-gold-light);">
                        🇬🇧 UK ONLY
                    </div>
                </div>

                <div class="booking-body">
                    <form id="main-booking-form">
                        <input type="hidden" id="csrf_token" value="<?php echo escape_output($csrf_token); ?>">
                        <input type="hidden" id="form-quantity" value="1">
                        <input type="hidden" id="payment_method" value="bank_transfer">

                        <!-- Personal Details -->
                        <h4 style="color: var(--color-maroon); margin-bottom: 16px;">1. Personal Details</h4>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label" for="customer_name">Full Name *</label>
                                <input type="text" id="customer_name" class="form-control" placeholder="e.g. Rajesh Patel" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="mobile">Mobile Number * (UK)</label>
                                <input type="tel" id="mobile" class="form-control" placeholder="e.g. +44 7700 900000" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="email">Email Address *</label>
                                <input type="email" id="email" class="form-control" placeholder="e.g. rajesh@example.co.uk" required>
                            </div>
                        </div>

                        <!-- Delivery Address -->
                        <h4 style="color: var(--color-maroon); margin-top: 32px; margin-bottom: 16px;">2. UK Delivery Address</h4>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label" for="address_line_1">Address Line 1 *</label>
                                <input type="text" id="address_line_1" class="form-control" placeholder="House number and street name" required>
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label" for="address_line_2">Address Line 2 (Optional)</label>
                                <input type="text" id="address_line_2" class="form-control" placeholder="Apartment, suite, unit, etc.">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="city">Town / City *</label>
                                <input type="text" id="city" class="form-control" placeholder="e.g. London, Birmingham, Leicester" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="county">County (Optional)</label>
                                <input type="text" id="county" class="form-control" placeholder="e.g. Greater London, West Midlands">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="postcode">UK Postcode *</label>
                                <input type="text" id="postcode" class="form-control" placeholder="e.g. SW1A 1AA" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="country">Country</label>
                                <input type="text" id="country" class="form-control" value="United Kingdom" readonly>
                            </div>
                        </div>

                        <!-- Order Summary Card -->
                        <div class="booking-summary-card">
                            <div class="summary-title">Order Summary</div>
                            <div class="summary-line">
                                <span>Ganesh Statue / Vinayaka Vigraha × <strong class="display-qty">1</strong></span>
                                <span class="display-subtotal">£14.99</span>
                            </div>
                            <div class="summary-line">
                                <span>Shipping Charge (UK Delivery)</span>
                                <span class="display-shipping">£3.99</span>
                            </div>
                            <div class="summary-line total">
                                <span>Total Payable</span>
                                <span class="display-total">£18.98</span>
                            </div>
                        </div>

                        <!-- Payment Method Section -->
                        <h4 style="color: var(--color-maroon); margin-bottom: 16px;">3. Select Payment Method</h4>
                        
                        <div class="payment-tabs-header">
                            <button type="button" class="payment-tab-btn active" data-tab="bank-tab">
                                🏦 Bank Transfer
                            </button>
                            <button type="button" class="payment-tab-btn" data-tab="paypal-tab">
                                💳 PayPal Checkout
                            </button>
                        </div>

                        <!-- BANK TRANSFER TAB CONTENT -->
                        <div class="payment-tab-content active" id="bank-tab">
                            <h5 style="color: var(--color-maroon); margin-bottom: 12px;">Pay via Direct UK Bank Transfer</h5>
                            
                            <div class="bank-info-box">
                                <div class="bank-detail-item">
                                    <span class="bank-detail-label">Account Name</span>
                                    <span class="bank-detail-val"><?php echo escape_output($settings['bank_account_name'] ?? 'VK LOGISTICS LTD'); ?></span>
                                </div>
                                <div class="bank-detail-item">
                                    <span class="bank-detail-label">Bank Name</span>
                                    <span class="bank-detail-val"><?php echo escape_output($settings['bank_name'] ?? 'Barclays Bank UK'); ?></span>
                                </div>
                                <div class="bank-detail-item">
                                    <span class="bank-detail-label">Sort Code</span>
                                    <span class="bank-detail-val"><?php echo escape_output($settings['bank_sort_code'] ?? '20-45-77'); ?></span>
                                </div>
                                <div class="bank-detail-item">
                                    <span class="bank-detail-label">Account Number</span>
                                    <span class="bank-detail-val"><?php echo escape_output($settings['bank_account_number'] ?? '83920144'); ?></span>
                                </div>
                            </div>

                            <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 16px;">
                                Please transfer the total amount (<strong><span class="display-total">£18.98</span></strong>) using your name or payment reference below.
                            </p>

                            <form id="bank-transfer-form">
                                <div class="form-group" style="margin-bottom: 20px;">
                                    <label class="form-label" for="payment_reference">Payment Reference Number *</label>
                                    <input type="text" id="payment_reference" class="form-control" placeholder="Enter transaction reference or your name">
                                </div>

                                <button type="submit" class="btn-saffron" id="btn-submit-bank" style="width: 100%;">
                                    Submit Bank Transfer Booking
                                </button>
                            </form>
                        </div>

                        <!-- PAYPAL TAB CONTENT -->
                        <div class="payment-tab-content" id="paypal-tab">
                            <h5 style="color: var(--color-maroon); margin-bottom: 12px;">Pay Securely with PayPal</h5>
                            <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-bottom: 20px;">
                                Complete your payment instantly using credit/debit card or your PayPal account.
                            </p>

                            <div id="paypal-button-container"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ SECTION -->
    <section class="faq-section" id="faq-section">
        <div class="container">
            <div class="section-header text-center">
                <span class="section-subtitle-tag">Got Questions?</span>
                <h2 class="section-title">Frequently Asked Questions</h2>
                <p class="section-desc">Find answers to common questions about your Ganesh Statue booking.</p>
            </div>

            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <span>How much does the Ganesh Statue cost?</span>
                        <div class="faq-toggle-icon">↓</div>
                    </div>
                    <div class="faq-answer">
                        The Ganesh Statue costs £14.99 plus applicable shipping charges (£3.99 flat delivery anywhere in the UK).
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Where do you deliver?</span>
                        <div class="faq-toggle-icon">↓</div>
                    </div>
                    <div class="faq-answer">
                        Currently, this booking service is available for delivery within the United Kingdom only.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>How can I pay?</span>
                        <div class="faq-toggle-icon">↓</div>
                    </div>
                    <div class="faq-answer">
                        Customers can pay through PayPal or Bank Transfer.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>What happens after booking?</span>
                        <div class="faq-toggle-icon">↓</div>
                    </div>
                    <div class="faq-answer">
                        A unique VK Logistics booking reference number will be generated after your booking is submitted successfully.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>How do I know my PayPal payment was successful?</span>
                        <div class="faq-toggle-icon">↓</div>
                    </div>
                    <div class="faq-answer">
                        The website will verify the payment and display the confirmed payment status with your booking details.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>What happens with Bank Transfer bookings?</span>
                        <div class="faq-toggle-icon">↓</div>
                    </div>
                    <div class="faq-answer">
                        Bank transfer bookings remain under payment verification until VK Logistics confirms the received payment.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h4>VK Logistics</h4>
                    <p>
                        Ganesh Statue Booking Service in the United Kingdom. Delivering devotion and festive joy to your doorstep.
                    </p>
                </div>

                <div>
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#hero">Home</a></li>
                        <li><a href="#product-section">Ganesh Statue</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#booking-section">Book Now</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="footer-title">Help & Info</h5>
                    <ul class="footer-links">
                        <li><a href="#faq-section">FAQ</a></li>
                        <li><a href="#trust-section">UK Delivery</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="footer-title">Contact Us</h5>
                    <p style="font-size: 0.9rem; margin-bottom: 8px;">📞 <?php echo escape_output($settings['support_phone'] ?? '+44 7700 900888'); ?></p>
                    <p style="font-size: 0.9rem;">✉️ <?php echo escape_output($settings['support_email'] ?? 'bappa@vklogistics.co.uk'); ?></p>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; 2026 VK Logistics. All Rights Reserved. | Ganesh Chaturthi UK Delivery
            </div>
        </div>
    </footer>

    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/paypal-integration.js"></script>
</body>
</html>
