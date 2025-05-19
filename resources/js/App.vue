<template>
  <div class="app-container">
    <NavBar />
    <main class="container">
      <router-view v-slot="{ Component, route }">
        <div :key="route.name">
          <Component :is="Component" />
        </div>
      </router-view>
    </main>
  </div>
</template>

<script>
import NavBar from './components/NavBar.vue'

export default {
  components: { NavBar }
}
</script>

<style>
body {
  font-family: sans-serif;
}
table {
  border-collapse: collapse;
}
a {
  color: #3b82f6;
  text-decoration: none;
}
.container {
  max-width: 1170px;
  width: 100%;
  padding: 0 1rem;
  margin: 0 auto;
}
input, select {
  box-sizing: border-box;
  outline-color: #3b82f6;
}
</style>
