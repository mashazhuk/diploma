<template>
    <v-card
      class="wrapper mx-auto pa-12 pb-8" 
      elevation="8"
      max-width="448"
      rounded="lg"
    >     
    <v-form 
        v-model="form"
        @submit.prevent="register"
        > 
        <v-text-field
          v-model="name"
          :counter="30"
          prepend-inner-icon="mdi-account"
          label="Ім'я"
          variant="outlined"
          required
        ></v-text-field>
    <v-text-field
          v-model="email"
          :counter="30"
          prepend-inner-icon="mdi-email-outline"
          label="Email"
          type="email"
          variant="outlined"
          required
        ></v-text-field>
        <v-text-field
          v-model="password"
          :counter="50"
          :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
          :type="visible ? 'text' : 'password'"
          :rules="[passwordRules.required, passwordRules.min ]"
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
          :rules="[passwordRules.required, passwordRules.min]"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible_conf = !visible_conf"
        
          label="Підтвердіть пароль"
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
            type="submit">
            Зареєструватися
          </v-btn>
          </v-form>
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
        visible_conf: false,
        form: false,
        loading: false,
        passwordRules: {
          required: value => !!value || 'Заповніть поле.',
          min: v => v.length >= 6 || 'Мінімум 6 символів',
          // passMatch: v => v === this.password || "Паролі не співпадають",
        },

        emailRules: [
          v => !!v || 'Заповніть поле',
          v => (v && v.length >= 10) || 'Мінімум 10 символів',
        ],
      }),
      methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate()
        },
        register() {
        this.loading = true;
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
                localStorage.setItem('token', resp.data.token);
                this.$router.push('/week-sch-student');
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
  