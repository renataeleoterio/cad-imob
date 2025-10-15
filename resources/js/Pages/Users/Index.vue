<template>
  <AppLayout>
    <v-container>
      <v-card>
        <v-card-title>
          Usuários
        </v-card-title>
        <v-data-table 
        :items="items" 
        :headers="headers"
        class="elevation-1"
        :items-per-page="5"
        >
          <template 
          #item.actions="{ item }">
            <Link 
            :href="route('users.edit', item.id)"
            class="v-btn v-btn--outlined v-size--small"
            >
          Visualizar
          </Link>
          </template>
        </v-data-table>
      </v-card>
    </v-container>
  </AppLayout>
</template>

<script setup>

import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue';

const props = defineProps({ 
  users: Object, 
})

const page = usePage();

const users = ref(page.props.users);
const items = props.users.data;


const headers = [
  { title: 'ID', key: 'id' },
  { title: 'Nome', key: 'nome' },
  { title: 'Email', key: 'email' },
  { title: 'Perfil', key: 'perfil' },
  { title: 'Ativo', key: 'ativo' },
  { title: 'Ações', key: 'actions', sortable: false }
]
</script>
