<template>
  <AppLayout> 
  <div class="dashboard-container">
  <v-card>
    <v-card-text>
      <h1 class="text-base">Bem vindo ao painel de controle do Cadastro Imobiliário</h1>

    <v-alert type="info">
      <p>Selecione uma opção no menu lateral</p>
    </v-alert>
    </v-card-text>
  </v-card>
  </div>
  </AppLayout>
  </template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<style scoped> 
.dashboard-container {
  padding: 20px;
  background-color: #fffefe;
}

.v-card {
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

</style>