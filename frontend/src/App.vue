<script setup>
import TaskManager from './components/TaskManager.vue'
import UserManagement from './components/UserManagement.vue'
</script>

<template>
  <div class="container">
    <h1>Gestión de la Aplicación</h1>
    <TaskManager />
    <UserManagement />
  </div>
</template>

<style scoped>
.container {
  max-width: 700px;
  margin: 0 auto;
  padding: 2rem;
  font-family: sans-serif;
}
</style>
