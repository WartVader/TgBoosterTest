<template>
  <div>
    <h2>Управление объявлениями</h2>
    <AdsList />
  </div>
</template>

<script>
import AdsList from '../components/ads/AdsList.vue'
//import AdsStats from '../components/ads/AdsStats.vue'

export default {
  components: { AdsList }
}
</script>
