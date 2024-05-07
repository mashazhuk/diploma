<template>
    <Menu></Menu>

    <div class="calendar-header">
        <h3>{{ month }}</h3>
        <div class="calendar-header right">
            <v-btn @click="changeWeek(-1)" density="compact" icon="mdi-chevron-left"></v-btn>
            <v-btn @click="goToToday" variant="plain" density="compact" class="pa-0">Сьогодні</v-btn>
            <v-btn @click="changeWeek(1)" density="compact" icon="mdi-chevron-right"></v-btn>
        </div>
    </div>
    
    <v-container>
    <v-row>
      <v-col
        v-for="(day, index) in week"
        :key="index"
        cols="12"
        sm="4"
        md="4"
        lg="2"
      >
      <v-card
      class="day-of-week"
        color="indigo-accent-2"
        variant="outlined"
      >

      <v-card-item>
          <div class="dayName">
            <div class="text-overline mb-1 weekDay">
              {{ day.toLocaleString('uk-UA', {weekday: 'short'}) }}
            </div>
            <div class="text-h6 mb-1 day" :class="{ today: isToday(day) }">{{ day.getDate() }}</div>
          </div>
          <WeekLesson :lessons="lessonsByDate[day.toLocaleDateString('en-CA')] || []" @update-lessons="getLessonsByDate"/>
        </v-card-item>
        </v-card>
    </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Menu from './Menu.vue'
import WeekLesson from './WeekLesson.vue';

export default {
        components: {
            WeekLesson,
            Menu
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
                    // .catch(error => {
                    //     if(error.response.status === 401) {
                    //         this.$router.push('/login');
                    //     }
                    // })
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

            isToday(day) {
        const today = new Date();
        return day.getDate() === today.getDate() &&
            day.getMonth() === today.getMonth() &&
            day.getFullYear() === today.getFullYear();
    },
        }
    }
</script>

<style lang="scss" scoped>
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100px;

    h3 {
        text-transform: capitalize;        
     }

    .right {
        margin-right: 40px;
    }   


}
.sch-wrapper {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

@media (min-width: 600px) and (max-width: 1280px) {
    .day-of-week {
        /* другие стили... */
        height: 30vh; /* Устанавливает высоту равной 60% от высоты вьюпорта */
    }
}

@media (min-width: 1280px) {
    .day-of-week {
        /* другие стили... */
        height: 60vh; /* Устанавливает высоту равной 60% от высоты вьюпорта */
    }
}

.day-of-week {
    overflow-y: auto;
}

.today {
    color: #29B6F6;
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
