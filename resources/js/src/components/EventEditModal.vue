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
              :label="transformTime(lesson.start_time)"
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
              :label="transformTime(lesson.end_time)"
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
            :value="weekDay"
            :items="days"
            item-value="value"
            item-title="text"           
          ></v-select>

          <v-text-field
            v-if="!repeatMain"
            v-model="formattedDateForm"
            :active="menu2"
            :focus="menu2"
            :label="lesson.lesson_date ? new Date(lesson.lesson_date).toLocaleDateString('uk-UA') : ''"
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
              :label="lesson.lesson_name"
              single-line
              variant="underlined"
              density="compact"
             
            ></v-text-field>

            <label>ID конференції</label>
            <v-text-field
              v-model="lessonConfId"
              :label="lesson.conference_id"
              single-line
              variant="underlined"
              density="compact"
              
            ></v-text-field>
            <label>Пароль конференції</label>
            <v-text-field
              v-model="lessonConfPass"
              :label="lesson.conference_password"
              single-line
              variant="underlined"
              density="compact"
              
            ></v-text-field>

            <label>Викладач</label>
            <v-select
              v-model="teacher"
              :items="allTeachers"
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
          <div class="d-flex  justify-space-between align-center">
            <div>
              <v-btn height="48" variant="plain" density="compact" @click="$emit('close')">Скасувати</v-btn>
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
    this.getGroups();
    this.getGroupName();
    this.getTeachers();
    this.getCurrentTeacher();
    this.updateRepeatMain();
    this.getDayOfWeek();
    // this.changeDate();
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

    // changeDate() {
    //   this.date = this.lesson.date;
    //   this.date = this.date.toLocaleDateString('uk-UA');
    // },

    openConfirmModal() {
      this.confirm_dialog = true;
    },

    updateRepeatMain() {
      if (this.lesson && this.lesson.type_of_week !== null) {
        this.repeatMain = true;
        this.repeatType = this.lesson.type_of_week;
      } else {
        this.repeatMain = false;
        this.repeatType = null;
      }
    },

    getDayOfWeek() {
      let dayNumber = this.lesson.weekday;
      this.weekDay = this.days.find(d => d.value === dayNumber);
      
    },

    submitForm() {
      let formData = {};
      if (this.startTime) formData.start_time = this.startTime;
      if (this.endTime) formData.end_time = this.endTime;
      if (this.lessonName) formData.lesson_name = this.lessonName;
      if (this.lessonConfId) formData.conference_id = this.lessonConfId;
      if (this.lessonConfPass) formData.conference_password = this.lessonConfPass;
      formData.teacher= this.teacher.id;
      if (this.repeatMain) {
        formData.lesson_date = null;
        formData.type_of_week = this.repeatType;
        formData.weekday = this.weekDay.value;
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
        })
        .catch(error => {
          console.log('Form data', formData);
          console.error(error);
        });

      const groupData = {
        groups: this.group
      };

      axios.post(`/api/update-groups/${lessonId}`, groupData)
        .then(response => {
          this.$emit('close');
          this.$emit('update-lessons');
        })
        .catch(error => {
          console.log(groupData);
          console.error(error);
        });
    },

    getGroupName() {
      const lessonId = this.lesson.id;
      axios.get(`api/get-group-name/${lessonId}`)
        .then(response => {
          this.group = response.data;
        });
    },

    getGroups() {
      axios.get('api/get-groups')
        .then(response => {
          this.allGroups = response.data;
        })
    },

    getTeachers() {
      axios.get('api/get-teachers')
        .then(response => {
          this.allTeachers = response.data;
          console.log(this.allTeachers);
      })
      .catch(error => {
        console.error(error);
     });
      },
      
      getCurrentTeacher() {
        const teacherId = this.lesson.teacher;
        axios.get(`api/get-current-teacher/${teacherId}`)
          .then(response => {
            this.teacher = response.data;
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

    // closeWithEvent(eventValue, event) {
    //   if (!this.$el.contains(event.target)) {
    //     this.$emit('close', eventValue);
    //   }
    // },

    onEscKeyPressed(event) {
      if (event.key === 'Escape') {
        this.$emit('close', 'close');
      }
    },
  }
}
</script>
