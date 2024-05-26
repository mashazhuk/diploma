<template>
  <v-dialog
      v-model="showDialog"
      width="auto"
    >
    <v-card
    class="ma-4"
      rounded="lg"
      variant="flat"
      width="450"
      height="400"
      style="overflow-y: auto"
    >
    
      <v-card-item>
        <v-card-title class="d-flex justify-space-between align-center">
          <div class="d-flex flex-column">
              <v-layout row align-center>
                <v-icon
                  color="#949cf7"
                  icon="mdi-clock-time-four-outline" 
                  start
                ></v-icon>

                <div class="text-h6 text-medium-emphasis font-weight-bold">{{ transformTime(lesson.start_time) }} - {{ transformTime(lesson.end_time) }}</div>
              </v-layout>


          <v-layout row align-center class="mt-2 mb-2">
            <v-icon
              color="#949cf7"
              icon="mdi-calendar-multiselect-outline" 
              start
            ></v-icon>
              <span class="text-h6 text-medium-emphasis font-weight-bold" v-if="repeatType === 1"> По чисельнику </span>
              <span class="text-h6 text-medium-emphasis font-weight-bold" v-else-if="repeatType === 2"> По знаменнику </span>

            <div class="text-medium-emphasis font-weight-bold" v-else>{{ new Date(lesson.lesson_date).toLocaleDateString('uk-UA') }}</div>
          </v-layout>
          </div>
          <v-spacer></v-spacer>
            <v-btn icon="$close" size="large" variant="text" @click="$emit('close')"></v-btn>          
        </v-card-title>
        <v-divider></v-divider>

        <div class="py-2">
         
          <div class="text-h3 mt-3"> {{ lesson.lesson_name }}</div>

          <v-layout row align-center class="mt-2">
                <v-icon
                  color="#949cf7"
                  icon="mdi-identifier" 
                  start
                ></v-icon>

                <div class="font-weight-light text-medium-emphasis">{{ lesson.conference_id }}</div>
          </v-layout>
          
          <v-layout row align-center class="mt-2">
                <v-icon
                  color="#949cf7"
                  icon="mdi-lock-outline" 
                  start
                ></v-icon>

                <div class="font-weight-light text-medium-emphasis">{{ lesson.conference_password }}</div>
          </v-layout>

          <v-layout row align-center class="mt-2">
                <v-icon
                  color="#949cf7"
                  icon="mdi-human-male-board" 
                  start
                ></v-icon>

                <div class="font-weight-light text-medium-emphasis">{{ teacher.name }}</div>
          </v-layout>
          

        </div>
      </v-card-item>
    </v-card>
  </v-dialog>
</template>


<script>
export default {
    name: 'eventModal',
    props: {
        lesson: Object,
        dialog: Boolean,
    },
    data() {
      return {
        showDialog: this.dialog,
        role: '',
        repeatType: null,
        teacher: {}
      };
    },
    mounted() {
      this.getRole();
      this.updateRepeatMain();
      this.getCurrentTeacher();
      document.addEventListener('keydown', this.onEscKeyPressed);
    },

    beforeDestroy() {
      document.removeEventListener('keydown', this.onEscKeyPressed);
    },

    watch: {
      dialog(newVal) {
        this.showDialog = newVal;
        
      }
    },

    methods: {
      transformTime(time) {
        const [hours, minutes] = time.split(':');
        return `${hours}:${minutes}`;
        
      },

      updateRepeatMain() {
        if (this.lesson && this.lesson.type_of_week !== null) {          
          this.repeatType = this.lesson.type_of_week;
        } else {
          this.repeatType = null;
        }
      },

      getRole() {
            this.role = localStorage.getItem('role');
      },

      getCurrentTeacher() {
        const teacherId = this.lesson.teacher;
        axios.get(`api/get-current-teacher/${teacherId}`)
          .then(response => {
            this.teacher = response.data;
          });
      },
      
    // Обработчик события для закрытия модального окна при нажатии на клавишу "Escape"
    onEscKeyPressed(event) {
      if (event.key === 'Escape') {
        this.$emit('close', 'close');
      }
    }
    }
}

</script>
