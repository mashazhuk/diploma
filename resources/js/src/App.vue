<template>
    <div>
        <router-link to="/login">Login</router-link>
        <router-link to="/register">Register</router-link>
        <router-link to="/week-sch-student">Розклад студента</router-link>
        <a @click.prevent="logout" href="#">Вийти</a>
        <router-view></router-view>
    </div>
    <!-- <v-card>
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
          <router-link class="nav-link" to="/"><v-list-item prepend-icon="mdi-folder" title="Головна" value="myfiles"></v-list-item></router-link>
          <router-link class="nav-link" to="/week-sch-student"><v-list-item prepend-icon="mdi-account-multiple" title="Розклад на тиждень" value="shared"></v-list-item></router-link>
          <router-link class="nav-link" to="/login"><v-list-item prepend-icon="mdi-account-multiple" title="Login" value="login"></v-list-item></router-link>
          <v-list-item prepend-icon="mdi-star" title="Starred" value="starred"></v-list-item>
        </v-list>
      </v-navigation-drawer>

      <v-main class="main">
        <router-view />
      </v-main>
    </v-layout>
  </v-card> -->
</template>


<style scoped>
.nav-link {
  text-decoration: none;
}
.main {
  margin-left: 20px;
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
                this.$router.push('/login')
            })
        },
    },
}
</script>
