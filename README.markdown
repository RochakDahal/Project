# Mobile Store eCommerce Website

## Overview
This is a minimalist eCommerce website for selling mobile devices, built using HTML, CSS, JavaScript, PHP, and MySQL. It features complete CRUD operations for user and product management with two distinct roles: **admin** and **regular users**. The admin can manage users, products, and orders through a dedicated dashboard, while regular users can register, log in, browse products, and purchase using eSewa or Cash on Delivery (COD). The frontend is clean, without a search bar, and includes client-side form validation for a smooth user experience. The project uses a MySQL database with seven entities (admin, user, product, order, payment, cart, review) and separates admin and user components with shared assets for consistent styling.

## Features
- **Admin Role**:
  - Login with plain-text credentials (Username: `rochak`, Password: `123`) via a checkbox-enabled form.
  - Access to an admin dashboard (`admin/dashboard.php`) with management pages for users, products, orders, and logout.
  - Full CRUD operations for products (add, edit, delete) and users (add, delete).
  - Update order statuses (pending, shipped, confirmed, delivered).
- **User Role**:
  - Secure registration with hashed passwords.
  - Login using username or email.
  - Browse 6 mobile products with images, descriptions, and prices in Nepalese Rupees (NRS).
  - Purchase products via eSewa (test Merchant ID: `EPAYTEST`) or COD.
  - View order history in profile (`profile.php`).
- **Database**:
  - Seven entities: `admin`, `user`, `product`, `order`, `payment`, `cart`, `review`.
  - Efficient schema with foreign key constraints.
- **Frontend**:
  - Clean design with no search bar.
  - Responsive CSS (`assets/css/style.css`, `assets/css/admin.css`).
  - Client-side validation using JavaScript (`assets/js/script.js`).
- **Backend**:
  - PHP with PDO for secure database interactions.
  - eSewa payment integration for test environment.
  - Session-based authentication for admin and user roles.
- **Assets**:
  - Placeholder images for 6 mobile devices (`phone1.jpg` to `phone6.jpg`) in `assets/images/`.

## Prerequisites
- **XAMPP**: Apache and MySQL modules enabled.
- **PHP**: Version 7.4 or higher (included in XAMPP).
- **MySQL**: Included in XAMPP.
- **Web Browser**: Chrome, Firefox, or any modern browser.
- **eSewa Test Credentials** (for payment testing):
  - eSewa ID: `9806800001`
  - Password: `Nepal@123`
  - MPIN: `1122`

## Setup Instructions
1. **Clone or Create Project Folder**:
   - Place the project in `C:\xampp\htdocs\project`.
   - Ensure the folder structure matches the one below.

2. **Set Up Database**:
   - Start XAMPP and enable Apache and MySQL.
   - Open `http://localhost/phpmyadmin`.
   - Create a database named `ecommerce`.
   - Import `schema.sql` from the `project/` folder to set up tables and sample data.
     ```sql
     CREATE DATABASE ecommerce;
     ```
     Then, in phpMyAdmin, go to the `ecommerce` database, select the SQL tab, and paste the contents of `schema.sql`.

3. **Add Placeholder Images**:
   - Place six mobile device images (`phone1.jpg` to `phone6.jpg`) in `C:\xampp\htdocs\project\assets\images`.
   - Suggested images (source from royalty-free sites like Unsplash or Pexels):
     - `phone1.jpg`: Samsung Galaxy S23
     - `phone2.jpg`: iPhone 14 Pro
     - `phone3.jpg`: Google Pixel 7
     - `phone4.jpg`: OnePlus 11
     - `phone5.jpg`: Xiaomi 13
     - `phone6.jpg`: Oppo Find X5
   - Recommended size: 300x300 pixels.

4. **Configure Project**:
   - Ensure `includes/config.php` has the correct `BASE_URL`:
     ```php
     define('BASE_URL', 'http://localhost/project/');
     define('ASSETS_URL', BASE_URL . 'assets/');
     ```
   - Verify file permissions for `assets/images/` (must be writable for product image uploads).

5. **Start XAMPP**:
   - Launch Apache and MySQL from the XAMPP Control Panel.
   - Access the site at `http://localhost/project/index.php`.

