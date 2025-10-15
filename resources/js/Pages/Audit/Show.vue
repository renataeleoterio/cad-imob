<template>
  <AppLayout>
    <v-container>
      <v-row class="mb-4">
        <v-col>
          <v-btn 
            icon 
            @click="router.get(route('auditoria.index'))"
            class="mr-2"
          >
            <v-icon>mdi-arrow-left</v-icon>
          </v-btn>
          <h3 class="d-inline-block text-subtitle-1 font-weight-bold">
            Detalhes da Auditoria #{{ audit.id }}
          </h3>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12">
          <v-card>
            <v-card-title class="bg-primary text-white">
              Informações da Auditoria
            </v-card-title>
            <v-card-text class="pt-4">
              <v-row>
                <v-col cols="12" md="6">
                  <v-table>
                    <tbody>
                      <tr>
                        <td class="font-weight-bold" style="width: 30%">ID:</td>
                        <td>#{{ audit.id }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Data:</td>
                        <td>{{ formatDateTime(audit.created_at) }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Usuário:</td>
                        <td>{{ audit.user?.name ?? 'Sistema' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">Evento:</td>
                        <td>
                          <v-chip :color="getEventColor(audit.event)" small>
                            {{ getEventText(audit.event) }}
                          </v-chip>
                        </td>
                      </tr>
                    </tbody>
                  </v-table>
                </v-col>
                
                <v-col cols="12" md="6">
                  <v-table>
                    <tbody>
                      <tr>
                        <td class="font-weight-bold" style="width: 30%">Tabela:</td>
                        <td>{{ getTableName(audit.auditable_type) }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">ID Auditado:</td>
                        <td>#{{ audit.auditable_id }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">URL:</td>
                        <td class="text-truncate">{{ audit.url || '-' }}</td>
                      </tr>
                      <tr>
                        <td class="font-weight-bold">IP:</td>
                        <td>{{ audit.ip_address || '-' }}</td>
                      </tr>
                    </tbody>
                  </v-table>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <v-card>
            <v-card-title class="bg-warning text-white">
              Dados Anteriores
            </v-card-title>
            <v-card-text class="pt-4">
              <v-table v-if="audit.old_values && Object.keys(audit.old_values).length > 0">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, key) in audit.old_values" :key="key">
                    <td class="font-weight-bold" style="width: 40%">{{ formatFieldName(key) }}</td>
                    <td>{{ formatValue(value) }}</td>
                  </tr>
                </tbody>
              </v-table>
              <div v-else class="text-center text-grey py-4">
                Nenhum dado anterior registrado
              </div>
            </v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="6">
          <v-card>
            <v-card-title 
              :class="audit.event === 'deleted' ? 'bg-error text-white' : 'bg-success text-white'"
            >
              {{ audit.event === 'deleted' ? 'Dados Excluídos' : 'Dados Novos' }}
            </v-card-title>
            <v-card-text class="pt-4">
              <v-table v-if="audit.new_values && Object.keys(audit.new_values).length > 0">
                <thead>
                  <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, key) in audit.new_values" :key="key">
                    <td class="font-weight-bold" style="width: 40%">{{ formatFieldName(key) }}</td>
                    <td>{{ formatValue(value) }}</td>
                  </tr>
                </tbody>
              </v-table>
              <div v-else class="text-center text-grey py-4">
                {{ audit.event === 'deleted' ? 'Nenhum dado excluído' : 'Nenhum dado novo' }}
              </div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  audit: {
    type: Object,
    required: true
  }
})


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

const formatFieldName = (field) => {
  const fieldNames = {
    'nome': 'Nome',
    'email': 'E-mail',
    'logradouro': 'Logradouro',
    'numero': 'Número',
    'bairro': 'Bairro',
    'area_terreno': 'Área do Terreno',
    'area_edificacao': 'Área da Edificação',
    'situacao': 'Situação',
    'tipo': 'Tipo',
    'created_at': 'Data de Criação',
    'updated_at': 'Data de Atualização',
  }
  return fieldNames[field] || field;
}

const formatValue = (value) => {
  if (value === null) return 'NULL';
  if (value === '') return 'Vazio';
  if (typeof value === 'boolean') return value ? 'Sim' : 'Não';
  if (typeof value === 'object') return JSON.stringify(value);
  return value;
}
</script>