<template>
  <div class="filter" v-if="withDateFilter">
    <input type="date" class="filter__date-input" v-model="filter.date.from">
    <input type="date" class="filter__date-input" v-model="filter.date.to">
    <button class="filter__btn" @click="applyFilter()">Применить</button>
  </div>
  <div class="">
    <Bar v-if="loaded" :data="chartData"/>
  </div>
</template>

<script>
import {Bar} from 'vue-chartjs'
import {Chart as ChartJS} from 'chart.js/auto'
import axios from "axios";

ChartJS.register()

export default {
  name: 'AdsChart',
  props: {
    api: {
      required: true,
    },
    withDateFilter: {
      required: false,
      type: Boolean
    }
  },
  components: {Bar},
  data: () => ({
    filter: {
      date: {
        from: null,
        to: null
      }
    },
    loaded: false,
    chartData: {
      labels: [],
      datasets: [
        {
          id: 'budget',
          label: 'Бюджет',
          backgroundColor: '#3b82f6',
          borderColor: '#3b82f6',
          pointBorderWidth: 3,
          type: 'line',
          fill: false,
          data: []
        },
        {
          id: 'cpm',
          label: 'CPM',
          backgroundColor: '#f63b3b',
          borderColor: '#f63b3b',
          pointBorderWidth: 3,
          type: 'line',
          fill: false,
          data: []
        }
      ]
    }
  }),
  async mounted() {
    await this.getChartData();
  },
  methods: {
    applyFilter() {
      this.getChartData([
        {
          name: 'created_at',
          between: [this.filter.date.from, this.filter.date.to]
        }
      ])
    },
    async getChartData(filter = null) {
      this.loaded = false;
      try {
        const config = filter ? {
          params: {
            filter: JSON.stringify(filter)
          }
        } : null;
        const response = await axios.get(this.api, config);
        const data = response.data.data;

        this.chartData.labels = [];
        this.chartData.datasets[0].data = [];
        this.chartData.datasets[1].data = [];

        for (let key in data) {
          this.chartData.labels.unshift(data[key]['created_at']);
          for (let column in data[key]['changes']) {
            let index = this.chartData.datasets.findIndex(item => item.id === column);
            this.chartData.datasets[index].data.unshift(data[key]['changes'][column]['value']);
          }
        }
      } catch (e) {
        console.error(e)
      }
      this.loaded = true;
      return this.chartData;
    }
  }
}
</script>

<style>
.filter {
  display: flex;
  justify-content: center;
  margin-bottom: 1rem;
}
.filter__date-input {
  padding: 0.5rem;
  border-radius: 3px;
  border: 1px solid black;
  margin-right: 1rem;
}
.filter__btn {
  padding: 0.5rem;
  border-radius: 3px;
  border: 1px solid #3b82f6;
  background-color: #3b82f6;
  color: white;
  cursor: pointer;
}
</style>
