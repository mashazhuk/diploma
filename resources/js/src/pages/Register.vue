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
          v-model="last_name"
          :counter="30"
          :rules = "nameRules"
          prepend-inner-icon="mdi-account"
          label="Прізвище"
          variant="outlined"
          required
        ></v-text-field>

        <v-text-field
          v-model="name"
          :counter="30"
          :rules = "nameRules"
          prepend-inner-icon="mdi-account"
          label="Ім'я"
          variant="outlined"
          required
        ></v-text-field>

        <v-text-field
          v-model="surname"
          :counter="30"
          prepend-inner-icon="mdi-account"
          label="По-батькові"
          variant="outlined"
        ></v-text-field>

        <v-select
          label="Оберіть роль"
          v-model="role"
          :items="roles"
          :rules="[v => !!v || 'Заповніть поле']"
          item-title="name"
          item-value="dbname"  
          required        
        ></v-select>

        <v-select v-if="role == 'student'"
          v-model="group"
          label="Оберіть групу"
          :rules="[v => !!v || 'Заповніть поле']"
          :items="allGroups"  
          required            
        ></v-select>

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
        last_name: '',
        surname: null,
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
        nameRules: [
          v => !!v || "Заповніть поле",
          v => (v && v.length <= 30) || "Максимум 30 символів ",
        ],
        role: null,
        roles: [
          { name: 'Студент', dbname: 'student' },
          { name: 'Викладач', dbname: 'admin' }
        ],
        group: null,
        allGroups: [],
      }),

      mounted() {
        this.getGroups();
      },

      methods: {
        async validate() {
            const { valid } = await this.$refs.form.validate()
        },

        getGroups() {
          axios.get('api/get-groups')
            .then(response => {
              this.allGroups = response.data;
            })
        },

        register() {
        this.loading = true;
          axios.get('/sanctum/csrf-cookie') .then(response => { 
            axios.post('/api/register', { 
                name: this.name,
                last_name: this.last_name,
                surname: this.surname,
                email: this.email, 
                role: this.role,
                group_name: this.group,
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
@media only screen and (max-width: 1024px) {
  .wrapper {
    top: 50%;
    transform: translateY(-50%);
  }
}
</style>
  