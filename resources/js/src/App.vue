<template>
    <v-card>
    <v-layout>
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
  overflow-y: auto;
  height: 100vh;
  scrollbar-width: thin;
  scrollbar-color: rgb(77, 77, 77) transparent;
}


/* Работает в Chrome, Edge и Safari */
.main::-webkit-scrollbar {
  width: 4px;
}

.main::-webkit-scrollbar-track {
  background: transparent;
}

.main::-webkit-scrollbar-thumb {
  background-color: rgb(77, 77, 77);
  border-radius: 20px;
  width: 4px;
  border: 3px transparent;
}
</style>

<script>
export default {
    name: 'App',

    methods: {
        

        logout() {
          localStorage.removeItem('x_xsrf_token'),
                localStorage.removeItem('token'),
                this.$router.push('/login')
        //     const token = localStorage.getItem('token');
        //     axios.get('/sanctum/csrf-cookie') .then(response => { 
        //     axios.post('/api/logout', {
        //       headers: {
        //              'Authorization': `Bearer ${token}`
        //             }
        //     })
        //     .then(res => {
        //         localStorage.removeItem('x_xsrf_token'),
        //         localStorage.removeItem('token'),
        //         this.$router.push('/login')
        //     })
        //     .catch(error => {
        //         console.error('Error logging out:', error);
        //     });
        // })
      
    },
}
}
</script>