## File Structure
```
project/
├── admin/
│   ├── dashboard.php       # Admin dashboard
│   ├── users.php           # Manage users (CRUD)
│   ├── products.php        # Manage products (CRUD with edit button)
│   ├── orders.php          # Manage orders (update status: pending, shipped, confirmed, delivered)
│   ├── logout.php          # Admin logout
├── assets/
│   ├── css/
│   │   ├── style.css       # Frontend styling
│   │   ├── admin.css       # Admin panel styling
│   ├── js/
│   │   ├── script.js       # Client-side validation
│   ├── images/
│   │   ├── phone1.jpg      # Placeholder image
│   │   ├── phone2.jpg
│   │   ├── phone3.jpg
│   │   ├── phone4.jpg
│   │   ├── phone5.jpg
│   │   ├── phone6.jpg
├── includes/
│   ├── config.php          # Configuration (BASE_URL, ASSETS_URL)
│   ├── db_connect.php      # Database connection
│   ├── functions.php       # Helper functions (isLoggedIn, isAdmin, etc.)
│   ├── esewa_config.php    # eSewa API settings
├── index.php               # Homepage with 6 mobile products
├── login.php               # Login page (admin/user)
├── register.php            # User registration
├── profile.php             # User profile with order history
├── checkout.php            # Checkout with eSewa/COD
├── payment_verify.php      # eSewa payment verification
├── logout.php              # User logout
├── schema.sql              # Database schema
├── README.md               # This file
```

## Usage
1. **Access the Site**:
   - Open `http://localhost/project/index.php` to view the homepage with 6 mobile products.

2. **Admin Access**:
   - Go to `http://localhost/project/login.php`.
   - Enter Username: `rochak`, Password: `123`, check the "Admin" checkbox, and log in.
   - Access the dashboard (`admin/dashboard.php`) to manage users, products, and orders.

3. **User Access**:
   - Register at `http://localhost/project/register.php` with a username, email, and password.
   - Log in at `http://localhost/project/login.php` using username or email.
   - Browse products, add to cart, and proceed to checkout with eSewa or COD.
   - View orders in `profile.php`.

4. **eSewa Testing**:
   - Select eSewa at checkout to test payments in the sandbox environment.
   - Use test credentials (eSewa ID: `9806800001`, Password: `Nepal@123`, MPIN: `1122`).
   - No external downloads are required for eSewa integration.

## eSewa Integration
- **Merchant ID**: `EPAYTEST` (test environment).
- **Secret Key**: `8gBm/:&EnhH.1/q` (test environment).
- **API**: Uses eSewa ePay v2 form-based integration (`https://rc-epay.esewa.com.np/api/epay/main/v2/form`).
- **No Downloads Needed**: The integration relies on PHP’s `hash_hmac` for signatures, included in standard PHP installations.
- **Production**: For live deployment, obtain credentials from https://esewa.com.np and update `includes/esewa_config.php`.

## Notes
- **Database Schema**: The `schema.sql` file includes sample data for 6 mobile products and an admin user (`rochak/123`). Update as needed for production.
- **Image Uploads**: Ensure `assets/images/` is writable for product image uploads in `admin/products.php`.
- **Error Debugging**: Check `C:\xampp\php\logs\php_error_log` for PHP errors.
- **Security**: Passwords for regular users are hashed using PHP’s `password_hash`. Admin credentials are plain-text for simplicity (update for production).

## Troubleshooting
- **Images Not Displaying**: Verify `phone1.jpg` to `phone6.jpg` exist in `assets/images/`.
- **Database Errors**: Ensure the `ecommerce` database is created and `schema.sql` is imported.
- **eSewa Redirect Issues**: Check `includes/esewa_config.php` for correct URLs and test credentials.
- **Session Issues**: Clear browser cache or test in incognito mode.


## ScreenShots

## Homepage
![Image](https://github.com/user-attachments/assets/9dedd651-3a38-461e-bdf2-3bdfd2fa05e4)

## Product list(Backend)
![Image](https://github.com/user-attachments/assets/a7548dba-02d2-455d-924c-331e8cf22aa6)

## Register Page
![Image](https://github.com/user-attachments/assets/ab64aea6-c66c-42e6-96aa-ff737aef58aa)

## Login Page
![Image](https://github.com/user-attachments/assets/f286ec20-311c-4a02-a7ce-25dbcbbeb84f)

## HomePage
![Image](https://github.com/user-attachments/assets/d3a4741e-e976-4f2c-99b9-64dd2d60332a)

## Checkout
![Image](https://github.com/user-attachments/assets/5f3ca80b-7cc8-4404-be4d-56b6dc6e5a5c)

## Checkout via esewa
![Image](https://github.com/user-attachments/assets/de1c42f9-a44f-4d06-9a57-6a449306f4ae)

## esewa for payment
![Image](https://github.com/user-attachments/assets/5865f061-341f-4e97-aa22-edeffea0e486)


## License
This project is for educational purposes and not licensed for commercial use. eSewa integration requires live credentials for production.