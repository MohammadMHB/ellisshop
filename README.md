## 🌐 Live Website

The project is live at: **[https://ellisshop.ir](https://ellisshop.ir)**

# Ellis Shop 📚 

Ellis Shop is a fully functional e-commerce website built with WordPress and WooCommerce. It allows users to purchase both **physical books** (delivered by mail) and **digital books** (instant download after purchase). The site includes a complete user authentication system, shopping cart, secure checkout, and order management features.

## 🚀 Features

- **User Authentication** – Registration, login, password reset, and profile management
- **Dual Product Types** – Sell physical books (with shipping) and digital books (with file download)
- **Shopping Cart** – Add/remove items, update quantities, apply coupon codes
- **Secure Checkout** – Multiple payment gateways and order summary
- **Order Management** – Users can view order history and download digital products
- **Responsive Design** – Works on desktop, tablet, and mobile devices
- **Admin Dashboard** – Manage products, orders, inventory, and user roles
- **Search & Filters** – Search by title, author, category, or price range
- **Product Reviews** – Rate and review purchased books
- **Email Notifications** – Automatic order confirmation and download links

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| WordPress  | CMS / Core framework |
| WooCommerce | E-commerce functionality |
| PHP         | Backend logic |
| MySQL       | Database |
| HTML5 / CSS3| Frontend structure & styling |
| JavaScript / jQuery | Interactive elements |
| WooCommerce Digital Products | Manage downloadable books |

## 📦 What's Included in This Repository

This repository contains only the **custom theme files** and **custom code** written for Ellis Shop. The following are **excluded** (see `.gitignore`):

- WordPress core files (`wp-admin/`, `wp-includes/`)
- Uploaded media files (`wp-content/uploads/`)
- Third-party plugins (install via WordPress admin)
- `wp-config.php` (database credentials)
- Log files and cache directories

## 📁 Folder Structure

```
ellisshop-theme/
├── style.css
├── functions.php
├── index.php
├── header.php
├── footer.php
├── sidebar.php
├── single.php
├── page.php
├── archive.php
├── woocommerce/
│   ├── single-product.php
│   ├── archive-product.php
│   └── cart/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
└── README.md
```

## 🔒 Environment Variables (Security)

Do **not** commit sensitive data. Use `wp-config.php` locally or set environment variables on your live server:

```php
define('DB_NAME', 'your_db_name');
define('DB_USER', 'your_db_user');
define('DB_PASSWORD', 'your_db_password');
define('AUTH_KEY', 'your_unique_key');
```

For production, use your hosting provider's secret manager or a plugin like **WP Encrypt**.

## 🧪 Testing Checklist

- [ ] User can register and log in
- [ ] User can add physical book to cart and proceed to checkout
- [ ] User can add digital book to cart and receives download link after payment
- [ ] Admin can view and update orders
- [ ] Coupon codes apply correctly
- [ ] Email notifications are sent
- [ ] Responsive layout on mobile devices
- [ ] Search filters work correctly

## 📄 License

This project is for personal or commercial use. All custom code is proprietary unless otherwise stated.

## 🤝 Contributing

This is a private project. For suggestions or bug reports, please contact the repository owner directly.

## 📧 Contact

For questions about this project:  
**Ellis Shop Support** – [mohebian80@gmail.com](mailto:mohebian80@gmail.com)

---

⭐ *If you find this project useful for learning purposes, feel free to star this repository!*
