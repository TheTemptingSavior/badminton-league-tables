<template>
  <div id="app">
    <div id="nav">
      <NavBar />
    </div>
    <router-view/>
  </div>
</template>

<style>

</style>
<script>
import NavBar from "@/components/NavBar";
import M from 'materialize-css'
export default {
  components: { NavBar },
  mounted() {
    M.AutoInit();
  }
}
</script>