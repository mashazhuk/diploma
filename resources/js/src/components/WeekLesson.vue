<template>
    <div v-for="(lesson, idx) in lessons" :key="idx">
        <v-btn @click="openModal(lesson)" class="btn-lesson">
            <v-icon class="btn-icon">mdi-pencil</v-icon>
            <span class="lesson-name">{{ lesson.lesson_name }}</span>
            <div class="time flex-column">
                <span>{{ transformTime(lesson.start_time) }}</span>
                <span>{{ transformTime(lesson.end_time) }}</span>
            </div>
        </v-btn>
    </div>
    <eventModal @close="dialog = false" :dialog="dialog" :lesson="selectedLesson"></eventModal>
</template>
    
<script>
import eventModal from './EventModal.vue';
export default {
    components: {
        eventModal
    },
    name: 'WeekLesson',
    data() {
        return {
            selectedLesson: {},
            dialog: false,
            role: ''
        }
    },

    props: {
        lessons: Array,
    },

    mounted() {
        this.getRole();
    },
    methods: {
        openModal(lesson) {
            this.selectedLesson = lesson;
            this.dialog = true;
            console.log(this.selectedLesson)
        },
        transformTime(dateTime) {
            let date = new Date(dateTime);
            let hours = date.getHours();
            let minutes = date.getMinutes();
            return hours + ":" + minutes;        
        },
        getRole() {
            this.role = localStorage.getItem('role');
        }
    }
}
</script>

<style scoped>

.btn-lesson {
    margin-top: 10px;
    width: 100%;
    overflow: hidden;    
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.btn-icon {
    display: none;
}

.btn-lesson:hover .btn-icon {
    display: contents;
}

.btn-icon::after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    box-shadow: 0 0 10px #ef0101;
}

.lesson-name {
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    position: absolute;
    left: 0;
    right: 30px;
}

.time {
    position: absolute;
    right: 0;
    display: flex;
    align-items: flex-end;    
}

.time span {
    font-size: 0.8em;
    color: #454444
}

/* .d-flex {
    
}
.justify-space-between {
    
}
.align-center {
    
}
.align-end {
    align-items: flex-end;
}
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.flex-grow-1 {
    flex-grow: 1;
} */
</style>