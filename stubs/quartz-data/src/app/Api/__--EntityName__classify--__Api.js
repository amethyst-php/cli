import { ResourceApi } from '@railken/quartz-core'

export class {{ EntityName|classify }}Api extends ResourceApi {
    resource_url = '/admin/{{ EntityName|kebab|pluralize }}';
};
