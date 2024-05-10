<template>
    <v-dialog
        v-model="showDialog"
        width="auto"
        @click="closeWithEvent('close', $event)"
      >
      <v-card
        class="ma-4"
        rounded="lg"
        variant="flat"
        max-width="650"
        min-height="300"
        width="600"
      >
      <v-form
        @submit.prevent="submitForm"

        >
        <v-card-item>
          <v-card-title class="text-body-2 d-flex align-center">
            <!-- Start time field -->
            <v-text-field
            v-model="startTime"
            prepend-icon="mdi-clock-time-four-outline"
            single-line
            density="compact"
            variant="underlined"
            :rules="timeRules"
            @click.stop

            @input="autoInsertColonStart"
          >
        </v-text-field>

        <!-- End time field -->
        <v-text-field
          v-model="endTime"
          prepend-icon="mdi-minus"
          single-line
          variant="underlined"
          density="compact"
          :rules="timeRules"
          @input="autoInsertColonEnd"
          @click.stop
        >
        </v-text-field>
  
             <v-spacer></v-spacer>
              <v-btn icon="$close" size="large" variant="text" @click="$emit('close')"></v-btn>
          </v-card-title>

          <v-text-field
          v-model="formattedDateForm"
          :active="menu2"
          :focus="menu2"
          :label="new Date(day).toLocaleDateString('uk-UA')"
          prepend-icon="mdi-calendar"
          readonly
          @click.stop

        >
        <v-menu
            v-model="menu2"
            :close-on-content-click="false"
            activator="parent"
            transition="scale-transition"
          >
          <v-date-picker
              elevation="24"
              v-if="menu2"
              v-model="date"
              full-width
              lang="ru"
            ></v-date-picker>
          </v-menu>
          </v-text-field>
          
          <div class="py-2">
          <label>Назва уроку</label>
            <v-text-field
              v-model="lessonName"
              single-line
              variant="underlined"
              density="compact"
              @click.stop
            ></v-text-field>

            <label>ID конференції</label>
            <v-text-field
              v-model="lessonConfId"
              single-line
              variant="underlined"
              density="compact"
              @click.stop
            ></v-text-field>

            <label>Пароль конференції</label>
            <v-text-field
              v-model="lessonConfPass"
              single-line
              variant="underlined"
              density="compact"
              @click.stop
            ></v-text-field>
  
          </div>

          
          <v-btn height="48" variant="plain" density="compact" 
            >
            Скасувати
          </v-btn>
          <v-btn
            :loading="loading"
            class="flex-grow-1" 
            color="blue" 
            size="large" 
            variant="tonal" 
            height="48"
            type="submit"
            >
            Редагувати
          </v-btn>

        </v-card-item>
        
        </v-form>
      </v-card>
    </v-dialog>
  </template>
  
  
  <script>
  import axios from 'axios';

  export default {
      name: 'eventAddModal',
      props: {
          day: Object,
          dialog: Boolean,
      },
      emits: [
        'update-lessons',
        'close'
      ],
      data() {
        return {
          showDialog: this.dialog,
          startTime: null,
          endTime: null,
          modalStartTime: false,
          modalEndTime: false,
          lessonName: null,
          lessonConfId: null,
          lessonConfPass: null,
          loading: false,
          date: null,
          menu2: false,
          timeRules: [
             v => !v || /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(v) || 'Некоректний час'
          ],
        };
      },
      mounted() {
        document.addEventListener('keydown', this.onEscKeyPressed);
      },
  
      beforeDestroy() {
        document.removeEventListener('keydown', this.onEscKeyPressed);
      },

      computed: {
        formattedDateForm() {
          return this.date ? new Date(this.date).toLocaleDateString('uk-UA') : null;
        },

        formattedDateDB() {
          return this.day ? new Date(this.day).toLocaleDateString('en-CA') : null;
        }
      },
  
      watch: {
        dialog(newVal) {
          this.showDialog = newVal;          
        }
      },
  
      methods: {

        submitForm() {
          let formData = {};
          if (this.startTime) formData.start_time = this.startTime;
          if (this.endTime) formData.end_time = this.endTime;
          if (this.formattedDateDB) formData.lesson_date = this.formattedDateDB;
          if (this.lessonName) formData.lesson_name = this.lessonName;
          if (this.lessonConfId) formData.conference_id = this.lessonConfId;
          if (this.lessonConfPass) formData.conference_password = this.lessonConfPass;
          formData.type_of_week = 1;

          axios.post('/api/add-lesson', formData)
            .then(response => {
              console.log(response.data);
              this.$emit('update-lessons');
            })
            .catch(error => {
              console.error(error);
            });


        },

        transformTime(time) {
          const [hours, minutes] = time.split(':');
          return `${hours}:${minutes}`;
        },
        autoInsertColonStart(event) {
          if (event.target.value.length === 2 && event.target.value.indexOf(':') === -1) {
            this.startTime = event.target.value + ':';
          }
        },
        autoInsertColonEnd(event) {
          if (event.target.value.length === 2 && event.target.value.indexOf(':') === -1) {
            this.endTime = event.target.value + ':';
          }
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
  