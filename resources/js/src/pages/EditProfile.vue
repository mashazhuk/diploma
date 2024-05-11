<template>
    <Menu></Menu>

    <v-card
       class="wrapper mx-auto pa-12 pb-8" 
       elevation="8"
       rounded="lg"
     >      
       <v-form 
         v-model="form"
         @submit.prevent="editProfile"
         >
         <v-text-field
           :counter="30"
           prepend-inner-icon="mdi-account"
           
           v-model="name"
           :placeholder="user.name"
           type="name"
           variant="outlined"
           required
         ></v-text-field>

         <v-text-field
           v-model="email"
           :counter="30"
           prepend-inner-icon="mdi-email-outline"
           
           :placeholder="user.email"
           type="email"
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
          formData.name = this.name;
          formData.email = this.email;
          formData.password = this.password;
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
                 console.log(resp)
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
   
