<template>
    <Menu></Menu>
    <!-- <div class="d-flex justify-center mb-6"> -->
    <v-form 
         v-model="form"
         @submit.prevent="editProfile"
         style="width: 90%; margin-top: 20px;"
    >
    
      <v-row>
        <v-col cols="4"
        md="1"
        lg="1">
          <v-list-subheader>Ім'я</v-list-subheader>
        </v-col>

        <v-col 
          cols="8"
          sm="8"
          md="4"
          lg="4">
          <v-text-field
           :counter="30"
           density="compact"
           prepend-inner-icon="mdi-account"
           
           v-model="name"
           :placeholder="user.name"
           type="name"
           variant="outlined"
           
           required
         ></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="4"
        md="1"
        lg="1">
          <v-list-subheader>Email</v-list-subheader>
        </v-col>

        <v-col 
        cols="8"
          sm="8"
          md="4"
          lg="4"
        >
          <v-text-field
           v-model="email"
           density="compact"
           :counter="30"
           prepend-inner-icon="mdi-email-outline"
           
           :placeholder="user.email"
           type="email"
           variant="outlined"
           required
         ></v-text-field>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="4"
        md="1"
        lg="1">
          <v-list-subheader>Пароль</v-list-subheader>
        </v-col>

        <v-col 
        cols="8"
          sm="8"
          md="4"
          lg="4">
          <v-text-field
           v-model="password"
           density="compact"
           :counter="50"
           :append-inner-icon="visible ? 'mdi-eye' : 'mdi-eye-off'"
           
           :type="visible ? 'text' : 'password'"
           prepend-inner-icon="mdi-lock-outline"
           variant="outlined"
           @click:append-inner="visible = !visible"
         
           label="Пароль"
           required
         ></v-text-field>
        </v-col>
      </v-row>
      <v-spacer></v-spacer>
      <div class="d-flex">      
          <v-btn 
             :disabled="!form"
             :loading="loading"
             class="me-2 text-none"
             color="blue" 
             size="large"
             variant="tonal" 
             type="submit"
             >
             Оновити профіль
           </v-btn>
            <v-btn 
             :disabled="!form"
             :loading="loading"
             class="me-2 text-none"
             size="large"
             >
             Скасувати
           </v-btn>
           </div>
       </v-form>
       
   </template>
   
   <script>
 import axios from 'axios';
 import Menu from "../components/Menu.vue"
 
     export default {
        components: {
            Menu,
        },
       name: 'EditProfile',
       mounted() {
        this.fetchProfile();
        // this.name = this.user.name;
        },
      // watch: {
      //   'user.name': function(newVal) {
      //     this.name = newVal
      //     console.log(this.name);
      //   } 
      // },

       data: () => ({
          name: '',
         email: '',
         password: '123456',
         role: 'admin',
         visible: false,
         form: false,
         loading: false,
         user: [],
 
      //    passwordRules: [
      //      value => !!value || 'Заповніть поле.',
      //      v => v.length >= 6 || 'Мінімум 6 символів',
      //  ],
 
         
      //    emailRules: [
      //    v => !!v || 'Заповніть поле',
      //    v => (v && v.length >= 10) || 'Мінімум 10 символів',
      //  ],
       }),
       methods: {
         async validate() {
             const { valid } = await this.$refs.form.validate()
         },

         fetchProfile() {
            const token = localStorage.getItem('token');
            axios.get('/api/profile', {
                headers: {
                     'Authorization': `Bearer ${token}`
                    }
            })
                .then(response => {
                    // Успешный ответ от сервера
                    this.user = response.data.data;
                   console.log(response.data.data);
                })
                .catch(error => {
                    if (error.response && error.response.status === 401) {
                        this.$router.push('/login');
                    } else {
                    console.error('Ошибка при запросе профиля:', error);
                    }
                });
            }, 

         editProfile() {
          let formData = {};
          if(this.name) formData.name = this.name;
          if(this.email) formData.email = this.email;
          if(this.password) formData.password = this.password;
          formData.role = this.role;
          const token = localStorage.getItem('token');
           this.loading = true;
             axios.post('/api/edit-profile', formData,
              {
              headers: {
                  'Authorization': `Bearer ${token}`
              }
            }
             )
               .then(resp => { 
                 console.log(resp);
                 if(this.name) formData.name = this.name;
                  if(this.email) formData.email = this.email;
                  if(this.password) formData.password = this.password;
                 this.loading = false;
               }) 
               .catch(error => { 
                console.log(error); 
                this.loading = false;
              });
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
   
