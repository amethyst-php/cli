<template>
  <div>
    <router-view :config="config"></router-view>
  </div>
</template>
<script>

import {{ EntityName|kebab }} from '../../app/Managers/{{ EntityName|classify }}Manager'

export default {
  data () {
    return {
      {{ EntityName|kebab }}: {{ EntityName|kebab }}
    }
  }
}
</script>
