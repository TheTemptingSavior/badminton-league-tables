import Vue from 'vue'
import VueRouter from 'vue-router'

import Home from '@/views/Home.vue'
import Login from "@/views/Login";
import Scoreboards from "@/views/Scoreboards";
import Tracker from "@/views/Tracker";
import NotFound from "@/views/errors/NotFound";
import adminRoutes from "@/router/adminRoutes";
import Scorecard from "@/views/Scorecard";
import Teams from "@/views/Teams";

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: {
      title: 'League Tables'
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: {
      title: 'Login'
    }
  },
  {
    path: '/scoreboards',
    name: 'Scoreboards',
    component: Scoreboards,
    meta: {
      title: 'Scoreboards'
    }
  },
  {
    path: '/tracker',
    name: 'Tracker',
    component: Tracker,
    meta: {
      title: 'Tracker'
    }
  },
  {
    path: '/scorecards/:scorecardId',
    name: 'Scorecard',
    component: Scorecard,
    props: true,
    meta: {
      title: 'Scorecard'
    }
  },
  {
    path: '/teams',
    name: 'Teams',
    component: Teams,
    meta: {
      title: 'Teams'
    }
  },
  {
    path: '*',
    name: 'NotFound',
    component: NotFound,
    meta: {
      title: 'Page Not Found'
    }
  },
  ...adminRoutes
]

const router = new VueRouter({
  routes
})
router.afterEach((to) => {
  // Use next tick to handle router history correctly
  // see: https://github.com/vuejs/vue-router/issues/914#issuecomment-384477609
  Vue.nextTick(() => {
    document.title = to.meta.title || 'League Tables';
  });
});

export default router
