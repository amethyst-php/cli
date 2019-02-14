import { Attributes } from '@railken/quartz-core'
import { {{ EntityName|classify }}Api } from './../Api/{{ EntityName|classify }}Api'

export class {{ EntityName|classify }}Attribute extends Attributes.BelongsTo {
  constructor (name, options) {
    super(name, new {{ EntityName|classify }}Api())

    this.resourceConfig = () => { return require('./../Managers/{{ EntityName|classify }}Manager').default };

    this.createComponent = {
      component: () => import('../../components/{{ EntityName|kebab }}/Resource/create')
    }

    this.updateComponent = {
      component: () => import('../../components/{{ EntityName|kebab }}/Resource/edit')
    }
  }
};
