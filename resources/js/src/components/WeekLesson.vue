<template>
    <div v-for="(lesson, idx) in lessons" :key="idx">
            <v-btn @click="openModal(lesson)" :class="{ 'btn-lesson': true, 'btn-lesson-hover': role === 'admin' }">
                <span class="lesson-name">{{ lesson.lesson_name }}</span>
                <div class="time flex-column">
                    <span>{{ transformTime(lesson.start_time) }}</span>
                    <span>{{ transformTime(lesson.end_time) }}</span>
                </div>
                <v-icon v-if="role === 'admin'" class="icon" icon="mdi-pencil" end @click.stop="openEditModal(lesson)"></v-icon>
            </v-btn>
    </div>
    <eventModal @close="dialog = false" :dialog="dialog" :lesson="selectedLesson"></eventModal>
    <event-edit-modal @close="edit_dialog = false" :dialog="edit_dialog" :lesson="selectedLesson" @update-lessons="$emit('update-lessons')" />

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

.btn-lesson {
    margin-top: 10px;
    width: 99%;
    overflow: hidden;    
    display: flex;
    align-items: center;
    justify-content: space-between;
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