<?php
/**
 * VK Logistics - Booking Confirmation & Receipt Page
 */

require_once __DIR__ . '/includes/booking-functions.php';

$reference = sanitize_input($_GET['ref'] ?? '');
$booking = null;

if (!empty($reference)) {
    $booking = get_booking_by_ref($reference);
}

// Fallback to last session booking if GET parameter is missing
if (!$booking && isset($_SESSION['last_booking'])) {
    $booking = $_SESSION['last_booking'];
    $reference = $booking['booking_reference'];
}

// Redirect to home if no valid booking reference is present
if (!$booking) {
    header('Location: index.php');
    exit;
}

$currency = get_setting('currency_symbol', '£');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation #<?php echo escape_output($booking['booking_reference']); ?> | VK Logistics</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800&family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/animations.css">
    
    <style>
        @media print {
            .site-header, .site-footer, .no-print {
                display: none !important;
            }
            .success-card {
                box-shadow: none !important;
                border: 1px solid #000 !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body style="background: var(--bg-cream);">

    <!-- Header Section -->
    <header class="site-header no-print">
        <div class="container">
            <div class="header-inner">
                <a href="index.php" class="brand-logo">
                    <div class="brand-logo-icon">VK</div>
                    <div class="brand-logo-text">
                        <span class="brand-name">VK Logistics</span>
                        <span class="brand-tagline">Ganesh Booking UK</span>
                    </div>
                </a>

                <a href="index.php" class="btn-outline-maroon">← Back to Home</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="success-card">
            <!-- Animated Green Checkmark SVG -->
            <div class="success-icon-wrap">
                <svg viewBox="0 0 100 100" width="90" height="90">
                    <circle class="success-svg-circle" cx="50" cy="50" r="44" fill="#E8F5E9" stroke="#388E3C" stroke-width="4"/>
                    <path class="success-svg-check" d="M30 50 L44 64 L70 36" fill="none" stroke="#388E3C" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>

            <h1 style="color: var(--color-maroon); font-size: 2.4rem; margin-bottom: 8px;">Booking Successful!</h1>
            <p style="font-size: 1.1rem; color: var(--color-text-dark); opacity: 0.9;">
                Thank you for booking your Ganesh Statue with <strong>VK Logistics</strong>.
            </p>

            <!-- Prominent Reference Display Box -->
            <div class="ref-display-box">
                <span style="font-size: 0.85rem; font-weight: 700; color: var(--color-maroon); text-transform: uppercase; letter-spacing: 1px;">
                    YOUR BOOKING REFERENCE
                </span>
                <span class="ref-code" id="booking-ref-text"><?php echo escape_output($booking['booking_reference']); ?></span>
                <button type="button" class="btn-gold no-print" id="btn-copy-ref" style="padding: 6px 18px; font-size: 0.85rem;">
                    📋 Copy Reference
                </button>
            </div>

            <p style="font-size: 0.9rem; color: var(--color-saffron); font-weight: 600; margin-bottom: 24px;">
                Please save your booking reference number. You may need it when contacting VK Logistics regarding your order.
            </p>

            <!-- Full Order Breakdown Table -->
            <div style="background: var(--bg-ivory); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: 24px; text-align: left; margin-bottom: 30px;">
                <h3 style="color: var(--color-maroon); border-bottom: 2px solid var(--color-gold); padding-bottom: 10px; margin-bottom: 16px;">
                    Order & Payment Summary
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; font-size: 0.95rem;">
                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Customer Name</span>
                        <strong><?php echo escape_output($booking['customer_name']); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Booking Reference</span>
                        <strong class="text-maroon"><?php echo escape_output($booking['booking_reference']); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Mobile Number</span>
                        <strong><?php echo escape_output($booking['mobile']); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Email Address</span>
                        <strong><?php echo escape_output($booking['email']); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Product Quantity</span>
                        <strong><?php echo escape_output($booking['quantity']); ?> × Ganesh Statue</strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Product Amount</span>
                        <strong><?php echo $currency . number_format($booking['subtotal'], 2); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">UK Shipping Charge</span>
                        <strong><?php echo $currency . number_format($booking['shipping_charge'], 2); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Total Paid / Payable</span>
                        <strong class="text-saffron" style="font-size: 1.1rem;"><?php echo $currency . number_format($booking['total_amount'], 2); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Payment Method</span>
                        <strong><?php echo strtoupper(str_replace('_', ' ', $booking['payment_method'])); ?></strong>
                    </div>

                    <div>
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Payment Reference / ID</span>
                        <strong><?php echo escape_output($booking['payment_reference'] ?: $booking['paypal_transaction_id'] ?: 'N/A'); ?></strong>
                    </div>

                    <div style="grid-column: span 2;">
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Payment Status</span>
                        <?php if ($booking['payment_status'] === 'PAID'): ?>
                            <span style="display: inline-block; background: #E8F5E9; color: #2E7D32; padding: 4px 12px; border-radius: 4px; font-weight: 800;">
                                ✓ PAID
                            </span>
                        <?php else: ?>
                            <span style="display: inline-block; background: #FFF3E0; color: #E65100; padding: 4px 12px; border-radius: 4px; font-weight: 800;">
                                ⏳ PAYMENT VERIFICATION PENDING
                            </span>
                        <?php endif; ?>
                    </div>

                    <div style="grid-column: span 2; border-top: 1px solid var(--color-border); padding-top: 12px; margin-top: 6px;">
                        <span style="color: var(--color-text-muted); display: block; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Delivery Address (UK)</span>
                        <strong>
                            <?php echo escape_output($booking['address_line_1']); ?>
                            <?php if (!empty($booking['address_line_2'])) echo ', ' . escape_output($booking['address_line_2']); ?>,
                            <?php echo escape_output($booking['city']); ?>
                            <?php if (!empty($booking['county'])) echo ', ' . escape_output($booking['county']); ?>,
                            <?php echo escape_output($booking['postcode']); ?>,
                            United Kingdom 🇬🇧
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="no-print" style="display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;">
                <a href="index.php" class="btn-outline-maroon">
                    🏠 Back to Home
                </a>
                <button type="button" class="btn-saffron" onclick="window.print();">
                    🖨️ Print / Save Booking Confirmation
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript Dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>
