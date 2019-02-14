import { {{ EntityName|classify }}Api } from '../Api/{{ EntityName|classify }}Api'
import { Attributes, Manager } from '@railken/quartz-core'

export default new Manager({
  name: '{{ EntityName|kebab }}',
  route: '{{ EntityName|kebab|pluralize }}',
  manager: new {{ EntityName|classify }}Api(),
  icon: require('../../assets/{{ EntityName|kebab }}-icon.svg'),
  attributes: [
    new Attributes.Id(),
    new Attributes.Base('name'),
    new Attributes.DateTime('created_at'),
    new Attributes.DateTime('updated_at')
  ]
})
