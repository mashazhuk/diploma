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
        <v-card-title class="text-body-2 d-flex align-center">
          <v-icon
            color="#949cf7"
            icon="mdi-clock-time-four-outline" 
            start
          ></v-icon>

          <span class="text-medium-emphasis font-weight-bold">{{ transformTime(lesson.start_time) }} - {{ transformTime(lesson.end_time) }}</span>
          <v-spacer></v-spacer>
            <v-btn icon="$close" size="large" variant="text" @click="$emit('close')"></v-btn>
        </v-card-title>

        <div class="py-2">
          <div v-if="repeatType === 1" class="text-h6"> По чисельнику </div>
          <div v-if="repeatType === 2" class="text-h6"> По знаменнику </div>
          <div v-else class="text-h6">22 жовтня</div>
          <div class="text-h6"> {{ lesson.lesson_name }}</div>

          <div class="font-weight-light text-medium-emphasis">
            {{ lesson.conference_id }}
          </div>

          <div class="font-weight-light text-medium-emphasis">
            {{ lesson.conference_password }}
          </div>

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
      };
    },
    mounted() {
      this.getRole();
      this.updateRepeatMain();
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
      
    // Обработчик события для закрытия модального окна при нажатии на клавишу "Escape"
    onEscKeyPressed(event) {
      if (event.key === 'Escape') {
        this.$emit('close', 'close');
      }
    }
    }
}

</script>
