# VK Logistics – Ganesh Statue Booking Website

A complete, production-ready, festive one-page Ganesh Statue booking website built for **VK Logistics** targeting customers in the **United Kingdom** for Ganesh Chaturthi.

---

## 🌟 Key Features

* **Festive UI/UX**: Designed with a theme (Deep Maroon `#4A0B17`, Warm Ivory `#FDFBF7`, Saffron `#E65100`, Gold `#D4AF37`) featuring floating Diya glows, gold glassmorphism cards, and traditional mandala accents.
* **Product & Pricing**: Ganesh Statue / Vinayaka Vigraha at **£14.99 + Shipping**.
* **Dynamic Shipping**: Retrieve shipping charges from the database `settings` table.
* **United Kingdom Delivery Restriction**: Locked to UK delivery with UK postcode validation (`SW1A 1AA`) and UK mobile validation (`+44 7...` or `07...`).
* **Live Quantity Calculator**: Dynamic item subtotal and grand total calculation.
* **Dual Payment Gateways**:
  1. **PayPal Smart Buttons Checkout**: Server-side PayPal order creation (`ajax/paypal-create-order.php`) and server-side payment verification (`ajax/paypal-verify.php`). Status set to `PAID`. Includes fallback sandbox test mode.
  2. **Direct UK Bank Transfer**: Displays configurable Sort Code, Account Number, Bank Name, and Account Holder Name from `settings` table. Captures customer payment reference number. Status set to `PAYMENT VERIFICATION PENDING`.
* **Unique Booking Reference**: Format `VKG-YYYY-XXXXXX` (e.g., `VKG-2026-000001`) generated using MySQL atomic sequence tracking.
* **Idempotent Confirmation Page (`success.php`)**: Protects against re-submitting payments or duplicating orders on page refresh. Printable receipt with copy reference button.
* **Security**: PDO prepared statements, CSRF protection token on AJAX requests, input sanitization, and output escaping (`htmlspecialchars`).

---

## 📁 Directory Architecture

```
/
├── index.php                 # Main festive landing & one-page booking interface
├── success.php               # Booking confirmation & printable receipt page
├── config/
│   ├── database.php          # PDO database connection with error handling
│   └── config.php            # Global constants, sessions & CSRF initialization
├── includes/
│   ├── security.php          # CSRF validation, input sanitization, escaping
│   ├── functions.php         # UK Postcode/Mobile regex validators & settings
│   └── booking-functions.php # Unique reference generator & calculation logic
├── ajax/
│   ├── get-settings.php      # Dynamic JSON settings API
│   ├── create-booking.php    # Server-side validation & order creation
│   ├── bank-payment.php      # Processes Bank Transfer payment reference
│   ├── paypal-create-order.php # PayPal SDK order initialization
│   └── paypal-verify.php     # Server-side PayPal payment verification
├── assets/
│   ├── css/
│   │   ├── style.css         # Complete design system & responsive rules
│   │   └── animations.css    # Diya glow, gold shimmer, checkmark drawing
│   ├── js/
│   │   ├── app.js            # Booking logic, AJAX handlers & toasts
│   │   └── paypal-integration.js # PayPal Smart Buttons wrapper
│   └── images/
│       ├── ganesh_hero.png   # Hero festive statue image
│       └── ganesh_product.png# Product card statue image
└── database/
    └── vklogistics.sql       # Database schema & initial settings seed data
```

---

## 🚀 Setup & Deployment Instructions

### 1. Database Setup
1. Open phpMyAdmin or your MySQL command line tool.
2. Import the `database/vklogistics.sql` file.
3. This creates database `vk_logistics` along with tables `bookings`, `settings`, and `booking_sequence`.

### 2. Configure Database Credentials
Edit `config/database.php` with your MySQL credentials:
```php
private static $host = 'localhost';
private static $db_name = 'vk_logistics';
private static $username = 'your_db_username';
private static $password = 'your_db_password';
```

### 3. PayPal Configuration (Optional)
To set up your live or sandbox PayPal Client ID:
1. Update `paypal_client_id` in the `settings` table via phpMyAdmin or database query:
```sql
UPDATE settings SET setting_value = 'YOUR_PAYPAL_CLIENT_ID' WHERE setting_key = 'paypal_client_id';
```

---

## 🔒 Security Best Practices
- SQL Injection protection via PDO prepared statements.
- XSS prevention via `escape_output()` HTML entities encoding.
- CSRF validation tokens on all POST/AJAX calls.
- Server-side price recalculation (client total amounts are never trusted directly).

---

## 📱 Mobile Responsiveness Tested
- Mobile S/M/L (320px, 375px, 430px)
- Tablet (768px, 1024px)
- Laptop & Desktop (1440px+)

© 2026 VK Logistics. All Rights Reserved.
