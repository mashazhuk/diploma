// import Home from "@/pages/Home.vue";
// import WeekScheduleStudent from "@/pages/WeekScheduleStudent.vue";
// import DailyScheduleTeacher from "@/pages/DailyScheduleTeacher.vue";
import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";
import WeekCalendar from "../components/WeekCalendarComponent.vue";
import Example from "../components/ExampleComponent.vue";

const routes = [
    {
        path: '/',
        component: Example
    },
    
    // {
    //     path: '/week-sch-student',
    //     component: WeekScheduleStudent
    // },

    // {
    //     path: '/daily-sch-teacher',
    //     component: DailyScheduleTeacher
    // },

    {
        path: '/register',
        component: Register
    },

    {
        path: '/login',
        component: Login
    },

    {
        path: '/week-sch-student',
        component: WeekCalendar
    }
];

export default routes;