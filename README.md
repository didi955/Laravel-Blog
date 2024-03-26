# About Laravel Blog

[![Tests Status](https://github.com/didi955/Laravel-Blog/actions/workflows/tests.yml/badge.svg)](https://github.com/didi955/Laravel-Blog/actions/workflows/tests.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/didi955/laravel-blog/badge)](https://www.codefactor.io/repository/github/didi955/laravel-blog)
[![codecov](https://codecov.io/gh/didi955/Laravel-Blog/branch/master/graph/badge.svg?token=2S5I3NLZDO)](https://codecov.io/gh/didi955/Laravel-Blog)

[Laravel Blog](https://blog.dylan-lannuzel.fr) is a project for learning the Laravel framework based on Jeffrey Way's web application from the [Laracasts](https://laracasts.com) series, [Laravel From Scratch](https://laravelfromscratch.com).
I have developed this application in parallel to this series.

## New features

At the end of the series, I decided to push the project further by implementing new features that were useful and made sense for this project.
Here are the major new features :

- Scheduled Posts
- Draft Post
- Customizable Profile (avatar & personal info)
- WYSIWYG HTML editor for the post's content
- Administration features (users & categories management)
- Multiple queued notifications & events
- Bookmarks system
- Some security system (password recovery & email verification)

### Further Ideas

- Messages & timezones user's localization
- Allow registered users to "follow" certain authors. When they publish a new post, an email should be delivered to all followers.
- Ban system
- Mobile friendly

## Warning

I am aware that the development is not perfect and still has some bugs that may hinder the user's functional experience.
That's why the contribution is useful for me because for now, i paused the development of this project.

## Prerequisites

* PHP 8.2+
* Composer
* NPM

## Installation

1. Clone the repo
   ```sh
   git clone https://github.com/didi955/Laravel-Blog.git
   ```
2. Install Composer packages
   ```sh
   composer install
   ```
4. Install NPM packages
   ```sh
   npm install
   ```
5. Copy env file
   ```sh
   cp .env.example .env
   ```
6. Configure env file
7. Generate Key
   ```sh
   php artisan key:generate
   ```
8. Run migration
   ```sh
   php artisan migrate
   ```
9. Build assets
   ```sh
   npm run build / npm run dev
   ```
10. Start dev server
   ```sh
   php artisan serve
   ```
11. Run queue workers (dev)
   ```sh
   php artisan queue:work --queue=listeners,notifications,default
   ```
12. Setup cron-job every minutes for run posts:publish command
    
13. Go to your web browser
    like : http//127.0.0.1:8080


## Contributing

Thank you for considering contributing to this project.

