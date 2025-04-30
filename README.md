# Cinema CMS Semester Project

A fully functional cinema website developed with PHP, MySQL, and Tailwind CSS. It allows users to browse movies, select screenings, reserve seats, and pay online, while administrators can manage content through a custom CMS.

## Features

- UI for browsing movies, screenings, news
- Real-time seat reservation system
- Secure payment via Stripe
- Admin dashboard with CRUD operations
- Guest and registered user support
- Live seat availability with real-time validation
- Session handling and CSRF protection
- Authentication & role-based access
- Object-oriented programming
- Model-View-Controller pattern

## Technologies

- **PHP**
- **MySQL**
- **Tailwind CSS**
- **Vanilla JavaScript**
- **Stripe API**

## Database Design

- 3NF
- Foreign key constraints
- SQL views
- Triggers
- Transactions (ACID)

## Security Measures

- Input sanitization and validation
- CSRF protection
- Prepared statements
- Password hashing
- Stripe API key stored in `.env` file

## Structure

- `assets/css/` – Contains `styles.css` for custom global CSS rules and Tailwind extensions
- `public/css/` – Compiled `tailwind.css` used across the website
- `public/js/` – JavaScript for interactivity (e.g. `seat-reservation.js` for live seat selection)
- `config/` – Configuration files:
  - `dbcon.php` – Database connection
  - `session.php`, `admin_session.php`, `user_session.php` – Session management
  - `functions.php` – Shared utility functions (e.g. `sanitizeInput()`)

- `src/Controllers/` – Handles business logic (e.g. `MovieController.php` for movie operations)
- `src/Models/` – Contains database models (e.g. `MovieModel.php` for movie-related queries)
- `src/Views/frontend/` – Frontend templates (e.g. `home_page.php`, `single_movie.php`)
- `src/Views/admin/` – Admin dashboard views with forms for managing movies, news, and images

- `stripe-php/` – Contains the Stripe SDK for secure payment processing
- `uploads/` – Stores media uploaded by admins (e.g. posters, news images)

## Getting Started

To run the project locally:

### Clone the repository
```bash
git clone https://github.com/noravitkai/DWP-project.git
cd DWP-project
```

### Import SQL (found in /sql folder) into your local MySQL instance

### Configure your DB credentials in config/dbcon.php

### Option A if using XAMPP or MAMP:
- Place the project in htdocs/
- Start Apache & MySQL
- Visit http://localhost/DWP-project

### Option B if not using XAMPP/MAMP:
```bash
php -S localhost:8000 -t public/
```

## Collaborators

Built by [Nóra Vitkai](https://github.com/noravitkai) and [Simon Jobbágy](https://github.com/goulashsup) for the Web Programming Backend and Databases course exam at [EASV](https://www.easv.dk/).
