## Attributes

List of all attributes

| Name | Fillable | Required | Unique | Default | Comment |
|------|----------|----------|--------|---------|---------|
{% for attribute in data.manager.getAttributes() %}{% set default = attribute.getDefault(data.entity)|json_encode %}| {{ attribute.getName() }} | {{ attribute.getFillable() ? "Yes" : "No" }} | {{ attribute.getRequired() ? "Yes" : "No" }} | {{ attribute.getUnique() ? "Yes" : "No" }} | {{ default ? default : "/" }} | {{ attribute.getComment() | raw }}
{% endfor %}

---
[Back](index.md)