<template>
  <AppLayout>
    <v-container>
      <h3 class="d-flex justify-space-between align-center text-subtitle-1 font-weight-bold mb-4">
        Registros de Auditoria
      </h3>

        <v-card class="mb-4">
        <v-card-text>
          <v-row>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="filters.usuario"
                label="Usuário"
                clearable
                @keyup.enter="aplicarFiltros"
              />
            </v-col>
            
            <v-col cols="12" md="2">
              <v-select
                v-model="filters.evento"
                :items="eventosOptions"
                label="Evento"
                clearable
              />
            </v-col>
            
            <v-col cols="12" md="2">
              <v-select
                v-model="filters.tabela"
                :items="tabelasOptions"
                label="Tabela"
                clearable
                item-title="text"
                item-value="value"
              />
            </v-col>
            
            <v-col cols="12" md="2">
              <v-text-field
                v-model="filters.data_inicio"
                label="Data Início"
                type="date"
                clearable
              />
            </v-col>
            
            <v-col cols="12" md="2">
              <v-text-field
                v-model="filters.data_fim"
                label="Data Fim"
                type="date"
                clearable
              />
            </v-col>
            
            <v-col cols="12" md="1" class="d-flex align-end">
              <v-btn
                color="secondary"
                @click="limparFiltros"
                :disabled="!temFiltrosAtivos"
                block
              >
                Limpar
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

      <v-card>
        <v-card-text>
          <v-data-table-server
            :headers="headers"
            :items="audits.data"
            :items-length="audits.total"
            :loading="loading"
            @update:options="updateOptions"
          >
            
            <template #item.id="{ item }">
              #{{ item.id }}
            </template>

           
            <template #item.user="{ item }">
              {{ item.user?.name ?? 'Sistema' }}
            </template>

            <template #item.event="{ item }">
              <v-chip :color="getEventColor(item.event)" small>
                {{ getEventText(item.event) }}
              </v-chip>
            </template>


            <template #item.created_at="{ item }">
              {{ formatDateTime(item.created_at) }}
            </template>

  
            <template #item.auditable_type="{ item }">
              {{ getTableName(item.auditable_type) }}
            </template>

    
            <template #item.auditable_id="{ item }">
              #{{ item.auditable_id }}
            </template>


            <template #item.actions="{ item }">
              <v-btn
                icon
                size="small"
                @click="verDetalhes(item.id)"
                color="primary"
                title="Ver Detalhes"
              >
                <v-icon>mdi-eye</v-icon>
              </v-btn>
            </template>

            <template #no-data>
              <div class="text-center py-4">
                <v-icon size="64" class="mb-2">mdi-shield-search</v-icon>
                <div>Nenhum registro de auditoria encontrado</div>
              </div>
            </template>
          </v-data-table-server>
        </v-card-text>
      </v-card>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ref } from 'vue';

const props = defineProps({
  audits: {
    type: Object,
    required: true
  },
  eventosOptions: {
    type: Array,
    default: () => []
  },
  tabelas: { 
    type: Array,
    default: () => []
  }
});

const loading = ref(false);

const headers = ref([
  { title: 'ID', key: 'id', sortable: true },
  { title: 'Usuário', key: 'user', sortable: false },
  { title: 'Evento', key: 'event', sortable: true },
  { title: 'Data e Horário', key: 'created_at', sortable: true },
  { title: 'Tabela', key: 'auditable_type', sortable: true },
  { title: 'ID Auditado', key: 'auditable_id', sortable: true },
  { title: 'Ações', key: 'actions', sortable: false, width: '100px' },
]);

const filters = ref({
  usuario: props.filters?.usuario || '',
  evento: props.filters?.evento || '',
  tabela: props.filters?.tabela || '',
  data_inicio: props.filters?.data_inicio || '',
  data_fim: props.filters?.data_fim || '',
});

const tabelasOptions = computed(() => {
  return props.tabelas.map(tabela => ({
    text: getTableName(tabela),
    value: tabela
  }));
});

const temFiltrosAtivos = computed(() => {
  return Object.values(filters.value).some(value => 
    value !== '' && value !== null && value !== undefined
  );
});

const aplicarFiltros = () => {
  loading.value = true;
  
  router.get(route('auditoria.index'), 
    { ...filters.value }, 
    { 
      preserveState: true,
      replace: true,
      onFinish: () => {
        loading.value = false;
      }
    }
  );
}

const limparFiltros = () => {
  filters.value = {
    usuario: '',
    evento: '',
    tabela: '',
    data_inicio: '',
    data_fim: '',
  };
}

const getEventColor = (event) => {
  const colors = {
    created: 'green',
    updated: 'blue',
    deleted: 'red',
  };
  return colors[event] || 'gray';
};

const getEventText = (event) => {
  const texts = {
    created: 'Criação',
    updated: 'Alteração',
    deleted: 'Exclusão',
  };
  return texts[event] || event;
};

const getTableName = (auditableType) => {
  const models = {
    'App\\Models\\Imovel': 'Imóveis',
    'App\\Models\\Contribuinte': 'Contribuintes',
    'App\\Models\\Averbacao': 'Averbações',
    'App\\Models\\User': 'Usuários',
  };
  return models[auditableType] || auditableType;
};

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString('pt-BR');
};

const verDetalhes = (id) => {
  router.get(route('auditoria.show', id));
};

const updateOptions = (options) => {
  loading.value = true;
  const { page, itemsPerPage, sortBy } = options;
  
  router.get(route('auditoria.index'), {
    page: page,
    per_page: itemsPerPage,
    sort_by: sortBy.length > 0 ? sortBy[0].key : 'created_at',
    sort_order: sortBy.length > 0 ? (sortBy[0].order === 'desc' ? 'desc' : 'asc') : 'desc',
  }, {
    preserveState: true,
    onFinish: () => {
      loading.value = false;
    }
  });
};
</script>