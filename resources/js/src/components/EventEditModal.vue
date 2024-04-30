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
            :label="transformTime(lesson.start_time)"
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
          :label="transformTime(lesson.end_time)"
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
          label="Picker in menu"
          prepend-icon="mdi-clock-time-four-outline"
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
              :label="lesson.lesson_name"
              single-line
              variant="underlined"
              density="compact"
              @click.stop
            ></v-text-field>

            <label>ID конференції</label>
            <v-text-field
              v-model="lessonConfId"
              :label="lesson.conference_id"
              single-line
              variant="underlined"
              density="compact"
              @click.stop
            ></v-text-field>

            <label>Пароль конференції</label>
            <v-text-field
              v-model="lessonConfPass"
              :label="lesson.conference_password"
              single-line
              variant="underlined"
              density="compact"
              @click.stop
            ></v-text-field>
  
          </div>
          <v-btn
            :loading="loading"
            class="mb-8" 
            color="blue" 
            size="large" 
            variant="tonal" 
            block 
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
          return this.date ? new Date(this.date).toLocaleDateString('en-CA') : null;
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

          const lessonId = this.lesson.id;
          axios.post(`/api/update/${lessonId}`, formData)
            .then(response => {
              console.log(response.data);
              if (this.startTime) this.lesson.start_time = this.startTime;
              if (this.endTime) this.lesson.end_time = this.endTime;
              if (this.formattedDateDB) this.lesson.lesson_date = this.formattedDateDB;
              if (this.lessonName) this.lesson.lesson_name = this.lessonName;
              if (this.lessonConfId) this.lesson.conference_id = this.lessonConfId;
              if (this.lessonConfPass) this.lesson.conference_password = this.lessonConfPass;
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
  