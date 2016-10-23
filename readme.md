**Todo List RESTful API with Laravel 5.3**
=======
----------
After cloning the repository, please use Laravel migration with the following commands at your terminal in project's root directory:

    php artisan migrate
    php artisan migrate --path=/app/Modules/TodoList/migrations


----------
You can start using the web service from the following url:

    /api/v1/list

The response JSON is formatted using `application/vnd.collection+json` standard. You can read more about it's syntax at [Mike Amundsen website](http://amundsen.com/media-types/collection/format/).
----------

To run the tests use the following command at your terminal at the project root directory:

    phpunit

----------

