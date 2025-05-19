<template>
  <h2>Правила</h2>
  <DataTable
    :columns="columns"
    :api-url="apiUrl"
    :per-page="10"
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
        { key: 'id', label: 'ID', sortable: true, },
        { key: 'ad_id', label: 'ID Объявления', sortable: true,},
        { key: 'rule', label: 'Правило', sortable: false, },
        { key: 'created_at', label: 'Дата выполнения', sortable: true }
      ]),
      apiUrl: '/api/rules-log',
      loading: ref(false),
    }
  }
}
</script>
