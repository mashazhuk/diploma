<template>
    <div class="calendar-header">
        <h3>{{ month }}</h3>
        <div>
            <button @click="changeWeek(-1)">
                <v-icon icon="mdi-arrow-left-drop-circle-outline"></v-icon>
            </button>
            <button @click="goToToday">Сьогодні</button>
            <button @click="changeWeek(1)">
                <v-icon icon="mdi-arrow-right-drop-circle-outline"></v-icon>
            </button>
        </div>
    </div>
    
    <v-container>
    <v-row>
      <v-col
        v-for="(day, index) in week"
        class="day-of-week"
        :key="index"
        cols="12"
        sm="4"
        md="2"
      >
            <div class="dayName">
                <p class="weekday">{{ day.toLocaleString('uk-UA', {weekday: 'short'})}}</p>
                <h3 class="day">{{ day.getDate() }}</h3>
            </div>
            <WeekLesson :lessons="lessonsByDate[day.toLocaleDateString('en-CA')] || []"/>
    </v-col>
    </v-row>
  </v-container>
</template>

<script>

import WeekLesson from './WeekLesson.vue';

export default {
        components: {
            WeekLesson
        },
        name: 'WeekCalendar',
        data() {
            return {                
                week: [],
                month: '',
                current: new Date(),
                lessonsByDate: [],
                role: ''
            }
        },

        mounted() {
            this.updateCalendar();
            this.getLessonsByDate();
            this.getRole();
            // this.fetchProfile();
            
        },
        methods: {
            updateCalendar() {
                this.week = this.getWeek(this.current);
                this.month = this.getMonthName(this.current);
            },

            getWeek() {
    let week = [];
    let first = this.current.getDate() - this.current.getDay() + (this.current.getDay() === 0 ? -6 : 1);
    for (let i = 0; i < 6; i++) {
        let day = new Date(this.current.getFullYear(), this.current.getMonth(), first + i);
        week.push(day);
    }
    return week;

},


            getMonthName() {
                let date = new Date();
                let month = date.toLocaleString('uk-UA', { month: 'long' });
                return month;
            },

            changeWeek(weeks) {
                let newDate = new Date(this.current.setDate(this.current.getDate() + weeks * 7));
                this.current = newDate;
                this.week = this.getWeek();
                this.month = this.getMonthName();
            },
            goToToday() {
                this.current = new Date();
                this.updateCalendar();
            },

            getRole() {
                const token = localStorage.getItem('token');
                axios.get('/api/get-role', {
                    headers: {
                     'Authorization': `Bearer ${token}`
                    }
                })
                    .then(res => {
                        this.role = res.data.role;
                        localStorage.setItem('role', res.data.role);
                    })
            },

           
            getLessonsByDate() {
                axios.get('/api/sorted-lessons')
                    .then(response => {
                        this.lessonsByDate = response.data;
                        console.log(this.lessonsByDate);
                    })
                    .catch(error => {
                        console.error(error);
                    });
                    
            },

        //     fetchProfile() {
        //     const token = localStorage.getItem('token');
        //     axios.get('/api/profile', {
        //         headers: {
        //              'Authorization': `Bearer ${token}`
        //             }
        //     })
        //         .then(response => {
        //             // Успешный ответ от сервера
        //            console.log(response.data.data);
        //         })
        //         .catch(error => {
        //             if (error.response && error.response.status === 401) {
        //     // Токен недействителен, перенаправляем на страницу входа
        //                 this.$router.push('/login');
        //             } else {
        //     // Другая ошибка, можно обработать по-другому, например, показать сообщение об ошибке
        //             console.error('Ошибка при запросе профиля:', error);
        //             }
        //         });
        // }
        }
    }
</script>

<style scoped>
.calendar-header {
    display: flex;
    justify-content: space-between;
}
.sch-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.day-of-week {
    /* flex: 1 0 0; */
    border-right: 1px solid grey;
    padding: 10px;
    box-sizing: border-box;
    /* min-width: 150px; */
}
.weekday {
    text-transform: capitalize;
}

.dayName {
    border-bottom: 1px solid grey;
}

p {
    margin-bottom: 0.5rem;
}

</style>
