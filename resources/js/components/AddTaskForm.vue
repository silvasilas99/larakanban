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
  <form
    class="relative mb-3 flex flex-col justify-between bg-white rounded-md shadow overflow-hidden"
    @submit.prevent="handleAddNewTask"
  >
    <div class="p-3 flex-1">
      <input
        class="block w-full px-2 py-1 text-lg border-b rounded"
        type="text"
        placeholder="Insira o título"
        v-model.trim="newTask.title"
      />
      <textarea
        class="mt-3 p-2 block w-full p-1 border text-sm rounded"
        rows="2"
        placeholder="Adicione a descrição (opcional)"
        v-model.trim="newTask.description"
      ></textarea>
      <div v-show="errorMessage">
        <span class="text-xs text-red-500">
          {{ errorMessage }}
        </span>
      </div>
    </div>
    <div class="p-3 flex justify-between items-end text-sm bg-gray-100">
      <button
        @click="$emit('task-canceled')"
        type="reset"
        class="py-1 leading-5 text-gray-600 hover:text-gray-700"
      >
        Cancelar
      </button>
      <button
        type="submit"
        class="py-1 leading-5 text-gray-600 hover:text-gray-700"
      >
        Salvar
      </button>
    </div>
  </form>
</template>

<script>
export default {
  props: {
    statusId: Number
  },
  data() {
    return {
      newTask: {
        title: "",
        description: "",
        status_id: null
      },
      errorMessage: ""
    };
  },
  mounted() {
    this.newTask.status_id = this.statusId;
  },
  methods: {
    handleAddNewTask() {
      // Basic validation so we don't send an empty task to the server
      if (!this.newTask.title) {
        this.errorMessage = "The title field is required";
        return;
      }

      // Send new task to server
      axios
        .post("/tasks", this.newTask)
        .then(res => {
          // Tell the parent component we've added a new task and include it
          this.$emit("task-added", res.data);
        })
        .catch(err => {
          // Handle the error returned from our request
          this.handleErrors(err);
        });
    },
    handleErrors(err) {
      if (err.response && err.response.status === 422) {
        // We have a validation error
        const errorBag = err.response.data.errors;
        if (errorBag.title) {
          this.errorMessage = errorBag.title[0];
        } else if (errorBag.description) {
          this.errorMessage = errorBag.description[0];
        } else {
          this.errorMessage = err.response.message;
        }
      } else {
        // We have bigger problems
        console.log(err.response);
      }
    }
  }
};
</script>
