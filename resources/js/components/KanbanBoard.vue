<!-----------------------------------------------------------------------
<Larakanban>
Copyright (C) <2021>  <Silas S. da Silva>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
------------------------------------------------------------------------->

<template>
  <div class="relative p-2 flex overflow-x-auto h-full">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Columns (Statuses) -->
    <div
        v-for="status in statuses"
        :key="status.slug"
        class="mr-6 w-4/5 max-w-xs flex-1 flex-shrink-0"
    >
      <div class="rounded-md shadow-md overflow-hidden">
        <div class="p-3 flex justify-center items-baseline" style="background: #6875F5">
            <h4 class="font-medium text-white font-bold" style="text-transform: uppercase">
                {{ status.title }}
            </h4>
        </div>
        <div class="p-2" style="background: rgb(162,168,225,0.2)"> <!-- flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto -->
            <AddTaskForm
                v-if="newTaskForStatus === status.id"
                :status-id="status.id"
                v-on:task-added="handleTaskAdded"
                v-on:task-canceled="closeAddTaskForm"
            />
            <!-- Tasks -->
            <draggable
                class="flex-1 overflow-hidden"
                v-model="status.tasks"
                v-bind="taskDragOptions"
                @end="handleTaskMoved"
            >

                <transition-group
                    class="flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto rounded shadow-xs"
                    tag="div"
                >
                    <div
                        v-for="task in status.tasks"
                        :key="task.id"
                        class="mb-3 p-3 h-24 flex flex-col bg-white rounded-md shadow transform hover:shadow-md cursor-pointer"
                    >
                        <span class="block mb-2 text-xl" style="color: #6875F5">
                            {{ task.title }}
                        </span>
                        <p class="text-gray-700 truncate">
                            {{ task.description }}
                        </p>
                    </div>
                    <!-- ./Tasks -->
                </transition-group>
            </draggable>


            <!-- No Tasks -->
            <div
                v-show="!status.tasks.length && newTaskForStatus !== status.id"
                class="flex-1 p-4 flex flex-col items-center justify-center"
            >
                <!-- <h3 class="mt-1" style="color: #2E378F">
                    Sem tarefas
                </h3> -->

            </div>
            <!-- ./No Tasks -->

            <!-- Add button -->
            <div class="md:pl-20">
            <button
                class="md:mt-1 text-sm text-white hover:underline"
                @click="openAddTaskForm(status.id)"
            >
                <div class="flex items-center justify-center rounded-md shadow-md overflow-hidden md:w-32" style="background: #5C69DB">

                    <div class="p-1 flex justify-center items-center">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                </div>
            </button>
            </div>

            <!-- /Add button -->

        </div>
      </div>
    </div>
    <!-- ./Columns -->
  </div>
</template>

<script>
import AddTaskForm from "./AddTaskForm"; // import the component
import draggable from "vuedraggable"; // import the vuedraggable component

export default {
    components: { draggable, AddTaskForm },
    props: {
        initialData: Array
    },
    data() {
        return {
        statuses: [],

        newTaskForStatus: 0 // track the ID of the status we want to add to
        };
    },
    computed: {
        taskDragOptions() {
            return {
                animation: 200,
                group: "task-list",
                dragClass: "status-drag"
            };
        }
    },
    mounted() {
        // 'clone' the statuses so we don't alter the prop when making changes
        this.statuses = JSON.parse(JSON.stringify(this.initialData));
    },
    methods: {
    // set the statusId and trigger the form to show
    openAddTaskForm(statusId) {
      this.newTaskForStatus = statusId;
    },
    // reset the statusId and close form
    closeAddTaskForm() {
      this.newTaskForStatus = 0;
    },
    // add a task to the correct column in our list
    handleTaskAdded(newTask) {
      // Find the index of the status where we should add the task
      const statusIndex = this.statuses.findIndex(
        status => status.id === newTask.status_id
      );

      // Add newly created task to our column
      this.statuses[statusIndex].tasks.push(newTask);

      // Reset and close the AddTaskForm
      this.closeAddTaskForm();
    },
    handleTaskMoved() {
      // Send the entire list of statuses to the server
      axios.put("/tasks/sync", {columns: this.statuses}).catch(err => {
        console.log(err.response);
      });
    }
  }
};
</script>


<style scoped>
    .status-drag {
        transition: transform 0.5s;
        transition-property: all;
    }
</style>
