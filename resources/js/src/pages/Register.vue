<template>
    <v-card
      class="wrapper mx-auto pa-12 pb-8" 
      elevation="8"
      max-width="448"
      rounded="lg"
    >      
    <v-text-field
          v-model="email"
          :counter="30"
          prepend-inner-icon="mdi-email-outline"
          label="Email"
          variant="outlined"
          required
        ></v-text-field>
        <v-text-field
          v-model="password"
          :counter="50"
          :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
          :type="visible ? 'text' : 'password'"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible = !visible"
        
          label="Пароль"
          required
        ></v-text-field>
        <v-text-field
          v-model="password_confirmation"
          :counter="50"
          :append-inner-icon="visible_conf ? 'mdi-eye' : 'mdi-eye-off'"
          :type="visible_conf ? 'text' : 'password'"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible_conf = !visible_conf"
        
          label="Підтвердіть пароль"
          required
        ></v-text-field>
  
        <v-btn class="mb-8" color="blue" size="large" variant="tonal" block @click="register">
            Зареєструватися
          </v-btn>
          <v-card-text class="text-center">
              <router-link to="/login"
                class="text-blue text-decoration-none"
                rel="noopener noreferrer"
              >
                Вже є акаунт? Увійти <v-icon icon="mdi-chevron-right"></v-icon>
              </router-link>
           </v-card-text>
    </v-card>
  </template>
  
  <script>
import axios from 'axios';

    export default {
      name: 'Register',
      data: () => ({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        visible: false,
        visible_conf: false
      //   emailRules: [
      //   v => !!v || 'Email is required',
      //   v => (v && v.length <= 10) || 'Email must be less than 10 characters',
      // ],
      }),
      methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate()
        },
        register() {
          axios.get('/sanctum/csrf-cookie') .then(response => { 
            axios.post('/api/register', { 
                name: this.name,
                email: this.email, 
                password: this.password,
                password_confirmation: this.password_confirmation
            }) 
              .then(resp => { 
                console.log(resp),
                localStorage.setItem('x_xsrf_token', resp.config.headers['X-XSRF-TOKEN']);
            }) 
              .catch(error => { console.log(error); }); 
            }) 
            .catch(error => { console.log(error); });
        },
      }
    }
  </script>

<style scoped>
.wrapper {
  top: 50%;
  transform: translateY(-50%);
}
</style>
  