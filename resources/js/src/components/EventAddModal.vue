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
        >
        </v-text-field>
  
             <v-spacer></v-spacer>
              <v-btn icon="$close" size="large" variant="text" @click="$emit('close')"></v-btn>
          </v-card-title>

          <v-checkbox-btn v-model="repeatMain" class="pe-2" label="Повторювати: "></v-checkbox-btn>
          <v-radio-group
            v-model="repeatType"
            v-if="repeatMain"
            inline
          >
            <v-radio :value="1" label="по чисельнику" ></v-radio>
            <v-radio :value="2" label="по знаменнику" ></v-radio>
          </v-radio-group>
          <v-select
            v-if="repeatMain"
            v-model="weekDay"
            value="День тижня.."
            :items="days"
            item-value="value"
            item-title="text"
           
          ></v-select>

          <v-text-field
          v-if="!repeatMain"
          v-model="formattedDateForm"
          :active="menu2"
          :focus="menu2"
          :label="new Date(day).toLocaleDateString('uk-UA')"
          prepend-icon="mdi-calendar"
          readonly

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
            ></v-text-field>

            <label>ID конференції</label>
            <v-text-field
              v-model="lessonConfId"
              single-line
              variant="underlined"
              density="compact"
            ></v-text-field>

            <label>Пароль конференції</label>
            <v-text-field
              v-model="lessonConfPass"
              single-line
              variant="underlined"
              density="compact"
            ></v-text-field>

            <label>Викладач</label>
            <v-select
              v-model="teacher"
              :items="allTeachers"
              :value = "teacher ? teacher : 'Оберіть викладача'"
              item-title="name"
              item-value="id"
              return-object
              single-line
            ></v-select>

            <label>Група</label>
            <v-select
              v-model="group"
              :items="allGroups"
              chips
              multiple
              
            ></v-select>
  
          </div>

          
          <v-btn height="48" variant="plain" density="compact" @click="$emit('close')"
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
          repeatMain: false,
          repeatType: 1,
          weekDay: null,
          teacher: {},
          allTeachers: [],
          group: [],
          allGroups: [],
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
        this.getTeachers();
        this.getGroups();
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
          if (this.teacher) formData.teacher = this.teacher.id;
          if (this.repeatMain) {
            formData.lesson_date = null;
            formData.type_of_week = this.repeatType;
            formData.weekday = this.weekDay;
          } else {
            formData.type_of_week = null;
            formData.weekday = null;
            formData.lesson_date = this.formattedDateDB;
          }

          const groupData = {
              groups: this.group
            };

          axios.post('/api/add-lesson', formData)
            .then(response => {
              axios.post(`/api/update-groups/${response.data.id}`, groupData)
              .then(resp => {
                this.$emit('close');
                this.$emit('update-lessons');
              })
              .catch(error => {
                console.error(error);
              });
            })
            .catch(error => {
              console.error(error);
            });
        },

        getTeachers() {
          axios.get('api/get-teachers')
            .then(response => {
              this.allTeachers = response.data;
          })
          .catch(error => {
            console.error(error);
          });
       },
      getGroups() {
        axios.get('api/get-groups')
          .then(response => {
            this.allGroups = response.data;
          })
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
        
      //   closeWithEvent(eventValue, event) {
      //   // Проверяем, был ли клик вне модального окна
      //   if (!this.$el.contains(event.target)) {
      //     this.$emit('close', eventValue);
      //   }
      // },
      // Обработчик события для закрытия модального окна при нажатии на клавишу "Escape"
      onEscKeyPressed(event) {
        if (event.key === 'Escape') {
          this.$emit('close', 'close');
        }
      },
      }
  }
  
  </script>
  