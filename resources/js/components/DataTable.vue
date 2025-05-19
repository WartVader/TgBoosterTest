<template>
  <div class="table-container">
    <!-- Таблица -->
    <div class="table-wrapper">
      <table class="table">
        <thead class="table__head">
          <tr class="table__row">
            <th
              v-for="column in columns"
              :key="column.key"
              @click="sortBy(column.key)"
              class="table__cell"
            >
              <div class="table__column" :class="column.sortable ? 'table__column_sortable' : ''">
                {{ column.label }}
                <span v-if="sortColumn === column.key && column.sortable" class="table__column-sort">
                  {{ sortDirection === 'asc' ? '▲' : '▼' }}
                </span>
              </div>
            </th>
            <th class="table__cell table__cell_action" v-if="editRouteName || viewRouteName">
              Действия
            </th>
          </tr>
        </thead>
        <tbody class="table__body">
          <tr v-for="(item, index) in items" :key="item.id" class="table__row">
            <td
              v-for="column in columns"
              :key="column.key"
              class="table__cell"
            >
              <slot :name="`cell-${column.key}`" :item="item" :index="index">
                {{ item[column.key] }}
              </slot>
            </td>
            <td class="table__cell table__cell_action" v-if="editRouteName || viewRouteName">
              <router-link
                :to="{ name: editRouteName, params: { id: item.id }}"
                v-if="editRouteName"
                class="nav-link"
              >
                Изменить
              </router-link>
              <router-link
                :to="{ name: viewRouteName, params: { id: item.id }}"
                v-if="viewRouteName"
                class="nav-link"
              >
                Посмотреть
              </router-link>
            </td>
          </tr>
          <tr v-if="items.length === 0">
            <td :colspan="columns.length" class="">
              Данных нет
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Пагинация -->
    <div>
      <div class="">
        <p>
          Показано с <span class="font-medium">{{ pagination.from }}</span> по <span
          class="font-medium">{{ pagination.to }}</span> из <span class="font-medium">{{ pagination.total }}</span>
          записей
        </p>
        <div>
          <nav>
            <button
              @click="previousPage"
              :disabled="pagination.current_page === 1"
              class="table__page-btn table__page-btn_previous"
            >
              Назад
            </button>
            <button
              v-for="page in pages"
              :key="page"
              @click="goToPage(page)"
              :class="{
                'table__page-btn_current': page === pagination.current_page,
              }"
              class="table__page-btn"
            >
              {{ page }}
            </button>
            <button
              @click="nextPage"
              :disabled="pagination.current_page === pagination.last_page"
              class="table__page-btn table__page-btn_next"
            >
              Вперед
            </button>
          </nav>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {ref, computed, watch} from 'vue';
import axios from 'axios';

const props = defineProps({
  columns: {
    type: Array,
    required: true,
    validator: (value) => {
      return value.every(col => 'key' in col && 'label' in col);
    }
  },
  apiUrl: {
    type: String,
    required: true
  },
  perPage: {
    type: Number,
    default: 10
  },
  editRouteName: {
    type: String,
    required: false
  },
  viewRouteName: {
    type: String,
    required: false
  }
});

const emit = defineEmits(['update:loading']);

const items = ref([]);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: props.perPage,
  from: 0,
  to: 0,
  total: 0
});
const sortColumn = ref(null);
const sortDirection = ref('asc');
const isLoading = ref(false);

// Вычисляем диапазон страниц для пагинации
const pages = computed(() => {
  const range = [];
  const current = pagination.value.current_page;
  const last = pagination.value.last_page;
  const delta = 2;

  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i);
  }

  if (current - delta > 2) {
    range.unshift('...');
  }

  if (current + delta < last - 1) {
    range.push('...');
  }

  range.unshift(1);
  if (last !== 1) range.push(last);

  return range;
})

const fetchData = async () => {
  try {
    isLoading.value = true;
    emit('update:loading', true);

    const params = {
      page: pagination.value.current_page,
      per_page: pagination.value.per_page,
      sort: sortColumn.value,
      direction: sortDirection.value
    };

    const {data} = await axios.get(props.apiUrl, {params});

    items.value = data.data;
    console.log(data);
    pagination.value = {
      current_page: data.pagination.current_page,
      last_page: data.pagination.last_page,
      per_page: data.pagination.per_page,
      from: data.pagination.from,
      to: data.pagination.to,
      total: data.pagination.total
    };
  } catch (error) {
    console.error('Ошибка загрузки данных:', error);
  } finally {
    isLoading.value = false;
    emit('update:loading', false);
  }
}

const sortBy = (column) => {
  if (sortColumn.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
}

const goToPage = (page) => {
  if (page === '...') return
  pagination.value.current_page = page
}

const previousPage = () => {
  if (pagination.value.current_page > 1) {
    pagination.value.current_page--
  }
}

const nextPage = () => {
  if (pagination.value.current_page < pagination.value.last_page) {
    pagination.value.current_page++
  }
}

watch(
  [() => pagination.value.current_page, sortColumn, sortDirection],
  () => {
    fetchData()
  },
  {immediate: true}
)
</script>

<style scoped>
.table {
  width: 100%;
  border-collapse: collapse;
}
.table__cell {
  text-align: start;
  padding: 1rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.2);
}
.table__cell_action {
  width: 20%;
}
.table__column {
  display: inline-flex;
  position: relative;
  align-items: center;
}
.table__column.table__column_sortable {
  cursor: pointer;
}
.table__column-sort {
  position: absolute;
  right: -1rem;
  font-size: 0.5rem;
}
.table__page-btn {
  background-color: transparent;
  border: 1px solid #3b82f6;
  border-radius: 3px;
  color: #3b82f6;
  padding: 0.3rem 0.5rem;
  margin-right: 0.5rem;
  transition: 0.2s;
  cursor: pointer;
}
.table__page-btn:hover {
  color: white;
  background-color: #3b82f6;
  transition: 0.2s;
}
</style>
