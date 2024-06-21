<template>
  <div class="col-5 border rounded p-3 mb-auto justify-content-start">
    <form class="myForm" @submit="handleSubmit">
      <div class="input-group mb-2">
        <span class="input-group-text" id="basic-addon1">Name</span>
        <input type="text" class="form-control" placeholder="Ahmad Jalkhan" name="name" v-model="name" required>
      </div>

      <div class="input-group mb-2">
        <span class="input-group-text" id="basic-addon1">Email</span>
        <input type="text" class="form-control" placeholder="ajmaj578@gmail.com" name="email" v-model="email" required>
      </div>

      <button type="submit" class="btn btn-success m-2">Save</button>
      <button type="button" class="btn btn-primary m-2" @click="handleUpdate">Update</button>
    </form>

    <div v-if="statusMessage" class="mt-3">
      <div class="bg-info text-primary p-2 rounded w-50 m-auto">
        {{ statusMessage.message }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: '',
      email: '',
      selectedUserId: null,
      statusMessage: null,
    };
  },
  computed: {
    selectedUser() {
      return this.$store.state.selectedUser;
    },
  },
  watch: {
    selectedUser(newVal) {
      if (newVal) {
        this.name = newVal.name;
        this.email = newVal.email;
        this.selectedUserId = newVal.id;
      }
    },
  },
  methods: {
    async handleSubmit(e) {
      e.preventDefault();
      const response = await this.$store.dispatch('addUser', { name: this.name, email: this.email });
      this.statusMessage = {message: response.message };
      this.resetForm();
    },
    async handleUpdate() {
      if (this.selectedUserId !== null) {
        if (this.name === '' || this.email === '') {
          alert('Please fill in all fields.');
        } else {
          const response = await this.$store.dispatch('updateUser', { id: this.selectedUserId, name: this.name, email: this.email });
          this.statusMessage = {message: response.message };
          this.resetForm();
        }
      } else {
        alert('Please select a user to update.');
      }
    },
    resetForm() {
      this.name = '';
      this.email = '';
      this.selectedUserId = null;
      setTimeout(() => {
        this.statusMessage = null;
      }, 3000);
    },
  },
};
</script>
