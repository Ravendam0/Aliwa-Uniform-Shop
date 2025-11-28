# Aliwa Uniform Shop

**Aliwa Uniform Shop** is a web-based platform that allows users to **buy school uniforms online**. The platform features a robust **admin panel** where administrators can upload products, add descriptions, set prices, and manage inventory efficiently.

Live demo: [aliwashop.ci.co.ke](https://aliwashop.ci.co.ke)

---

## Features

### User Features

* Browse and purchase school uniforms online.
* View product descriptions, prices, and images.
* Secure user registration and login.

### Admin Features

* Upload new uniform products with descriptions and pricing.
* Edit or remove existing products.
* Manage inventory efficiently.
* Monitor orders and customer activity.

---

## Technology Stack

* **Backend:** PHP (handles user authentication, product management, and order processing)
* **Database:** MySQL (stores users, products, and order information)
* **Frontend:** HTML, CSS, JavaScript (responsive design for all devices)
* **Server:** Apache/Nginx or any PHP-compatible hosting

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/Ravendam0/Aliwa-Uniform-Shop.git
```

2. Create a MySQL database, e.g., `aliwa_shop`, and import the provided SQL file .

3. Configure `config.php` with your database credentials:

```php
$DB_host = "localhost";
$DB_user = "your_db_username";
$DB_pass = "your_db_password";
$db = "aliwa_shop";
```

4. Upload the files to your PHP-enabled server.

5. Access the website via your browser:

```
http://yourdomain.com/
```

6. Access the admin panel to upload products:

```
http://yourdomain.com/admin
```

---

## Usage

* **For Customers:** Browse the shop, select uniforms, and place orders.
* **For Admins:** Log in via the admin panel, manage products, update inventory, and track orders.

---

## Project Structure

```
/Aliwa-Uniform-Shop
│
├── index.php           # Home/login page
├── register.php        # User registration page
├── dashboard.php       # User dashboard
├── admin/              # Admin panel folder
│   ├── index.php       # Admin login/dashboard
│   └── manage_products.php
├── config.php          # Database connection
├── assets/             # CSS, JS, images
└── user_site.php       # Dynamic user link for packages (if applicable)
```

---

## Future Enhancements

* Integrate **payment gateways** for online transactions.
* Add **order notifications** and email confirmations.
* Improve **UI/UX** with modern frameworks like Bootstrap or TailwindCSS.
* Add **analytics** for admin to track sales and popular products.

---

## License

This project is open-source and free to use for learning or development purposes.

---

## Contact

For any issues, feature requests, or contributions, contact **Ravendam0** via GitHub.
