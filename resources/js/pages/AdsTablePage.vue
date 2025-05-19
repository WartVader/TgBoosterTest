<template>
  <h2>Правила</h2>
  <DataTable
    :columns="columns"
    :api-url="apiUrl"
    :per-page="10"
    :view-route-name="viewRouteName"
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
        { key: 'budget', label: 'Бюджет', sortable: true },
        { key: 'cpm', label: 'CPM', sortable: true },
      ]),
      apiUrl: '/api/ads',
      viewRouteName: 'ad-chart',
      loading: ref(false),
    }
  }
}
</script>
