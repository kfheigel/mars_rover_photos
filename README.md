# Mars Rover Photos project!
### Table of contents
* [Introduction](#introduction)
* [Technologies](#technologies)
* [Setup](#setup)
* [Additional info](#additional_info)

### Introduction
Welcome to MARS ROVER PHOTOS api, that connects to NASA api, downloads data (including urls to mars rovers photos), and stores that data in database.

### Technologies
* PHP version: 7.4.3
* Symfony version: 5.2
* Docker version: 20.10.1


### Setup
1. Firstly run 
```
$ composer install  
$ composer update
```
to have all packages installed and updated

2. Setup docker containers for database purposes. Project is using mysql and phpmyadmin docker images.
```
$ docker-compose run -d
```
3. Create migration files and set the database
```
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

4. Create mockup user with a token for api calls for unit test purposes
```
$ php bin/console doctrine:fixtures:load
```

5. Insert the data to database: Holidays - it requires additional year parameter for which holidays are getting set. Images have to be set after updated holidays - those are base on the dates from Holidays table in database.
```
$ php bin/console app:refresh-holidays 2020
$ php bin/console app:refresh-images
```
#### The project is ready to run using symfony binaries!
```
$ symfony serve -d
```
### Additional info
You need to register to use this API. After the registration, you'll receive an email confirmation with YOUR_API_KEY token. Without it, you are not allowed to use the api endpoints. 
There are two endpoints, that you can use for your purposes.
The first one allows you to get every photo: by selected Mars rover, by camera that took the photo and by specific date or between two dates - this one is not mandatory
```
/get/images/api_key/YOUR_API_KEY/rover/ROVER_NAME/camera/TYPE_OF_CAMERA/SELECTED_START_DATE/SELECTED_END_DATE
```
The second one allows you to get details only for specific photo selected by photo_id
```
/get/image/api_key/YOUR_API_KEY/photo_id/PHOTO_ID
```

