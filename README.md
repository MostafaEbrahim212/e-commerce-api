# E-Commerce 🛒

## Description

Welcome to the **E-Commerce Platform**! This Laravel-based application offers a comprehensive solution for online shopping, allowing users to browse, purchase products, and manage orders seamlessly. Admins have full control over products, orders, and users, making this platform robust and scalable.

## Technology Stack 💻

-   **Laravel Framework**: A powerful PHP framework for web application development.
-   **PHP**: Server-side scripting language.
-   **MySQL**: Relational database management system.

## Design Patterns 🏗️

Our project adheres to well-established design patterns to ensure clean and maintainable code:

-   **Model-View-Controller (MVC)**: Separates concerns to streamline development.
-   **Repository Pattern**: Abstracts data access to provide a consistent interface.
-   **Service Pattern**: Encapsulates business logic, enhancing reusability and organization.

## Tips and Best Practices 🔧

-   **Authentication**: Utilize Laravel’s built-in authentication system for user management.
-   **Database Operations**: Leverage Laravel's Eloquent ORM for smooth database interactions.
-   **Routing**: Define API endpoints clearly using Laravel's routing system.
-   **Migrations**: Manage database schema changes with Laravel's migration feature.
-   **Validation**: Implement robust form validation using Laravel's validation rules.
-   **Artisan Commands**: Use Laravel's artisan tool for various development tasks.
-   **Naming Conventions**: Follow Laravel's conventions for better code organization and readability.

## Installation Guide 🚀

1. **Clone the Repository**: `git clone https://github.com/MostafaEbrahim212/e-commerce-api.git`
2. **Navigate to the Project Directory**: `cd e-commerce`
3. **Install Dependencies**: `composer install`
4. **Create Environment File**: Copy `.env.example` to `.env`
5. **Generate Application Key**: `php artisan key:generate`
6. **Configure Database**: Update the database settings in the `.env` file.
7. **Run Migrations**: `php artisan migrate`
8. **Start Development Server**: `php artisan serve`

## Usage 🛠️

1. **Access the Platform**: Open your browser and go to `http://localhost:8000`
2. **User Registration/Login**: Register a new account or log in with existing credentials.
3. **Browse Products**: Explore and add products to your cart.
4. **Checkout**: Complete your purchase and place an order.
5. **Admin Management**: Manage products, orders, and users via the admin panel.

## API Endpoints 🔍

### User Endpoints

-   **Register User**: `POST /api/register` - Create a new user account.
-   **Login**: `POST /api/login` - Authenticate and log in a user.
-   **Logout**: `POST /api/logout` - Log out the currently authenticated user.

> **Note:** Additional user-related endpoints, such as password reset and profile update, and more will be added soon. Stay tuned for updates! 🚀

### Admin Endpoints

-   **Admin Login**: `POST /api/admin/login` - Authenticate an admin user.
-   **Admin Logout**: `POST /api/admin/logout` - Log out the currently authenticated admin.

-   **Users Management**:

    -   `GET /api/admin/users` - List all users.
    -   `GET /api/admin/users/{id}` - Retrieve a specific user by ID.
    -   `POST /api/admin/users/{id}/toggle-status` - Block or unblock a user.

-   **Products Management**:

    -   `GET /api/admin/products` - List all products.
    -   `GET /api/admin/products/{id}` - Retrieve a specific product by ID.
    -   `POST /api/admin/products` - Create a new product.
    -   `PUT /api/admin/products/{id}` - Update a product by ID.
    -   `DELETE /api/admin/products/{id}` - Remove a product by ID.

-   **Categories Management**:

    -   `GET /api/admin/categories` - List all categories.
    -   `GET /api/admin/categories/{id}` - Retrieve a specific category by ID.
    -   `GET /api/admin/categories/{id}/products` - List products in a category.
    -   `POST /api/admin/categories` - Create a new category.
    -   `PUT /api/admin/categories/{id}` - Update a category by ID.
    -   `DELETE /api/admin/categories/{id}` - Remove a category by ID.

-   **Orders Management**:
    -   `GET /api/admin/orders` - List all orders.
    -   `GET /api/admin/orders/{id}` - Retrieve a specific order by ID.
    -   `POST /api/admin/orders/{id}/accept` - Accept an order.
    -   `POST /api/admin/orders/{id}/reject` - Reject an order.

### Localization 🌐

Localization support is in progress. We will add multiple language support soon to enhance user experience for diverse audiences.
