import { createRouter, createWebHistory } from "vue-router";
import LoginPage from "@/pages/LoginPage.vue";
import RegisterPage from "@/pages/RegisterPage.vue";
import DashboardPage from "@/pages/DashboardPage.vue";

const routes = [
  {
    path: "/",
    name: "home",
    redirect: "/dashboard", // default redirect
  },
  {
    path: "/login",
    name: "login",
    component: LoginPage,
  },
  {
    path: "/register",
    name: "register",
    component: RegisterPage,
  },
  {
    path: "/dashboard",
    name: "dashboard",
    component: DashboardPage,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");

  if ((to.path === "/login" || to.path === "/register") && token) {
    next("/dashboard");
  } else {
    next();
  }
});

export default router;
