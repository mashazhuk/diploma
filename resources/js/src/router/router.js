import { createRouter, createWebHashHistory } from "vue-router";
import routes from "./routes.js";

const router = createRouter({
    history: createWebHashHistory(),
    linkActiveClass: 'active',
    routes
});

export default router;