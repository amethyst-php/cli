# Documentation

- [Installation](installation.md)
- Data
{% for entity in data %}
    - [{{ entity.manager.getName() }}](data/{{ entity.manager.getName() }}/index.md)
{% endfor %}