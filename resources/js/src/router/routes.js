// import Home from "@/pages/Home.vue";
// import WeekScheduleStudent from "@/pages/WeekScheduleStudent.vue";
// import DailyScheduleTeacher from "@/pages/DailyScheduleTeacher.vue";
import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";
import WeekCalendar from "../components/WeekCalendarComponent.vue";
import ExampleComponent from "../components/ExampleComponent.vue";
import EditProfile from "../pages/EditProfile.vue";

const routes = [
    {
        path: '/',
        component: ExampleComponent
    },

    {
        path: '/register',
        name: 'Register',
        component: Register
    },

    {
        path: '/login',
        name: 'Login',
        component: Login
    },

    {
        path: '/week-sch-student',
        component: WeekCalendar
    },

    {
        path: '/edit-profile',
        component: EditProfile
    },
];

export default routes;