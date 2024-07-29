# E-Commerce

## Description

This project is an e-commerce application built using Laravel framework. It provides a platform for users to browse and purchase products online. The application includes features such as user authentication, product listing, shopping cart functionality, and order management and more.

## Technology Used

This project utilizes the following technologies:

-   Laravel framework
-   PHP
-   MySQL database

## Design Patterns

The project follows the following design patterns:

-   Model-View-Controller (MVC) pattern for organizing code and separating concerns
-   Repository pattern for abstracting data access and providing a consistent interface
-   Service pattern for encapsulating business logic and promoting reusability

## Tricks and Tips

Here are some tricks and tips for working with this project:

-   Utilize Laravel's built-in authentication system for user management
-   Leverage Laravel's Eloquent ORM for database interactions
-   Take advantage of Laravel's routing system to define API endpoints
-   Use Laravel's migration feature to manage database schema changes
-   Implement form validation using Laravel's validation rules
-   Make use of Laravel's artisan command-line tool for various tasks
-   Follow Laravel's naming conventions and best practices for better code organization and maintainability

## Installation

1. Clone the repository: `git clone https://github.com/MostafaEbrahim212/e-commerce-api.git`
2. Navigate to the project directory: `cd e-commerce`
3. Install dependencies: `composer install`
4. Create a copy of the `.env.example` file and rename it to `.env`
5. Generate an application key: `php artisan key:generate`
6. Configure the database connection in the `.env` file
7. Run database migrations: `php artisan migrate`
8. Start the development server: `php artisan serve`

## Usage

1. Open your web browser and navigate to `http://localhost:8000`
2. Register a new user account or log in with an existing account
3. Browse the available products and add them to your cart
4. Proceed to checkout and complete the order
5. Manage orders and products through the admin panel

## Endpoints

### User Endpoints

-   `POST /api/register` - Register a new user
-   `POST /api/login` - Log in with existing user credentials
-   `POST /api/logout` - Log out the currently authenticated user

### Admin Endpoints

-   `POST /api/admin/login` - Log in with existing admin credentials
-   `POST /api/admin/logout` - Log out the currently authenticated admin

-   `GET /api/admin/users` - Get all users (admin only)
-   `GET /api/admin/users/{id}` - Get a specific user by ID (admin only)
-   `POST /api/admin/users` - Create a new user (admin only)
-   `PUT /api/admin/users/{id}` - Update a user by ID (admin only)
-   `DELETE /api/admin/users/{id}` - Delete a user by ID (admin only)

-   `GET /api/admin/products` - Get all products (admin only)
-   `GET /api/admin/products/{id}` - Get a specific product by ID (admin only)
-   `POST /api/admin/products` - Create a new product (admin only)
-   `PUT /api/admin/products/{id}` - Update a product by ID (admin only)
-   `DELETE /api/admin/products/{id}` - Delete a product by ID (admin only)

-   `GET /api/admin/orders` - Get all orders (admin only)
-   `GET /api/admin/orders/{id}` - Get a specific order by ID (admin only)
-   `POST /api/admin/orders` - Create a new order (admin only)
-   `PUT /api/admin/orders/{id}` - Update an order by ID (admin only)
-   `DELETE /api/admin/orders/{id}` - Delete an order by ID (admin only)
