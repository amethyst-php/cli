const routes = [{
  path: '{{ EntityName|kebab|pluralize }}',
  component: () => import('./../components/{{ EntityName|kebab }}/app'),
  children: [
    {
      path: '',
      name: '{{ EntityName|kebab|pluralize }}.index',
      component: () => import('./../components/{{ EntityName|kebab }}/Page/index')
    },
    {
      path: ':id',
      name: '{{ EntityName|kebab|pluralize }}.show',
      component: () => import('./../components/{{ EntityName|kebab }}/Page/show')
    }
  ]
}]
module.exports = routes;