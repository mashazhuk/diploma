import { createRouter, createWebHashHistory } from "vue-router";
import routes from "./routes.js";

const router = createRouter({
    history: createWebHashHistory(),
    linkActiveClass: 'active',
    routes
});
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.name !== 'Login' && to.name !== 'Register' && !token) {
      // Если пользователь не аутентифицирован и не находится на странице входа,
      // перенаправляем его на страницу входа
      next({ name: 'Login' });
    } else {
      next(); // Продолжаем навигацию
    }
  });

export default router;