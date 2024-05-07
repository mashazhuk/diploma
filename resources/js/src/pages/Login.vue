<template>
   <v-card
      class="wrapper mx-auto pa-12 pb-8" 
      elevation="8"
      max-width="448"
      rounded="lg"
    >      
      <v-form 
        v-model="form"
        @submit.prevent="login"
        >
        <v-text-field
          v-model="email"
          :counter="30"
          prepend-inner-icon="mdi-email-outline"
          :rules="emailRules"
          label="Email"
          type="email"
          variant="outlined"
          required
        ></v-text-field>
        <v-text-field
          v-model="password"
          :counter="50"
          :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
          :rules="passwordRules"
          :type="visible ? 'text' : 'password'"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible = !visible"
        
          label="Пароль"
          required
        ></v-text-field>

          <v-btn 
            :disabled="!form"
            :loading="loading"
            class="mb-8" 
            color="blue" 
            size="large" 
            variant="tonal" 
            block 
            type="submit"
            >
            Увійти
          </v-btn>
      </v-form>
          <v-card-text class="text-center">
              <router-link to="/register"
                class="text-blue text-decoration-none"
                rel="noopener noreferrer"
              >
                Немає акаунту? Зареєструватися <v-icon icon="mdi-chevron-right"></v-icon>
              </router-link>
           </v-card-text>
   </v-card>
  </template>
  
  <script>
import axios from 'axios';

    export default {
      name: 'Login',
      data: () => ({
        email: '',
        password: '',
        visible: false,
        form: false,
        loading: false,

        passwordRules: [
          value => !!value || 'Заповніть поле.',
          v => v.length >= 6 || 'Мінімум 6 символів',
      ],

        
        emailRules: [
        v => !!v || 'Заповніть поле',
        v => (v && v.length >= 10) || 'Мінімум 10 символів',
      ],
      }),
      methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate()
        },
        login() {
          this.loading = true;

          axios.get('/sanctum/csrf-cookie') .then(response => { 
            axios.post('/api/login', { email: this.email, password: this.password }) 
              .then(resp => { 
                console.log(resp),
                localStorage.setItem('x_xsrf_token', resp.config.headers['X-XSRF-TOKEN']);
                localStorage.setItem('token', resp.data.token);
                this.$router.push('/week-sch-student');
              }) 
              .catch(error => { console.log(error); }); 
            }) 
            .catch(err => { console.log(err); });
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
  