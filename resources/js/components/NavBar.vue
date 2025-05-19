<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <div class="navbar-nav">
        <router-link to="/rules" class="nav-link">Правила</router-link>
        <router-link to="/rules-table" class="nav-link">Список правил</router-link>
        <router-link to="/rules-log" class="nav-link">Логи</router-link>
        <router-link to="/ads-table" class="nav-link">Объявления</router-link>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.router-link-active {
  font-weight: bold;
}
.navbar {
  padding: 1rem 0;
}
.navbar-nav {
  display: flex;
}
.nav-link {
  margin-right: 1rem;
}
</style>
