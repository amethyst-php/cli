<template>
  <q-page-show v-bind="$attrs">
    <template slot='body' slot-scope="scope">
      <show :resource="scope.resource" v-bind="$attrs"/>
    </template>
    <template slot='actions' slot-scope="scope">
      <edit :resource="scope.resource" v-bind="$attrs"/>
    </template>
  </q-page-show>
</template>

<script>

import Show from '../Resource/show'
import Edit from '../Resource/edit'

export default {
  components: {
    Show,
    Edit
  }
}
</script>