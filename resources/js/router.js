import {createRouter, createWebHistory} from 'vue-router'
import AdsPage from './pages/AdsPage.vue'
//import RulesPage from './pages/RulesPage.vue'
//import LogsPage from './pages/LogsPage.vue'

const routes = [
  {
    path: "/",
    component: () => import("./pages/HomePage.vue"),
  },
  {
    path: "/rules",
    component: () => import("./pages/RulesPage.vue"),
  },
  {
    path: "/rules/:id",
    name: "rule-edit",
    component: () => import("./pages/RulesPage.vue"),
  },
  {
    path: "/rules-table",
    component: () => import("./pages/RulesTablePage.vue"),
  },
  {
    path: "/rules-log",
    component: () => import("./pages/RulesLogTablePage.vue"),
  },
  {
    path: "/ads-table",
    component: () => import("./pages/AdsTablePage.vue"),
  },
  {
    path: "/ads/:id",
    name: "ad-chart",
    component: () => import("./pages/AdsChartPage.vue"),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
