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

            <v-text-field
          v-model="startTime"
          :active="modalStartTime"
          :focused="modalStartTime"
          label="Початок"
          prepend-icon="mdi-clock-time-four-outline"
          readonly
        >
          <v-dialog v-model="modalStartTime" activator="parent" width="auto">
            <v-time-picker 
            v-if="modalStartTime" 
            v-model="startTime"
            format="24hr"
            scrollable
            ></v-time-picker>
          </v-dialog>
        </v-text-field>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="endTime"
          :active="modalEndTime"
          :focused="modalEndTime"
          label="Кінець"
          prepend-icon="mdi-minus"
          readonly
        >
          <v-dialog v-model="modalEndTime" activator="parent" width="auto">
            <v-time-picker 
            v-if="modalEndTime" 
            v-model="endTime"
            format="24hr"
            scrollable
            ></v-time-picker>
          </v-dialog>
        </v-text-field>
  
            <!-- <span class="text-medium-emphasis font-weight-bold">{{ transformTime(lesson.start_time) }} - {{ transformTime(lesson.end_time) }}</span> -->
            <v-spacer></v-spacer>
              <v-btn icon="$close" size="large" variant="text" @click="$emit('close')"></v-btn>
          </v-card-title>
  
          <div class="py-2">
            <div class="text-h6"> {{ lesson.lesson_name }}</div>
  
            <div class="font-weight-light text-medium-emphasis">
              Hellooooo
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
      name: 'eventEditModal',
      props: {
          lesson: Object,
          dialog: Boolean,
      },
      data() {
        return {
          showDialog: this.dialog,
          startTime: null,
          endTime: null,
          modalStartTime: false,
          modalEndTime: false,
        };
      },
      mounted() {
        document.addEventListener('keydown', this.onEscKeyPressed);
        console.log(this.dialog);
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
      },
      }
  }
  
  </script>
  