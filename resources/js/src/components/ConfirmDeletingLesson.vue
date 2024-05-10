<template>
    <v-dialog
        v-model="showDialog"
        width="auto"
        @click.stop
        @click="closeWithEvent('close', $event)"
      >
      <v-card
        class="ma-4"
        rounded="lg"
        variant="flat"
        prepend-icon="mdi-delete"
        text="Ви точно готові видалити цей урок?"
        title="Видалити урок"
      >
      <template v-slot:actions>
        <v-btn density="compact" @click.stop @click="$emit('close')">Ні</v-btn>
          <v-btn
            text="Так"
            color="error"
            @click.stop
            @click="deleteLesson"
          ></v-btn>
        </template>
    </v-card>
</v-dialog>
</template>

<script>
import axios from 'axios';

export default {
    name: 'confirmDeletingModal',
    props: {
        lesson: Object,
        dialog: Boolean,
    },
    emits: [
        'close',
        'update-lessons',
    ],
      data() {
        return {
          showDialog: this.dialog,
        }
    },

    mounted() {
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
        deleteLesson() {
            const lessonId = this.lesson.id;
            axios.post(`/api/delete-lesson/${lessonId}`)
                .then(response => {
                    this.$emit('update-lessons');
                    this.$emit('close');
                })
                .catch(error => {
                    console.error(error);
                });

        },
        

      closeWithEvent(eventValue, event) {
        // Проверяем, был ли клик вне модального окна
        if (!this.$el.contains(event.target)) {
          this.$emit('close', eventValue);
        }
      }, 
    }

}

</script>