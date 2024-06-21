import Vuex from 'vuex';
import axios from 'axios';

export const store = new Vuex.Store({
  state: {
    userLists: [],
    selectedUser: null,
  },
  mutations: {
    setUsers(state, users) {
      state.userLists = users;
    },
    addUser(state, user) {
      state.userLists.push(user);
      
    },
    chooseUser(state, user) {
      state.selectedUser = user;
    },
    updateUser(state, updatedUser) {
      const index = state.userLists.findIndex(user => user.id === updatedUser.id);
      if (index !== -1) {
        state.userLists.splice(index, 1, updatedUser);
      }
      state.selectedUser = null;
    },
    removeUser(state, userId) {
      state.userLists = state.userLists.filter(user => user.id !== userId);
    },
  },
  actions: {
    async fetchUsers({ commit }) {
      try {
        const response = await axios.get('http://localhost:8088/users');
        commit('setUsers', response.data);
      } catch (error) {
        console.error("Error fetching users: ", error);
      }
    },
    async addUser({ commit }, user) {
      try {
        const response = await axios.post('http://localhost:8088/users', user);
        if (response.data && response.data.id) {
          commit('addUser', { id: response.data.id, ...user });
          return { message: response.data.message };
        } else {
          return { message: response.data.message };
        }
      } catch (error) {
        console.error("Error adding user: ", error);
        throw new Error(error.response.data.message);
      }
    },
    async updateUser({ commit }, updatedUser) {
      try {
        const response = await axios.put(`http://localhost:8088/users/${updatedUser.id}`, updatedUser);
          commit('updateUser', updatedUser);
          return { message: response.data.message};
        } catch (error) {
          if (error.response && error.response.status === 400) {
            return { message: "Invalid email format." };
          } else {
            return { message: "Error updating user: " + (error.response ? error.response.data.message : error.message) };
          }
        }
    },
    async removeUser({ commit }, userId) {
      try {
        await axios.delete(`http://localhost:8088/users/${userId}`);
        commit('removeUser', userId);
      } catch (error) {
        console.error("Error deleting user: ", error);
      }
    },
  },
});

export default store;
