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
        height="400"
        width="600"
        style="overflow-y: auto"
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

          <v-checkbox-btn v-model="repeatMain" class="pe-2" label="Повторювати: " @click.stop></v-checkbox-btn>
          <v-radio-group
            v-model="repeatType"            
            v-if="repeatMain"
            inline
          >
          <v-radio value="1" label="по чисельнику" @click.stop></v-radio>
          <v-radio value="2" label="по знаменнику" @click.stop></v-radio>
          </v-radio-group>
          <v-select
            v-if="repeatMain"
            v-model="weekDay"
            :value="lesson.weekday ? getDayOfWeek(lesson.weekday) : 'День тижня..'"
            :items="days"
            item-value="value"
            item-title="text"
            @click.stop
          ></v-select>
          
          <v-text-field
          v-if="!repeatMain"
          v-model="formattedDateForm"
          :active="menu2"
          :focus="menu2"
          :label="lesson.lesson_date ? new Date(lesson.lesson_date).toLocaleDateString('uk-UA') : ''"
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

            <label>Викладач</label>
            <v-text-field
              v-model="lessonConfPass"
              :label="lesson.conference_password"
              single-line
              variant="underlined"
              density="compact"
              @click.stop
            ></v-text-field>
  
          </div>
          <div class="d-flex  justify-space-between align-center">
  <div>
    <v-btn height="48" variant="plain" density="compact">Скасувати</v-btn>
    <v-btn :loading="loading" color="blue" size="large" variant="tonal" height="48" type="submit">Редагувати</v-btn>
  </div>
  <div>
    <v-btn variant="plain" density="compact" color="error" icon="mdi-delete" @click.stop @click="openConfirmModal"></v-btn>
  </div>
</div>
<confirm-deleting-lesson @close="confirm_dialog = false" :dialog="confirm_dialog" :lesson="lesson" @update-lessons="$emit('update-lessons')"></confirm-deleting-lesson>

        </v-card-item>
        
        </v-form>
      </v-card>
    </v-dialog>
  </template>
  
  
  <script>
  import axios from 'axios';
import ConfirmDeletingLesson from './ConfirmDeletingLesson.vue';

  export default {
  components: { ConfirmDeletingLesson },
      name: 'eventEditModal',
      props: {
          lesson: Object,
          dialog: Boolean,
      },
      emits: [
        'update-lessons',
        'close'
      ],
      data() {
        return {
          confirm_dialog: false,
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
          repeatMain: true,
          repeatType: null,
          weekDay: null,
          timeRules: [
             v => !v || /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/.test(v) || 'Некоректний час'
          ],
          days: [
            { value: 1, text: "Понеділок" },
            { value: 2, text: "Вівторок" },
            { value: 3, text: "Середа" },
            { value: 4, text: "Четвер" },
            { value: 5, text: "П'ятниця" },
            { value: 6, text: "Субота" }
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

        openConfirmModal() {
            this.confirm_dialog = true;
        },

        submitForm() {
          let formData = {};
          if (this.startTime) formData.start_time = this.startTime;
          if (this.endTime) formData.end_time = this.endTime;
          // if (this.formattedDateDB) formData.lesson_date = this.formattedDateDB;
          if (this.lessonName) formData.lesson_name = this.lessonName;
          if (this.lessonConfId) formData.conference_id = this.lessonConfId;
          if (this.lessonConfPass) formData.conference_password = this.lessonConfPass;
          if (this.repeatMain) {
              formData.lesson_date = null;
              formData.type_of_week = this.repeatType;
              formData.weekday = this.weekDay;
          } else {
              formData.type_of_week = null;
              formData.weekday = null;
              formData.lesson_date = this.formattedDateDB;
          }

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
              if (this.repeatType) this.type_of_week = this.repeatType;
              if (this.weekDay) this.weekday = this.weekDay;

              this.$emit('update-lessons');
            })
            .catch(error => {
              console.error(error);
            });


        },
        getDayOfWeek(dayNumber) {
          const day = this.days.find(d => d.value === dayNumber);
          return day.text;
        },

        getRepeat() {

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
  