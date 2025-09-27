<template>
  <v-app id="inspire">
    
    <Sidebar v-model="drawer" @toggle-sidebar="toggleSidebar" />

    <Navbar @toggle-sidebar="toggleSidebar" />
    
    <v-main>
      <v-container fluid>
        <slot></slot>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
  import { ref } from 'vue'
  import Sidebar from '@/Components/Sidebar.vue';
  import Navbar from '@/Components/Navbar.vue';

  const drawer = ref(true)
  const toggleSidebar = () => {
    drawer.value = !drawer.value;
  };

  
</script>




