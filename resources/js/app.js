require('./bootstrap');

window.Vue = require('vue').default;

Vue.component("kanban-board", require("./components/KanbanBoard.vue").default);


const app = new Vue({
    el: "#app"
});
