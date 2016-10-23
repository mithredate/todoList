**Todo List RESTful API with Laravel 5.3**
=======
----------
After cloning the repository, please use Laravel migration with the following commands at your terminal in project's root directory:

    php artisan migrate
    php artisan migrate --path=/app/Modules/TodoList/migrations


----------
You can start using the web service from the following url:

    /api/v1/list

There's currently no content negotiation so using `Accept: application/json` is not mandatory.

The response JSON is formatted using `application/vnd.collection+json` standard. You can read more about it's syntax at [Mike Amundsen website](http://amundsen.com/media-types/collection/format/).
----------

To run the tests use the following command at your terminal at the project root directory:

    phpunit

----------

You can use `php artisan serve` at project's root folder and access the site at `http://localhost:8000`. The API is then accessible through the following url:

    http://localhost:8000/api/v1/list

The authentication is based on `auth:api` guard and you have to send a `token_api` param along with the request or attach the following header:

    Authorization: Bearer $api_token



