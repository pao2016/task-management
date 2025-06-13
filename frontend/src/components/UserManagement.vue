<template>
  <div class="user-management">
    <h2>Gestión de Usuarios</h2>

    <!-- Formulario para crear/editar usuario -->
    <form @submit.prevent="saveUser" class="user-form">
      <input type="text" v-model="currentUser.name" placeholder="Nombre" required />
      <input type="email" v-model="currentUser.email" placeholder="Email" required />
      <input type="password" v-model="currentUser.password" placeholder="Contraseña" :required="!currentUser.id" />
      <button type="submit">{{ currentUser.id ? 'Actualizar Usuario' : 'Crear Usuario' }}</button>
      <button type="button" @click="cancelEdit" v-if="currentUser.id">Cancelar</button>
    </form>

    <!-- Lista de usuarios -->
    <ul class="user-list">
      <li v-for="user in users" :key="user.id">
        <span>{{ user.name }} ({{ user.email }})</span>
        <div>
          <button @click="editUser(user)">Editar</button>
          <button @click="deleteUser(user.id)">Eliminar</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import api from '../services/api';

export default {
  name: 'UserManagement',
  data() {
    return {
      users: [],
      currentUser: {
        id: null,
        name: '',
        email: '',
        password: '',
      },
    };
  },
  created() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await api.get('/users');
        this.users = response.data;
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    },
    async saveUser() {
      try {
        if (this.currentUser.id) {
          // Actualizar usuario
          await api.put(`/users/${this.currentUser.id}`, this.currentUser);
        } else {
          // Crear nuevo usuario
          await api.post('/users', this.currentUser);
        }
        this.resetForm();
        this.fetchUsers();
      } catch (error) {
        console.error('Error saving user:', error);
        if (error.response && error.response.data.errors) {
            alert(Object.values(error.response.data.errors).flat().join('\n'));
        } else {
            alert('Error al guardar el usuario.');
        }
      }
    },
    editUser(user) {
      this.currentUser = { ...user, password: '' }; // No precargar la contraseña
    },
    async deleteUser(id) {
      if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
        try {
          await api.delete(`/users/${id}`);
          this.fetchUsers();
        } catch (error) {
          console.error('Error deleting user:', error);
        }
      }
    },
    cancelEdit() {
      this.resetForm();
    },
    resetForm() {
      this.currentUser = {
        id: null,
        name: '',
        email: '',
        password: '',
      };
    },
  },
};
</script>

<style scoped>
.user-management {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-form {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}

.user-form input {
  flex: 1;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.user-form button {
  padding: 8px 15px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.user-form button:hover {
  background-color: #0056b3;
}

.user-list {
  list-style: none;
  padding: 0;
}

.user-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  border-bottom: 1px solid #eee;
}

.user-list li:last-child {
  border-bottom: none;
}

.user-list button {
  margin-left: 10px;
  padding: 5px 10px;
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.user-list button:hover {
  background-color: #218838;
}

.user-list button:last-child {
  background-color: #dc3545;
}

.user-list button:last-child:hover {
  background-color: #c82333;
}
</style>