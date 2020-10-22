<template>
  <div id="admin-home">
    <AdminNavBar />
    <div class="container">
      <h1>Logged in successfully</h1>
      <hr />
      <p>
        Maybe some stats about the site??
      </p>
      <ul>
        <li>Number of teams</li>
        <li>Number of seasons</li>
        <li>Number of games played (total + this season)</li>
      </ul>
    </div>
  </div>
</template>

<script>
import AdminNavBar from "@/components/admin/AdminNavBar";
export default {
name: "AdminHome",
  components: {AdminNavBar}
}
</script>

<style scoped>

</style>