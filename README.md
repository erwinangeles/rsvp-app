## 🎉 Event RSVP App
An easy-to-use web app that lets users create events, RSVP, and bring items like food, drinks, or supplies. Perfect for potlucks, parties, game nights, or any group gathering.

## 🏠 Event Page
See the event details and who's going/bringing what.
![Alt text](/public/images/screen.png "EventApp")

## ✍️ RSVP Form
Quickly RSVP with your name, phone number, and choose or add items to bring.
![Alt text](/public/images/screen-rsvp.png "EventApp")

## 📝 Manage Event Details
![Alt text](/public/images/screen-events.png "EventApp")
![Alt text](/public/images/screen-edit.png "EventApp")

## Features
- 📝 Create Events: Title, description, banner image, and more
- 👥 Shareable RSVP Links: Anyone with the link can RSVP
- 📱 Phone-based RSVP: Just enter your phone to confirm identity
- 🧺 Bring Items: RSVP with specific food, drinks, or supplies
- 🔁 Edit Your RSVP: Update what you're bringing anytime
- 🔒 Session-based Identity: No login required, just your phone

## 🚀 Getting Started
See below for the the detailed instructions for setup.

## Option 1: Laravel Sail (Docker)
Clone the repo

```
git clone https://github.com/erwinangeles/rsvp-app.git
```
```
cd event-rsvp-app
```
```
Copy the .env and install dependencies
```
```
cp .env.example .env
```
```
composer install
```

Start Sail (use the sail alias)
```
./vendor/bin/sail up -d
```

Generate the app key

```
./vendor/bin/sail artisan key:generate
```

Run migrations and seed sample data
bash
```
./vendor/bin/sail artisan migrate
```
Access the app

Visit: http://localhost

## Option 2: Local Dev (No Docker)
```
composer install
```
```
npm install && npm run build
```
```
cp .env.example .env
```
```
php artisan key:generate
```
```
php artisan migrate
```
```
php artisan serve
```

## 📦 Tech Stack
- Laravel + Blade
- Bootstrap 5
- jQuery
- S3 (for image uploads)