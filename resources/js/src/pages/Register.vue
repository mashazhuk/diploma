<template>
    <v-sheet class="mx-auto" width="300">        
      <v-form  ref="form">
        <v-text-field
          v-model="name"
          :counter="10"
          
          label="Ім'я"
          required
        ></v-text-field>
        <v-text-field
          v-model="email"
          :counter="10"
          
          label="Email"
          required
        ></v-text-field>
        <v-text-field
          v-model="password"
          type="password"
          :counter="10"
        
          label="Пароль"
          required
        ></v-text-field>
        <v-text-field
          v-model="password_confirmation"
          type="password"
          :counter="10"
        
          label="Пароль"
          required
        ></v-text-field>
  
        <div class="d-flex flex-column">
          <v-btn class="mt-4" color="success" type="submit"  value="register" block @click="register">
            Зареєструватися
          </v-btn>
        </div>
      </v-form>
    </v-sheet>
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
  