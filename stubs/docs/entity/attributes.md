## Attributes

List of all attributes

| Name | Fillable | Required | Unique | Comment |
|------|----------|----------|--------|---------|
{% for attribute in data.manager.attributes %}| {{ attribute.name }} | {{ attribute.fillable ? "Yes" : "No" }} | {{ attribute.required ? "Yes" : "No" }} | {{ attribute.unique ? "Yes" : "No" }} | {{ attribute.comment | raw }}
{% endfor %}

---
[Back](index.md)