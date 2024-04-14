<template>
    <v-sheet class="mx-auto" width="300">        
      <v-form  ref="form">        
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
  
        <div class="d-flex flex-column">
          <v-btn class="mt-4" color="success" type="submit"  value="login" block @click="login">
            Увійти
          </v-btn>
        </div>
      </v-form>
    </v-sheet>
  </template>
  
  <script>
import axios from 'axios';

    export default {
      name: 'Login',
      data: () => ({
        email: '',
        password: '',
      //   emailRules: [
      //   v => !!v || 'Email is required',
      //   v => (v && v.length <= 10) || 'Email must be less than 10 characters',
      // ],
      }),
      methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate()
        },
        login() {
          axios.get('/sanctum/csrf-cookie') .then(response => { 
            axios.post('/api/login', { email: this.email, password: this.password }) 
              .then(resp => { 
                console.log(resp),
                localStorage.setItem('x_xsrf_token', resp.config.headers['X-XSRF-TOKEN']);
                this.$router.push('/week-sch-student');
              }) 
              .catch(error => { console.log(error); }); 
            }) 
            .catch(error => { console.log(error); });
        },
      }
    }
  </script>
  