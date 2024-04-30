<template>
    <v-card>
    <v-layout>
      <v-navigation-drawer
        permanent
        app
        expand-on-hover
        rail
      >
        <v-list>
          <v-list-item
            prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg"
            subtitle="sandra_a88@gmailcom"
            title="Sandra Adams"
          ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav>
        
        <router-link class="nav-link" to="/"><v-list-item prepend-icon="mdi-account-multiple" title="Розклад на тиждень" value="shared"></v-list-item></router-link>
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
      </v-navigation-drawer>

      <v-main class="main">
        <router-view />
      </v-main>
    </v-layout>
  </v-card>
</template>


<style scoped>
.nav-link {
  text-decoration: none;
}
.main {
  margin-left: 20px;
  height: 100vh;
}
</style>

<script>

export default {
    name: 'App',

    methods: {
        logout() {
            axios.get('/api/logout')
            .then(res => {
                localStorage.removeItem('x_xsrf_token'),
                localStorage.removeItem('token'),
                this.$router.push('/login')
            })
        },
    },
}
</script>
