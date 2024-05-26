<template>
    <Menu></Menu>
    <!-- <div class="d-flex justify-center mb-6"> -->
    <v-form 
         v-model="form"
         @submit.prevent="editProfile"
         style="width: 90%; margin-top: 20px;"
    >

      <label><v-list-subheader>Прізвище</v-list-subheader></label>
        <v-responsive
          max-width="344"
        >
          <v-text-field
           :counter="30"
           density="compact"
           prepend-inner-icon="mdi-account"
           
           v-model="last_name"
           :placeholder="user.last_name"
           type="name"
           variant="outlined"
         ></v-text-field>
         </v-responsive>

        <label><v-list-subheader>Ім'я</v-list-subheader></label>
        <v-responsive
          max-width="344"
        >
          <v-text-field
           :counter="30"
           density="compact"
           prepend-inner-icon="mdi-account"
           
           v-model="name"
           :placeholder="user.name"
           type="name"
           variant="outlined"
         ></v-text-field>
        </v-responsive>


         <label><v-list-subheader>По-батькові</v-list-subheader></label>
        <v-responsive
          max-width="344"
        >
          <v-text-field
           :counter="30"
           density="compact"
           prepend-inner-icon="mdi-account"
           
           v-model="surname"
           :placeholder="user.surname"
           type="name"
           variant="outlined"
         ></v-text-field>
       </v-responsive>

       <label><v-list-subheader>Email</v-list-subheader></label>
        <v-responsive
          max-width="344"
        >
          <v-text-field
           v-model="email"
           density="compact"
           :counter="30"
           prepend-inner-icon="mdi-email-outline"
           :rules="emailRules"
           :placeholder="user.email"
           type="email"
           variant="outlined"
         ></v-text-field>
        </v-responsive>


        <label v-if="role === 'student'"><v-list-subheader>Група</v-list-subheader></label>
        <v-responsive
          max-width="344"
          v-if="role === 'student'"
        >
          <v-select
              v-model="group"
              :items="allGroups"              
            ></v-select>
        </v-responsive>
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
             @click="reset"
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
        this.getGroups();
        this.getGroupName();
        this.getRole();
        },

       data: () => ({
         name: '',
         last_name: '',
         surname: '',
         email: '',
         role: '',
         visible: false,
         form: false,
         loading: false,
         user: [],
         group: [],
         allGroups: [],        
         role: '' ,
         emailRules: [
            v => !v || v.length >= 10 || 'Мінімум 10 символів'
         ],
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

        reset() {
          this.name = '',
          this.last_name = '',
          this.surname = '',
          this.email = ''
        },

         editProfile() {
          let formData = {};
          if(this.name) formData.name = this.name;
          if(this.last_name) formData.last_name = this.last_name;
          if(this.surname) formData.surname = this.surname;
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
                 console.log(formData.surname);
                 console.log(this.surname);
                 if(this.name) formData.name = this.name;
                 if(this.last_name) formData.last_name = this.last_name;
                 if (this.surname) formData.surname = this.surname;
                 if(this.email) formData.email = this.email;
                 if(this.password) formData.password = this.password;
                 this.loading = false;
               }) 
               .catch(error => { 
                console.log(error); 
                this.loading = false;
              });
         },

         getGroupName() {
          const token = localStorage.getItem('token');
            axios.get('api/get-user-group-name', {
              headers: {
                'Authorization': `Bearer ${token}`
              }
            })
              .then(response => {
                this.group = response.data;
              });
          },

          getGroups() {
            axios.get('api/get-groups')
              .then(response => {
                this.allGroups = response.data;
              })
          },

          getRole() {
            this.role = localStorage.getItem('role');
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
   
