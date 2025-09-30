<template>
  <AppLayout>
    <v-container>
      <PessoaForm :pessoa="null" />
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PessoaForm from '@/Components/PessoaForm.vue';
</script>