### Personal Movie Database
---
A little SPA I wrote to experiment using JQuery Mobile and AngularJS together
in a solution. (I am not awfully sure that this is quite the best marriage of scripts, but 
interesting nonetheless).

The script currently makes use of the [OMDB API](http://www.omdbapi.com/).

**Note:
The app uses Facebook for Authentication, so you will need to register an app on http://developers.facebook.com**

#### Getting Started
1. In the root folder, alter config.php to reflect your mysql database settings and your Facebook API/Secret keys.
2. In the app folder run the build.bat file, this will fetch the required JavaScript dependencies
using the node package manager and minify the JavaScript/CSS resources (using gulp tasks) for use in the app.
3. In the service folder run dbscripts.sql on your mysql database.
4. The Images folder needs to be writable (used for caching IMDB images)

#### Dependencies
1. PHP 5.5
2. NodeJS 4.2.1
3. Angular 1.4.6
4. JQuery 2.1.4
5. JQueryMobile 1.4.1
6. Underscore 1.8.3
7. Gulp 3.9.0
