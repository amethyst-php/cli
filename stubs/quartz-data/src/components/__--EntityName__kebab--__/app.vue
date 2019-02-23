<template>
  <div>
    <router-view :config="config"></router-view>
  </div>
</template>
<script>

import {{ EntityName|classify }} from '../../app/Managers/{{ EntityName|classify }}Manager'

export default {
  data () {
    return {
      config: {{ EntityName|classify }}
    }
  }
}
</script>
