import './bootstrap';
import { createApp } from 'vue';
import router from "./src/router/router.js";
import App from './src/App.vue'
// Vuetify
import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { VTimePicker } from 'vuetify/labs/VTimePicker'
import axios from 'axios';
import { createI18n } from 'vue-i18n'
import { VDateInput } from 'vuetify/labs/VDateInput'

// Добавьте перехватчик ответов
axios.interceptors.response.use(
  response => {
    // Если ответ успешный, просто продолжайте
    return response;
  },
  error => {
    // Если статус ответа 401, перенаправьте на страницу входа
    if (error.response && error.response.status === 401) {
      router.push('/login');
    }

    // Важно продолжить генерировать ошибку, чтобы не прерывать цепочку промисов
    return Promise.reject(error);
  }
);


const vuetify = createVuetify({
  theme: {
    defaultTheme: 'dark'
  },
    components: {
        VTimePicker,
        VDateInput,
        ...components
      },
  directives,
  icons: {
    defaultSet: 'mdi',
  }
})

createApp(App)
    .use(router)
    .use(vuetify)
    .mount('#app');

