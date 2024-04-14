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


const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
  }
})

createApp(App)
    .use(router)
    // .use(Store)
    .use(vuetify)
    .mount('#app');

