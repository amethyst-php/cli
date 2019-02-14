<template>
  <q-page-index v-bind="$attrs">
    <template slot='body' slot-scope="scope">
       <index v-bind="$attrs"/>
    </template>
  </q-page-index>
</template>

<script>

import Index from '../Resource/index'

export default {
  components: {
    Index
  }
}
</script>
