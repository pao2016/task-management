<script setup>
import TaskManager from './components/TaskManager.vue'
</script>

<template>
  <div class="container">
    <h1>Gestión de Tareas</h1>
    <TaskManager />
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
