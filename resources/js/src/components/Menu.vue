<template>
    <v-menu
      v-model="menu"

      :close-on-content-click="false"
    >
      <template v-slot:activator="{ props }">
        <v-avatar v-bind="props" :image = "url" class="cursor-pointer mt-7"></v-avatar>
      </template>

      <v-card min-width="300">
        <v-list>
          <v-list-item
          :subtitle="user.email"
          :title="user.name"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list>
          <router-link class="nav-link" to="/week-sch-student"><v-list-item prepend-icon="mdi-calendar-blank-outline" title="Розклад" value="shared"></v-list-item></router-link>
          <router-link class="nav-link" to="/edit-profile"><v-list-item prepend-icon="mdi-account-cog-outline" title="Редагувати профіль" value="edit"></v-list-item></router-link>
          <!-- <v-list-item prepend-icon="mdi-logout"><v-btn @click.prevent="logout" >Logout</v-btn></v-list-item> -->

        </v-list>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn prepend-icon="mdi-logout" color="error" @click.prevent="logout" >Logout</v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>
</template>
    <!-- <v-navigation-drawer
        permanent
        app
        expand-on-hover
        rail
      >
        <v-list>
          <router-link class="nav-link" to="/edit-profile">
            <v-list-item
              :prepend-avatar="url"
              :subtitle="user.email"
              :title="user.name"
            ></v-list-item>
          </router-link>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav>
        
         <router-link class="nav-link" to="/week-sch-student"><v-list-item prepend-icon="mdi-account-multiple" title="Розклад на тиждень" value="shared"></v-list-item></router-link>
          <router-link class="nav-link" to="/login"><v-list-item prepend-icon="mdi-account-multiple" title="Login" value="login"></v-list-item></router-link>
          <router-link class="nav-link" to="/register"><v-list-item prepend-icon="mdi-account-multiple" title="Register" value="register"></v-list-item></router-link>
          <v-list-item prepend-icon="mdi-star" title="Starred" value="starred"></v-list-item>
        </v-list>
        <template v-slot:append>
          <div class="pa-2">
                <v-list density="compact" nav>
                    <v-list-item prepend-icon="mdi-logout"><v-btn @click.prevent="logout" >Logout</v-btn></v-list-item>
                </v-list>
          </div>
        </template>
      </v-navigation-drawer> -->
<!-- </template> -->

<script>
export default {
    name: 'Menu',

    data() {
        return {  
            user: [],
            url: '',
            menu: false,
            message: false,
      hints: false
        }
    }, 

    mounted() {
        this.fetchProfile();
        this.randomPhoto();
    },

    methods: {       

        logout() {
          localStorage.removeItem('x_xsrf_token'),
                localStorage.removeItem('token'),
                this.$router.push('/login')
        },

        randomPhoto() {
          let url = "https://randomuser.me/api/portraits/lego/" + Math.floor(Math.random() * (8 - 0 + 1) + 0) + ".jpg";
          this.url = url;
          return url;
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
            }
    }
}
</script>
