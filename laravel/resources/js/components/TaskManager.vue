<template>
  <div class="task-container">
    <h2>📋 Lista de Tareas</h2>

    <ul>
      <li v-for="task in tasks" :key="task.id" class="task-item">
        <strong>{{ task.title }}</strong> - 
        <em>{{ task.status }}</em> | 
        <span>👤 {{ task.user?.name || 'Sin usuario' }}</span>
        <button @click="deleteTask(task.id)">🗑 Eliminar</button>
      </li>
    </ul>

    <h3>➕ Crear tarea</h3>
    <form @submit.prevent="createTask">
      <input v-model="newTask.title" placeholder="Título" required />
      <select v-model="newTask.status">
        <option value="pendiente">Pendiente</option>
        <option value="en_proceso">En proceso</option>
        <option value="completada">Completada</option>
      </select>
      <select v-model="newTask.user_id">
        <option disabled value="">Seleccione usuario</option>
        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
      </select>
      <button type="submit">Crear</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tasks: [],
      users: [],
      newTask: {
        title: "",
        status: "pendiente",
        user_id: ""
      }
    };
  },
  methods: {
    fetchTasks() {
      fetch("/api/tasks")
        .then(res => res.json())
        .then(data => (this.tasks = data));
    },
    fetchUsers() {
      fetch("/api/users") // <- crea una ruta en Laravel que devuelva User::all()
        .then(res => res.json())
        .then(data => (this.users = data));
    },
    createTask() {
      fetch("/api/tasks", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json"
        },
        body: JSON.stringify(this.newTask)
      })
        .then(() => {
          this.newTask = { title: "", status: "pendiente", user_id: "" };
          this.fetchTasks();
        });
    },
    deleteTask(id) {
      fetch(`/api/tasks/${id}`, { method: "DELETE" }).then(() =>
        this.fetchTasks()
      );
    }
  },
  mounted() {
    this.fetchTasks();
    this.fetchUsers();
  }
};
</script>

<style>
.task-container {
  padding: 2rem;
  font-family: sans-serif;
}
.task-item {
  margin-bottom: 1rem;
  padding: 0.5rem;
  background: #f3f4f6;
  border-radius: 5px;
}
button {
  margin-left: 1rem;
  background: #ef4444;
  color: white;
  border: none;
  padding: 0.3rem 0.6rem;
  border-radius: 3px;
}
</style>
