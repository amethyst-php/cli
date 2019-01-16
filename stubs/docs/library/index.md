# Documentation

- [Installation](installation.md)
- Data
{% for entity in data %}
    - [{{ entity.name|classify }}](data/{{ entity.name }}/index.md)
{% endfor %}