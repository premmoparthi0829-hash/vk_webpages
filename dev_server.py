import http.server
import socketserver
import urllib.parse
import json
import re
import os
import random

PORT = 8000
DIRECTORY = os.path.dirname(os.path.abspath(__file__))

# Global in-memory storage for local demo testing
BOOKINGS = {}
NEXT_SEQ = 1

def generate_ref():
    global NEXT_SEQ
    ref = f"VKG-2026-{NEXT_SEQ:06d}"
    NEXT_SEQ += 1
    return ref

class VKRequestHandler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=DIRECTORY, **kwargs)

    def do_GET(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path
        query = urllib.parse.parse_qs(parsed.query)

        if path in ['/', '/index.php', '/index.html']:
            self.render_index_php()
            return
        elif path == '/success.php':
            ref = query.get('ref', [''])[0]
            self.render_success_php(ref)
            return
        elif path == '/ajax/get-settings.php':
            self.send_json({
                "success": True,
                "message": "Settings loaded",
                "settings": {
                    "product_name": "Ganesh Statue / Vinayaka Vigraha",
                    "unit_price": "14.99",
                    "shipping_charge": "3.99",
                    "currency_symbol": "£",
                    "currency_code": "GBP",
                    "bank_account_name": "VK LOGISTICS LTD",
                    "bank_name": "Barclays Bank UK",
                    "bank_sort_code": "20-45-77",
                    "bank_account_number": "83920144",
                    "paypal_client_id": "sb",
                    "support_phone": "+44 7700 900888",
                    "support_email": "bappa@vklogistics.co.uk",
                    "csrf_token": "demo_token_12345"
                }
            })
            return
        
        super().do_GET()

    def do_POST(self):
        parsed = urllib.parse.urlparse(self.path)
        path = parsed.path
        content_length = int(self.headers.get('Content-Length', 0))
        post_data = self.rfile.read(content_length).decode('utf-8')
        form_data = urllib.parse.parse_qs(post_data)
        
        # Flatten form data
        data = {k: v[0] for k, v in form_data.items()}

        if path == '/ajax/create-booking.php':
            ref = generate_ref()
            qty = int(data.get('quantity', 1))
            subtotal = round(qty * 14.99, 2)
            total = round(subtotal + 3.99, 2)
            
            booking = {
                "booking_reference": ref,
                "customer_name": data.get('customer_name', 'Customer'),
                "mobile": data.get('mobile', ''),
                "email": data.get('email', ''),
                "address_line_1": data.get('address_line_1', ''),
                "address_line_2": data.get('address_line_2', ''),
                "city": data.get('city', ''),
                "county": data.get('county', ''),
                "postcode": data.get('postcode', ''),
                "country": "United Kingdom",
                "quantity": qty,
                "unit_price": 14.99,
                "subtotal": subtotal,
                "shipping_charge": 3.99,
                "total_amount": total,
                "payment_method": data.get('payment_method', 'bank_transfer'),
                "payment_reference": data.get('payment_reference', ''),
                "paypal_order_id": data.get('paypal_order_id', ''),
                "paypal_transaction_id": data.get('paypal_transaction_id', ''),
                "payment_status": "PAID" if data.get('payment_method') == 'paypal' else "PAYMENT VERIFICATION PENDING"
            }
            
            BOOKINGS[ref] = booking

            self.send_json({
                "success": True,
                "message": "Booking created successfully",
                "booking_reference": ref,
                "total_amount": f"{total:.2f}",
                "payment_method": booking["payment_method"],
                "redirect_url": f"success.php?ref={ref}"
            })
            return

        elif path == '/ajax/bank-payment.php':
            ref = data.get('booking_reference', '')
            pay_ref = data.get('payment_reference', '')
            if ref in BOOKINGS:
                BOOKINGS[ref]['payment_reference'] = pay_ref
                BOOKINGS[ref]['payment_status'] = 'PAYMENT VERIFICATION PENDING'
            
            self.send_json({
                "success": True,
                "message": "Bank transfer payment submitted",
                "booking_reference": ref,
                "redirect_url": f"success.php?ref={ref}"
            })
            return

        elif path == '/ajax/paypal-create-order.php':
            qty = int(data.get('quantity', 1))
            subtotal = round(qty * 14.99, 2)
            total = round(subtotal + 3.99, 2)

            self.send_json({
                "success": True,
                "message": "PayPal order initialized",
                "amount": {
                    "currency_code": "GBP",
                    "value": f"{total:.2f}",
                    "breakdown": {
                        "item_total": {"currency_code": "GBP", "value": f"{subtotal:.2f}"},
                        "shipping": {"currency_code": "GBP", "value": "3.99"}
                    }
                },
                "items": [{
                    "name": "Ganesh Statue / Vinayaka Vigraha",
                    "unit_amount": {"currency_code": "GBP", "value": "14.99"},
                    "quantity": str(qty)
                }]
            })
            return

        elif path == '/ajax/paypal-verify.php':
            ref = data.get('booking_reference', '')
            order_id = data.get('paypal_order_id', '')
            txn_id = data.get('paypal_transaction_id', '')

            if ref in BOOKINGS:
                BOOKINGS[ref]['paypal_order_id'] = order_id
                BOOKINGS[ref]['paypal_transaction_id'] = txn_id
                BOOKINGS[ref]['payment_status'] = 'PAID'

            self.send_json({
                "success": True,
                "message": "PayPal payment verified!",
                "booking_reference": ref,
                "payment_status": "PAID",
                "redirect_url": f"success.php?ref={ref}"
            })
            return

        self.send_error(404, "Not Found")

    def render_index_php(self):
        file_path = os.path.join(DIRECTORY, 'index.php')
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()

        # Replace PHP tags for local Python preview
        content = re.sub(r'<\?php.*?\?>', '', content, flags=re.DOTALL)
        content = content.replace("<?php echo escape_output(APP_TITLE); ?>", "Ganesh Statue / Vinayaka Vigraha Booking - UK")
        content = content.replace("<?php echo escape_output($csrf_token); ?>", "demo_token_12345")
        content = content.replace("<?php echo urlencode($paypal_client_id); ?>", "sb")
        content = content.replace("<?php echo escape_output($settings['bank_account_name'] ?? 'VK LOGISTICS LTD'); ?>", "VK LOGISTICS LTD")
        content = content.replace("<?php echo escape_output($settings['bank_name'] ?? 'Barclays Bank UK'); ?>", "Barclays Bank UK")
        content = content.replace("<?php echo escape_output($settings['bank_sort_code'] ?? '20-45-77'); ?>", "20-45-77")
        content = content.replace("<?php echo escape_output($settings['bank_account_number'] ?? '83920144'); ?>", "83920144")
        content = content.replace("<?php echo escape_output($settings['support_phone'] ?? '+44 7700 900888'); ?>", "+44 7700 900888")
        content = content.replace("<?php echo escape_output($settings['support_email'] ?? 'bappa@vklogistics.co.uk'); ?>", "bappa@vklogistics.co.uk")

        self.send_response(200)
        self.send_header('Content-Type', 'text/html; charset=utf-8')
        self.end_headers()
        self.wfile.write(content.encode('utf-8'))

    def render_success_php(self, ref):
        file_path = os.path.join(DIRECTORY, 'success.php')
        with open(file_path, 'r', encoding='utf-8') as f:
            content = f.read()

        booking = BOOKINGS.get(ref, {
            "booking_reference": ref or "VKG-2026-000001",
            "customer_name": "Rajesh Patel",
            "mobile": "+44 7700 900000",
            "email": "rajesh@example.co.uk",
            "address_line_1": "10 Downing Street",
            "address_line_2": "",
            "city": "London",
            "county": "Greater London",
            "postcode": "SW1A 2AA",
            "country": "United Kingdom",
            "quantity": 1,
            "unit_price": 14.99,
            "subtotal": 14.99,
            "shipping_charge": 3.99,
            "total_amount": 18.98,
            "payment_method": "bank_transfer",
            "payment_reference": "TRANSFER-99218",
            "payment_status": "PAYMENT VERIFICATION PENDING"
        })

        # Simple string replacements for success template
        content = re.sub(r'<\?php.*?\?>', '', content, flags=re.DOTALL)
        content = content.replace('<?php echo escape_output($booking[\'booking_reference\']); ?>', booking['booking_reference'])
        content = content.replace('<?php echo escape_output($booking[\'customer_name\']); ?>', booking['customer_name'])
        content = content.replace('<?php echo escape_output($booking[\'mobile\']); ?>', booking['mobile'])
        content = content.replace('<?php echo escape_output($booking[\'email\']); ?>', booking['email'])
        content = content.replace('<?php echo escape_output($booking[\'quantity\']); ?>', str(booking['quantity']))
        content = content.replace('<?php echo $currency . number_format($booking[\'subtotal\'], 2); ?>', f"£{booking['subtotal']:.2f}")
        content = content.replace('<?php echo $currency . number_format($booking[\'shipping_charge\'], 2); ?>', f"£{booking['shipping_charge']:.2f}")
        content = content.replace('<?php echo $currency . number_format($booking[\'total_amount\'], 2); ?>', f"£{booking['total_amount']:.2f}")
        content = content.replace('<?php echo strtoupper(str_replace(\'_\', \' \', $booking[\'payment_method\'])); ?>', booking['payment_method'].upper())
        content = content.replace('<?php echo escape_output($booking[\'payment_reference\'] ?: $booking[\'paypal_transaction_id\'] ?: \'N/A\'); ?>', booking['payment_reference'] or 'N/A')
        content = content.replace('<?php echo escape_output($booking[\'address_line_1\']); ?>', booking['address_line_1'])
        content = content.replace('<?php echo escape_output($booking[\'city\']); ?>', booking['city'])
        content = content.replace('<?php echo escape_output($booking[\'postcode\']); ?>', booking['postcode'])

        status_html = '<span style="display: inline-block; background: #E8F5E9; color: #2E7D32; padding: 4px 12px; border-radius: 4px; font-weight: 800;">✓ PAID</span>' if booking['payment_status'] == 'PAID' else '<span style="display: inline-block; background: #FFF3E0; color: #E65100; padding: 4px 12px; border-radius: 4px; font-weight: 800;">⏳ PAYMENT VERIFICATION PENDING</span>'
        content = re.sub(r'<\?php if \(\$booking\[\'payment_status\'\].*?endif; \?>', status_html, content, flags=re.DOTALL)

        self.send_response(200)
        self.send_header('Content-Type', 'text/html; charset=utf-8')
        self.end_headers()
        self.wfile.write(content.encode('utf-8'))

    def send_json(self, data):
        body = json.dumps(data).encode('utf-8')
        self.send_response(200)
        self.send_header('Content-Type', 'application/json; charset=utf-8')
        self.send_header('Content-Length', len(body))
        self.end_headers()
        self.wfile.write(body)

if __name__ == '__main__':
    print(f"Starting VK Logistics local dev server on http://localhost:{PORT}")
    with socketserver.TCPServer(("", PORT), VKRequestHandler) as httpd:
        httpd.serve_forever()
