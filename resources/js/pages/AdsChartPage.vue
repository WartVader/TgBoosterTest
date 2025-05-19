<template>
  <AdsChart :api="apiUrl + $route.params.id" :with-date-filter="true"/>
</template>

<script>
import AdsChart from '../components/AdsChart.vue';
export default {
  components: {
    AdsChart
  },
  data () {
    return {
      apiUrl: '/api/ads-chart/',
    }
  },
}
</script>
