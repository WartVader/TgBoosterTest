<template>
  <div class="card mt-4">
    <div class="card-header">
      Список объявлений
    </div>
    <div class="card-body">
      <table class="table">
        <thead>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Бюджет</th>
          <th>CPM</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="ad in ads" :key="ad.id">
          <td>{{ ad.id }}</td>
          <td>{{ ad.name }}</td>
          <td>{{ ad.budget }}</td>
          <td>{{ ad.cpm }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      ads: [
        { id: 1, name: 'Реклама 1', budget: 100, cpm: 5 },
        { id: 2, name: 'Реклама 2', budget: 200, cpm: 7 }
      ]
    }
  }
}
</script>
