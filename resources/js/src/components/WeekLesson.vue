<!-- <template>
    <div v-for="(lesson, idx) in lessons" :key="idx">
            <v-btn v-if="lesson.start_time === '09:00:00'" @click="openModal(lesson)" :variant="lesson.lesson_date ? 'tonal' : 'elevated'" :class="{ 'btn-lesson': true, 'btn-lesson-hover': role === 'admin' }">
                <span class="lesson-name">{{ lesson.lesson_name }}</span>
                <div class="time flex-column">
                    <span>{{ transformTime(lesson.start_time) }}</span>
                    <span v-if="lesson.end_time">{{ transformTime(lesson.end_time) }}</span>
                </div>
                <v-icon v-if="role === 'admin'" class="icon" icon="mdi-pencil" end @click.stop="openEditModal(lesson)"></v-icon>
            </v-btn>
            <v-btn v-if="lesson.start_time === '09:30:00'" @click="openModal(lesson)" :variant="lesson.lesson_date ? 'tonal' : 'elevated'" :class="{ 'btn-lesson': true, 'btn-lesson-hover': role === 'admin' }">
                <span class="lesson-name">{{ lesson.lesson_name }}</span>
                <div class="time flex-column">
                    <span>{{ transformTime(lesson.start_time) }}</span>
                    <span v-if="lesson.end_time">{{ transformTime(lesson.end_time) }}</span>
                </div>
                <v-icon v-if="role === 'admin'" class="icon" icon="mdi-pencil" end @click.stop="openEditModal(lesson)"></v-icon>
            </v-btn>
    </div>
    <eventModal v-if="dialog" @close="dialog = false" :dialog="dialog" :lesson="selectedLesson"></eventModal>
    <event-edit-modal v-if="edit_dialog" @close="edit_dialog = false" :dialog="edit_dialog" :lesson="selectedLesson" @update-lessons="$emit('update-lessons')" />

</template> -->

<template>
    <div v-for="(slot, idx) in prepareTimeSlots()" :key="idx" >
    <div height="40px" v-if="slot.lesson" >
      <v-btn           
        @click="openModal(slot.lesson)" 
        :variant="slot.lesson.lesson_date ? 'tonal' : 'elevated'" 
        :class="{ 'btn-lesson': true, 'btn-lesson-hover': role === 'admin' }"
      >
        <span class="num-of-lesson">{{ idx+1 }}</span>
        <span class="lesson-name">{{ slot.lesson.lesson_name }}</span>
        <div class="time flex-column">
          <span>{{ transformTime(slot.lesson.start_time) }}</span>
          <span v-if="slot.lesson.end_time">{{ transformTime(slot.lesson.end_time) }}</span>
        </div>
        <v-icon 
          v-if="role === 'admin'" 
          class="icon" 
          icon="mdi-pencil" 
          end 
          @click.stop="openEditModal(slot.lesson)"
        ></v-icon>
      </v-btn>
      </div>

      <div v-else class="empty-slot">
        {{idx + 1}}
      </div>
    </div>
    <eventModal v-if="dialog" @close="dialog = false" :dialog="dialog" :lesson="selectedLesson"></eventModal>
    <event-edit-modal v-if="edit_dialog" @close="edit_dialog = false" :dialog="edit_dialog" :lesson="selectedLesson" @update-lessons="$emit('update-lessons')" />
  </template>
  
    
<script>
import eventModal from './EventModal.vue';
import EventEditModal from './EventEditModal.vue';
export default {
    components: {
        eventModal,
        EventEditModal
    },
    name: 'WeekLesson',
    emits: ['update-lessons'],
    data() {
        return {
            selectedLesson: {},
            dialog: false,
            edit_dialog: false,
            role: ''
        }
    },

    props: {
        lessons: Object,
        typeOfWeek: Number
    },

    mounted() {
        this.getRole();
    },
    methods: {

        prepareTimeSlots() {
    const timeSlots = [
      { time: '08:00:00', lesson: null },
      { time: '09:35:00', lesson: null },
      { time: '11:25:00', lesson: null },
      { time: '12:55:00', lesson: null },
      { time: '14:30:00', lesson: null },
      { time: '16:05:00', lesson: null },
      { time: '17:35:00', lesson: null },
    ];

    Object.keys(this.lessons).forEach(key => {
      const lesson = this.lessons[key];
      const slot = timeSlots.find(slot => slot.time === lesson.start_time);
      if (slot) {
        slot.lesson = lesson;
      }
    });

    return timeSlots;
  },
        openModal(lesson) {
            this.selectedLesson = lesson;
            this.dialog = true;
        },

        openEditModal(lesson) {
            this.selectedLesson = lesson;
            this.edit_dialog = true;
        },

        transformTime(time) {
            const [hours, minutes] = time.split(':');
            return `${hours}:${minutes}`;
        },
        getRole() {
            this.role = localStorage.getItem('role');
        },
    }
}
</script>

<style lang="scss" scoped>

.empty-slot {
  height: 40px; /* Примерная высота для пустого слота */
  border-bottom: 0.1px solid;
  display: flex;
  align-items: center;
  font-size: xx-small;
}

.btn-lesson {
    margin-top: 10px;
    width: 99%;
    overflow: hidden;    
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #272727;
}

.btn-lesson-hover {
    .time { 
        transition: opacity 0.2s ease-in-out;  
    }

    .icon {
        transition: opacity 0.2s ease-in-out;
    }


    &:hover {
        .time {
            opacity: 0;
        }
        .icon {
            opacity: 1;
        }
    }
}

.lesson-name {
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    position: absolute;
    left: 0;
    right: 40px;
}
.num-of-lesson {
    position: absolute;
    left: 0;
    font-size: xx-small;
}

.time {
    position: absolute;
    right: 0;
    display: flex;
    align-items: flex-end;  
}

.time span {
    font-size: 0.8em;
}

.icon {
    opacity: 0;
    position: absolute;
    right: 10px;
    // transition: opacity 0.2s ease-in-out;

    // &:hover {
    //     color: black;
    // }
}
</style>