<template>
  <q-resource-show v-bind="$attrs">
    <template slot="show" slot-scope="scope">
      <q-text :resource="scope.resource" :attribute="scope.config.getAttribute('name')" />
    </template>
    <template slot='actions' slot-scope="scope">
      <edit :resource="scope.resource" v-bind="$attrs"/>
    </template>
  </q-resource-show>
</template>

<script>

import Edit from './edit'

export default {
  components: {
    Edit
  }
}
</script>
