## Clone the Repository

git clone https://github.com/your-repository/meeting-room-booking.git
cd meeting-room-booking

## Install Backend Dependencies

composer install
php artisan install:api

## Set Up Environment File

cp .env.example .env

Edit the .env file to configure your environment settings, including:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meeting_room_booking
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file

## Generate Application Key

php artisan key:generate

## Migrate and Seed Database

php artisan migrate
php artisan db:seed

## Install Frontend Dependencies

npm install

## Build Frontend Assets

npm run dev

## Run the Application

php artisan serve

