<template>
  <q-resource-edit v-bind="$attrs">
    <template slot='edit' slot-scope="scope">
      <q-text v-model="scope.resource" :attribute="scope.config.getAttribute('name')" :errors="scope.errors"/>
    </template>
  </q-resource-edit>
</template>