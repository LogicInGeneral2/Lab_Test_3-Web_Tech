<template>
  <div class="col-5 border rounded m-auto">
    <h4 class="m-2">List of Users</h4>
    <hr>
    <ul v-if="getUsers.length > 0" class="list-group">
      <li v-for="user in getUsers" :key="user.id" class="list-group-item mb-2 border-primary rounded bg-light">
        <div class="row m-2">
          <div class="col align-self-center">
            <p>{{ user.name }}</p>
            <p>({{ user.email }})</p>
          </div>
          <div class="col-auto d-flex flex-column">
            <button type="button" class="btn btn-primary mb-2" @click="chooseUser(user)">Choose</button>
            <button type="button" class="btn btn-danger" @click="removeUser(user.id)">Remove</button>
          </div>
        </div>
      </li>
    </ul>
    <div v-else class="text-center mt-3">
      <p>- No user yet -</p>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    getUsers() {
      return this.$store.state.userLists;
    },
  },
  methods: {
    chooseUser(user) {
      this.$store.commit('chooseUser', user);
    },
    removeUser(userId) {
      if (confirm('Are you sure you want to remove this user?')) {
        this.$store.dispatch('removeUser', userId);
      }
    },
  },
  created() {
    this.$store.dispatch('fetchUsers');
  },
};
</script>
