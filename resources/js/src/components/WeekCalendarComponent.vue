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
      <v-hover v-slot="{ isHovering, props }">
      <v-card
      class="day-of-week"
        color="indigo-accent-2"
        variant="outlined"
        v-bind="props"
      >
      <v-card-item>
          <div class="dayName">
            <div class="text-overline mb-1 weekDay">
              {{ day.date.toLocaleString('uk-UA', {weekday: 'short'}) }}
            </div>
            <div class="text-h6 mb-1 day" :class="{ today: isToday(day) }">{{ day.date.getDate() }}</div>
          </div>
          <WeekLesson :lessons="lessonsByDate && lessonsByDate[typeOfWeek] ? lessonsByDate[typeOfWeek][day.weekday] || [] : []"  @update-lessons="getLessonsByDate"  :typeOfWeek="typeOfWeek"/>
          <div class="d-flex justify-center align-end mb-6" v-if="isHovering && role === 'admin'">
            <v-btn
            @click="openModal(day.date)"
            icon="mdi-plus"
            variant="text"
            ></v-btn>
            </div>
            <event-add-modal @close="dialog = false" :dialog="dialog" :day="selectedDay" @update-lessons="getLessonsByDate"></event-add-modal>
        </v-card-item>
        </v-card>
      </v-hover>
    </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Menu from './Menu.vue'
import WeekLesson from './WeekLesson.vue';
import EventAddModal from './EventAddModal.vue';

export default {
        components: {
            WeekLesson,
            Menu,
            EventAddModal
        },
        name: 'WeekCalendar',
        data() {
            return {                
                week: [],
                month: '',
                typeOfWeek: '',
                current: new Date(),
                lessonsByDate: [],
                dialog: false,
                selectedDay: {},
                role: ''
            }
        },

        mounted() {
            this.updateCalendar();
            this.getLessonsByDate();
            this.getRole();
            this.getTypeOfWeek();
            
        },
        methods: {
            updateCalendar() {
                this.week = this.getWeek(this.current);
                this.month = this.getMonthName(this.current);
                this.typeOfWeek = this.getTypeOfWeek();
                // console.log(this.typeOfWeek);
            },

            getWeekNumber(d) {
                d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
                d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay()||7));
                let yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
                let weekNo = Math.ceil(( ( (d - yearStart) / 86400000) + 1)/7)
                return [weekNo];
            },


            getWeek() {
                let week = [];
                let first = this.current.getDate() - this.current.getDay() + (this.current.getDay() === 0 ? -6 : 1);
                for (let i = 0; i < 6; i++) {
                    let day = new Date(this.current.getFullYear(), this.current.getMonth(), first + i);
                    let dayObject = {
                        date: day,
                        weekday: day.getDay() === 0 ? 7 : day.getDay(),
                        weekNumber: this.getWeekNumber(day)[0]
                    };
                    week.push(dayObject);
                    // console.log(dayObject.weekday);
                    
                    // week.push({
                    //     date: day,
                    //     weekday: day.getDay() === 0 ? 7 : day.getDay(),
                    //     weekNumber: this.getWeekNumber(day)[0]
                    // });
                }
                return week;
            },

            getTypeOfWeek() {
                let typeOfWeek = this.getWeekNumber(new Date(this.getWeek()[0].date));
                if (typeOfWeek % 2 == 0) {
                    //знаменатель
                    return 2;
                } else {
                    //числитель
                    return 1;
                }
            },

            openModal(day) {
                this.selectedDay = day.date;
                this.dialog = true;
            },


            getMonthName() {
                let date = new Date(this.getWeek()[0].date);
                let month = date.toLocaleString('uk-UA', { month: 'long' });
                return month;
            },

            changeWeek(weeks) {
                let newDate = new Date(this.current.setDate(this.current.getDate() + weeks * 7));
                this.current = newDate;
                this.week = this.getWeek();
                this.typeOfWeek = this.getTypeOfWeek();
                // console.log(this.typeOfWeek);
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
                        // this.lessonsByDate = Object.values(this.lessonsByDate);
                        // console.log(this.lessonsByDate);
                        console.log(this.lessonsByDate[this.typeOfWeek][1]);
                    })
                    .catch(error => {
                        console.error(error);
                    });
                    
            },

            isToday(day) {
                const today = new Date();
                return day.date.getDate() === today.getDate() &&
                day.date.getMonth() === today.getMonth() &&
                day.date.getFullYear() === today.getFullYear();
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
