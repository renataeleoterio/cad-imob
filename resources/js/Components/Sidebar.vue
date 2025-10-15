<template>
  <v-navigation-drawer app
  :model-value="modelValue"
  @update:modelValue="(value) => emit('update:modelValue', val)"
  permanent
  class="sidebar-menu"
  >
    <!-- header -->
     <div>
      <v-toolbar flat color="white" class="pa-5">
        <v-icon
        size= "3.5rem"
        color="grey-darken-1"
        >
        mdi-home-city-outline
      </v-icon>
        <v-toolbar-title class="text-grey-darken-1"
        >
        <p class="font-weight-bold"
        >
        CadImob
        </p>
      </v-toolbar-title>
      </v-toolbar>
    </div>

    <!-- menu items -->
    <v-list density="compact" class="">
      <Link
      as="div"
      v-for="item in menuItems"
      :key="item.title" 
      :href="item.route" 
      class="flex items-center gap-4 py-4 text-sm font-medium hover:bg-gray-100"
        :class="{ 'bg-blue-100 text-blue-800': $page.url === item.route }">
      
      
        <v-icon class="ml-8 mr-4" 
        color="grey-darken-3"
        :icon="item.icon" 
        :size="item.iconSize"
        ></v-icon>
      

      <v-list-item-title>{{ item.title }}</v-list-item-title>
      </Link>
    </v-list>

    
    </v-navigation-drawer>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import { Link } from '@inertiajs/vue3';

const menuItems = [
  { title: 'Home', route: route('Home'), icon: 'mdi-home-outline', iconSize: '1.53rem', value: 'home' },
  { title: 'Pessoas', route: route('pessoas.index'), icon: 'mdi-folder-account-outline', iconSize: '1.54rem', value: 'pessoas' },
  { title: 'Imóveis', route: route('imoveis.index'), icon: 'mdi-home-city-outline', iconSize: '1.4rem' , value: 'imoveis' },
  { title: 'Usuários', route: route('users.index'), icon: 'mdi-card-account-details-outline', iconSize: '1.38rem', value: 'users' },
  { title: 'Configurações', route: route('configuracoes.index'), icon: 'mdi-cog-outline', iconSize: '1.52rem', value: 'configuracoes' },
  { title: 'Auditoria', route: route('auditoria.index'), icon: 'mdi-shield-account-outline', iconSize: '1.52rem', value: 'auditoria' },

];

const props = defineProps({
  modelValue: Boolean
});


const emit = defineEmits(['update:modelValue']);

</script>

<style scoped>



</style>