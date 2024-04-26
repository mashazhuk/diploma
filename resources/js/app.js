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


const vuetify = createVuetify({
    components: {
        VTimePicker,
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

