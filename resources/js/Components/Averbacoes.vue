<template>
  <v-card variant="outlined">
    <v-card-title class="d-flex align-center">
      <v-icon icon="mdi-history" class="mr-2" />
      Histórico de Averbações
      <v-chip class="ml-2" color="primary" small>
        {{ totalAverbacoes }}
      </v-chip>
    </v-card-title>

    <v-card-text>
      <v-form @submit.prevent="submit" ref="formRef">
        <v-row>
          <v-col cols="12" md="4">
            <v-select
              v-model="form.evento"
              :items="eventos"
              item-title="text"
              item-value="value"
              label="Evento"
              :error-messages="errors.evento"
              @update:modelValue="onEventoChange"
              :disabled="loading"
              required
            />
          </v-col>

          <v-col cols="12" md="3" v-if="mostrarCampoMedida">
            <v-text-field
              v-model="form.medida"
              label="Medida (m²)"
              type="number"
              step="0.01"
              min="0.01"
              :error-messages="errors.medida"
              :required="campoMedidaObrigatorio"
              :disabled="loading"
            />
          </v-col>

          <v-col cols="12" md="5">
            <v-text-field
              v-model="form.descricao"
              label="Descrição"
              :error-messages="errors.descricao"
              :disabled="loading"
              required
            />
          </v-col>

          <v-col cols="12">
            <v-btn 
              type="submit" 
              color="primary"
              :loading="loading"
              :disabled="!podeAdicionar"
              prepend-icon="mdi-plus"
            >
              Adicionar Averbação
            </v-btn>
          </v-col>
        </v-row>
      </v-form>

    <v-card variant="tonal" class="mb-4">
    <v-card-text class="pa-4">
    <v-row class="text-center">
        <v-col>
        <div class="text-h6 text-primary">
            {{ totalAverbacoes }}
        </div>
        <div class="text-caption">Total de Averbações</div>
        </v-col>
        <v-col>
        <div class="text-h6 text-success">
            {{ totalAumentoArea }} m²
        </div>
        <div class="text-caption">
            Área Aumentada
        </div>
        <div class="text-h6 text-warning">
            {{ totalReducaoArea }} m²
        </div>
        <div class="text-caption">Área Reduzida</div>
        </v-col>
        <v-col>
        <v-chip :color="situacaoImovel === 'Ativo' ? 'success' : 'error'" variant="flat">
          {{ situacaoImovel }}
        </v-chip>
        <div class="text-caption mt-1">Situação</div>
      </v-col>
    </v-row>
  </v-card-text>
</v-card>

      <!-- lista de averbações -->
      <v-list v-if="averbacoes.length" class="mt-4">
        <v-list-item 
          v-for="averbacao in averbacoes"
          :key="averbacao.id"
          class="mb-2"
          border
        >
          <template #prepend>
            <v-icon :color="getEventoColor(averbacao.evento)" size="large">
              {{ getEventoIcon(averbacao.evento) }}
            </v-icon>
          </template>

          <v-list-item-title class="font-weight-medium">
            {{ getEventoLabel(averbacao.evento) }}
            <v-chip 
              v-if="averbacao.medida" 
              size="small" 
              class="ml-2"
              :color="getEventoChipColor(averbacao.evento)"
            >
              {{ formatMedida(averbacao) }}
            </v-chip>
          </v-list-item-title>
          
          <v-list-item-subtitle>
            {{ averbacao.descricao }}
          </v-list-item-subtitle>

          <v-list-item-subtitle class="text-caption">
            Registrado em: {{ formatDate(averbacao.data) }}
          </v-list-item-subtitle>

          <template #append>
            <v-btn 
              icon 
              variant="text"
              @click="emit('delete', averbacao)"
              :loading="deleteLoading === averbacao.id"
              size="small"
            >
              <v-icon color="error">mdi-delete</v-icon>
            </v-btn>
          </template>
        </v-list-item>
      </v-list>

      <!-- estado vazio -->
      <v-alert
        v-else
        type="info"
        variant="tonal"
        class="mt-4"
      >
        <div class="text-center">
          <v-icon icon="mdi-history" size="large" class="mb-2" />
          <div>Nenhuma averbação registrada</div>
          <div class="text-caption">Registre eventos como alterações de área, cancelamentos ou observações</div>
        </div>
      </v-alert>
    </v-card-text>
  </v-card>

