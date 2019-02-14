<template>
  <q-page-show v-bind="$attrs">
    <template slot='body' slot-scope="scope">
      <show :resource="scope.resource" v-bind="$attrs"/>
    </template>
  </q-page-show>
</template>

<script>

import Show from '../Resource/show'

export default {
  components: {
    Show
  }
}
</script>
