<template>
  <h2>{{ $route.params.id ? "Изменение правила #" + $route.params.id : "Создание правила"}}</h2>
  <Conditions :id="$route.params.id" />
</template>

<script>
import Conditions from '../components/rules/Conditions.vue'

export default {
  components: { Conditions }
}
</script>