</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  imovelId: {
    type: [String, Number],
    required: true
  },
  averbacoes: {
    type: Array,
    default: () => []
  },
  situacaoImovel: {
    type: String,
    default: 'Ativo'
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const totalAverbacoes = computed(() => props.averbacoes.length);

const totalAumentoArea = computed(() => {
  const total = props.averbacoes
    .filter(a => a.evento === 'aumento_area_construida')
    .reduce((sum, a) => sum + parseFloat(a.medida || 0), 0);
  return total.toFixed(2);
});

const totalReducaoArea = computed(() => {
  const total = props.averbacoes
    .filter(a => a.evento === 'reducao_area_construida')
    .reduce((sum, a) => sum + parseFloat(a.medida || 0), 0);
  return total.toFixed(2);
});

const form = ref({
  evento: '',
  medida: null,
  descricao: ''
});
const errors = ref({});
const loading = ref(false);
const deleteLoading = ref(null);
const formRef = ref(null);


const eventos = ref([
  { text: 'Aumento Área Construída', value: 'aumento_area_construida' },
  { text: 'Redução Área Construída', value: 'reducao_area_construida' },
  { text: 'Observação', value: 'observacao' },
  { text: 'Cancelamento', value: 'cancelamento' },
  { text: 'Reativação', value: 'reativacao' },
]);


const mostrarCampoMedida = computed(() => {
  return ['aumento_area_construida', 'reducao_area_construida'].includes(form.value.evento);
});

const campoMedidaObrigatorio = computed(() => {
  return mostrarCampoMedida.value;
});

const podeAdicionar = computed(() => {
  if (!form.value.evento || !form.value.descricao) return false;
  
  if (form.value.evento === 'cancelamento' && props.situacaoImovel === 'Inativo') {
    return false;
  }
  
  if (form.value.evento === 'reativacao' && props.situacaoImovel === 'Ativo') {
    return false;
  }
  
  if (mostrarCampoMedida.value && (!form.value.medida || form.value.medida <= 0)) {
    return false;
  }
  
  return true;
});


function onEventoChange() {
  // limpar medida quando mudar o evento
  if (!mostrarCampoMedida.value) {
    form.value.medida = null;
  }
  errors.value = {};
}

function submit() {
  if (!podeAdicionar.value) return;
  
  loading.value = true;
  errors.value = {};
  
 
   router.post(route('imoveis.averbacoes.store', props.imovelId), form.value, {
    preserveScroll: true,
    onSuccess: () => {
      form.value = { evento: '', medida: null, descricao: '' };
      formRef.value?.reset();
      router.reload();
    },
    onError: (errorErrors) => {
      errors.value = errorErrors;
    },
    onFinish: () => {
      loading.value = false;
    }
});
}

function excluirAverbacao() {
  if (!averbacaoParaExcluir.value) return;
  
  deleteLoading.value = true;
  
  router.delete(
    route('imoveis.averbacoes.destroy', [props.imovelId, averbacaoParaExcluir.value.id]),
    {
      preserveScroll: true,
      onSuccess: () => {
        router.reload();
        deleteDialog.value = false;
        averbacaoParaExcluir.value = null;
      },
      onFinish: () => {
        deleteLoading.value = false;
      }
    }
  );
}

function confirmarExclusao(averbacao) {
  averbacaoParaExcluir.value = averbacao;
  deleteDialog.value = true;
}



// utilitários
function getEventoLabel(evento) {
  const event = eventos.value.find(e => e.value === evento);
  return event ? event.text : evento;
}

function getEventoIcon(evento) {
  switch (evento) {
    case 'aumento_area_construida': return 'mdi-arrow-up-bold';
    case 'reducao_area_construida': return 'mdi-arrow-down-bold';
    case 'observacao': return 'mdi-text-box-outline';
    case 'cancelamento': return 'mdi-cancel';
    case 'reativacao': return 'mdi-restore';
    default: return 'mdi-help-circle';
  }
}

function getEventoColor(evento) {
  switch (evento) {
    case 'aumento_area_construida': return 'success';
    case 'reducao_area_construida': return 'warning';
    case 'observacao': return 'info';
    case 'cancelamento': return 'error';
    case 'reativacao': return 'success';
    default: return 'grey';
  }
}

function getEventoChipColor(evento) {
  switch (evento) {
    case 'aumento_area_construida': return 'success';
    case 'reducao_area_construida': return 'warning';
    default: return 'primary';
  }
}

function formatMedida(averbacao) {
  if (!averbacao.medida) return '';
  
  const prefix = averbacao.evento === 'aumento_area_construida' ? '+' : '-';
  return `${prefix} ${averbacao.medida} m²`;
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('pt-BR');
}

</script>