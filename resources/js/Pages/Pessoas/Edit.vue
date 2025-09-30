<template>
  <AppLayout>
    <v-container>
      <PessoaForm :pessoa="pessoa" />
    </v-container>
  </AppLayout>
  </template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PessoaForm from '@/Components/PessoaForm.vue';
import { defineProps } from 'vue';

const props = defineProps({
  pessoa: {
    type: Object,
    required: true,
  },
})
</script>