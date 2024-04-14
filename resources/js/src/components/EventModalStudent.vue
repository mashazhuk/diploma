<template>
  <v-dialog
      v-model="showDialog"
      width="auto"
    >
    <v-card
      class="ma-4"
      color="#2f3136"
      rounded="lg"
      variant="flat"
      max-width="650"
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

      <!-- <v-divider></v-divider>

        <v-spacer></v-spacer>
      <div class="pa-4 d-flex align-center">
        <v-btn
          class="text-none"
          prepend-icon="mdi-check"
          variant="text"
          border
          @click="$emit('close')"
        >
          Готово
        </v-btn>
      </div> -->
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
        showDialog: this.dialog
      };
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
        console.log(this.dialog);
        return hours + ":" + minutes;
        
      }
    }
}

</script>
