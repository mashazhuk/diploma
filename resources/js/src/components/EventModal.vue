<template>
  <v-dialog
      v-model="showDialog"
      width="auto"
      @click="closeWithEvent('close', $event)"
    >
    <v-card
      class="ma-4"
      color="#2f3136"
      rounded="lg"
      variant="flat"
      max-width="650"
      min-height="300"
      width="400"
      theme="dark"
    >
    
      <v-card-item>
        <v-card-title class="text-body-2 d-flex align-center">
          <v-icon
            color="#949cf7"
            icon="mdi-calendar"
            start
          ></v-icon>

          <span class="text-medium-emphasis font-weight-bold">{{ transformTime(lesson.start_time) }} - {{ transformTime(lesson.end_time) }}</span>
          <v-spacer></v-spacer>
            <v-btn icon="$close" size="large" variant="text" @click="$emit('close')"></v-btn>
        </v-card-title>

        <div class="py-2">
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
      };
    },
    mounted() {
      this.getRole();
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
      transformTime(dateTime) {
        let date = new Date(dateTime);
        let hours = date.getHours();
        let minutes = date.getMinutes();
        return hours + ":" + minutes;
        
      },

      getRole() {
            this.role = localStorage.getItem('role');
      },
      closeWithEvent(eventValue, event) {
      // Проверяем, был ли клик вне модального окна
      if (!this.$el.contains(event.target)) {
        this.$emit('close', eventValue);
      }
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
