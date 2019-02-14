import { ServiceProvider } from '@railken/quartz-core'
import { container } from '@railken/quartz-core'

export class {{ EntityName|classify }}ServiceProvider extends ServiceProvider {
  register() {

    this.addRoutes('app', require('./../../routes/{{ EntityName|kebab }}.js'))

    this.addData({
      name: '{{ EntityName|kebab }}',
      description: 'Configure your application',
      tags: ['data'],
      route: { name: '{{ EntityName|kebab|pluralize }}.index' },
    })

    this.addLang({
      'en': require('../../../lang/{{ EntityName|kebab }}/en.json'),
      'it': require('../../../lang/{{ EntityName|kebab }}/it.json')
    })
  }
}