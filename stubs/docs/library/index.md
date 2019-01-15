# Documentation

- [Installation](installation.md)
- Data
{% for entity in data %}
    - [{{ entity.manager.getName() }}](entity/{{ entity.manager.getName() }}/index.md)
{% endfor %}

Test with phpunit:

    ./vendor/bin/phpunit

Install the application:

    php artisan migrate
    php artisan install

Test the index endpoint

    curl -X GET http://localhost:8000/api