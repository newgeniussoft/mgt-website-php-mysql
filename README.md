# MGT Website PHP/MySQL

This is a simple website built with PHP and MySQL database. It is designed to be a simple and easy to use website for a small business.
<img src="screenshots/2025-10-02 171424.png" alt="Home">
## Features

* Simple and easy to use
* Responsive design
* Support for multiple languages
* Includes a contact form
* Includes a blog
* Includes a gallery

## Installation

1. Clone the repository
2. Install the dependencies with `composer install`
3. Create a database and import the `mgt_website.sql` file
4. Configure the database connection in the `app/config/database.php` file
5. Run the website with `php -S localhost:8000`


<nav>
    <ul>
        <li><a href="{{ route('') }}">Home</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ switchTo('en') }}">English</a></li>
        <li><a href="{{ switchTo('es') }}">Spanish</a></li>
    </ul>
</nav>