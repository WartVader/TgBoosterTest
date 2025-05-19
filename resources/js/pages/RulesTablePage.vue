<template>
  <h2>Правила</h2>
  <DataTable
    :columns="columns"
    :api-url="apiUrl"
    :per-page="10"
    :edit-route-name="editRouteName"
    @update:loading="loading = $event"
  />
</template>

<script>
import { ref } from 'vue';
import DataTable from '../components/DataTable.vue';

export default {
  components: { DataTable },
  data() {
    return {
      columns: ref([
        { key: 'id', label: 'ID', sortable: true },
        { key: 'rule', label: 'Правило' },
      ]),
      apiUrl: '/api/rules',
      editRouteName: 'rule-edit',
      loading: ref(false),
    }
  }
}
</script>
