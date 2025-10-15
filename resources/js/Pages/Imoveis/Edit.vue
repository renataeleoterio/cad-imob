<template>
  <AppLayout>
    <v-container>
      <v-card class="pa-4">
        <v-card-title>Editar Imóvel</v-card-title>

         <v-alert
          v-if="$page.props.flash?.success"
          type="success"
          class="mb-4"
        >
          {{ $page.props.flash.success }}
        </v-alert>

        <v-alert
          v-if="$page.props.flash?.error"
          type="error"
          class="mb-4"
        >
          {{ $page.props.flash.error }}
        </v-alert>

        <v-form @submit.prevent="submit">
          <v-row>
            <!-- tipo -->
            <v-col cols="12" md="4">
              <v-select 
                v-model="form.tipo"
                :items="tiposImovel"
                item-title="text"
                item-value="value"
                label="Tipo do Imóvel"
                :error-messages="form.errors.tipo"
                @update:modelValue="onTipoChange"
                required
              />
            </v-col>

            <!-- area do terreno -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.area_terreno"
                label="Área do Terreno (m²)"
                inputmode="decimal"
                :readonly="tipoConfig.areaTerrenoReadonly"
                :error-messages="form.errors.area_terreno"
                :required="tipoConfig.areaTerrenoObrigatorio"
                :hint="tipoConfig.areaTerrenoHint"
                persistent-hint
              />
            </v-col>

            <!-- area da edificação -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.area_edificacao"
                label="Área da Edificação (m²)"
                inputmode="decimal"
                :readonly="tipoConfig.areaEdificacaoReadonly"
                :error-messages="form.errors.area_edificacao"
                :hint="tipoConfig.areaEdificacaoHint"
                persistent-hint
                :required="tipoConfig.areaEdificacaoObrigatorio"
              />
            </v-col>

            <!-- logradouro -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.logradouro"
                label="Logradouro"
                :error-messages="form.errors.logradouro"
                required
              />
            </v-col>

            <!-- numero -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.numero"
                type="number"
                label="Número"
                :error-messages="form.errors.numero"
                required
              />
            </v-col>

            <!-- bairro -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.bairro"
                label="Bairro"
                :error-messages="form.errors.bairro"
                required
              />
            </v-col>

            <!-- complemento -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.complemento"
                label="Complemento"
                :error-messages="form.errors.complemento"
              />
            </v-col>

            <!-- contribuinte -->
            <v-col cols="12" md="4">
              <v-select
                v-model="form.contribuinte_id"
                :items="pessoas"
                item-value="id"
                item-title="nome"
                label="Contribuinte"
                :error-messages="form.errors.contribuinte_id"
                required
              />
            </v-col>

            <!-- situacao -->
            <v-col cols="12" md="4">
              <v-select
                v-model="form.situacao"
                :items="situacoes"
                label="Situação"
                :error-messages="form.errors.situacao"
                disabled
              />
            </v-col>

          
            <!-- botoes -->
            <v-col cols="12" md="4">
              <v-btn type="submit" :loading="form.processing" class="mr-2">
                Salvar
              </v-btn>

              <v-btn text @click="router.get(route('imoveis.index'))">
                Cancelar
              </v-btn>
            </v-col>
          </v-row>
        </v-form>

        <!-- upload de documentos -->
             <v-card-title>Documenos anexados</v-card-title>
            <v-card-text>

              <v-file-input 
              v-model="novosArquivos"
              label="Adicionar documentos"
              accept=".jpg,.jpeg,.png,.pdf"
              multiple 
              counter 
              show-size 
              :rules="[arquivosRules]"
              />

              <v-btn 
              class="mt-2"
              color="primary"
              :disabled="!novosArquivos.length"
              @click="uploadDocumentos">
              Enviar
              </v-btn>

              <!-- lista de documentos -->
              <v-list>
                <v-list-item 
                v-for="doc in documentos"
                :key="doc.id"
              >
              
              <template #title>
                {{  doc.name }}
              </template>

              
                <v-btn icon :href="route('imoveis.decoumentos.download', [props.imovel.id], doc.id)">
                  <v-icon>mdi-download</v-icon>
                </v-btn>
                <v-btn icon @click="excluirDocumento(doc.id)">
                  <v-icon color="red">mdi-delete</v-icon>
                </v-btn>
             

              </v-list-item>
              </v-list>
            </v-card-text>
      </v-card>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { computed, ref, watch } from 'vue';

const props = defineProps({
  imovel: Object,
  pessoas: Array,
  situacoes: Array,
  documentos: Array,
})
 
const novosArquivos = ref([])
const documentos = props.documentos || []

const form = useForm('put', route('imoveis.update', props.imovel.id), {
  tipo: props.imovel.tipo || '',
  area_terreno: props.imovel.area_terreno || '',
  area_edificacao: props.imovel.area_edificacao || '',
  logradouro: props.imovel.logradouro || '',
  numero: props.imovel.numero || '',
  bairro: props.imovel.bairro || '',
  complemento: props.imovel.complemento || '',
  contribuinte_id: props.imovel.contribuinte_id || '',
  situacao: props.imovel.situacao || 'Ativo',
});

const tiposImovel = [
  { text: 'Terreno', value: 'Terreno' },
  { text: 'Casa', value: 'Casa' },
  { text: 'Apartamento', value: 'Apartamento' },
];

const tipoConfig = computed(() => {
  const config = {
    areaTerrenoObrigatorio: false,
    areaTerrenoReadonly: false,
    areaTerrenoHint: '',
    areaEdificacaoObrigatorio: false,
    areaEdificacaoReadonly: false,
    areaEdificacaoHint: '',
  };

  switch (form.tipo) {
    case 'Terreno':
      config.areaTerrenoObrigatorio = true;
      config.areaTerrenoHint = 'Obrigatório para terreno';
      config.areaEdificacaoReadonly = true;
      config.areaEdificacaoHint = 'Deve ser zero para terreno';
      break;
    
    case 'Casa':
      config.areaTerrenoObrigatorio = true;
      config.areaTerrenoHint = 'Obrigatório para casa';
      config.areaEdificacaoObrigatorio = true;
      config.areaEdificacaoHint = 'Obrigatório para casa';
      break;
    
    case 'Apartamento':
      config.areaTerrenoReadonly = true;
      config.areaTerrenoHint = 'Deve ser zero para apartamento';
      config.areaEdificacaoObrigatorio = true;
      config.areaEdificacaoHint = 'Obrigatório para apartamento';
      break;
  }

  return config;
});

// ajusta os valores conforme muda o tipo
const onTipoChange = (novoTipo) => {
  
  form.validate('tipo');

  // ajusta valores conforme o tipo
  switch (novoTipo) {
    case 'Terreno':
      form.area_edificacao = 0;
      break;
    
    case 'Apartamento':
      form.area_terreno = 0;
      break;
  }

  // valida os campos de área após ajuste
  setTimeout(() => {
    form.validate('area_terreno');
    form.validate('area_edificacao');
  }, 100);
};

//validacao
function arquivosRules(files) {
  if(!files) return true
  if(files.length + documentos.length > 5) {
    return 'Máximo de 5 arquivos por imóvel'
  }
  for (let f of files) {
    if (f.size > 3 * 1024 * 1024) return 'Cada arquivo deve ter no máximo 3MB'
  }
  return true
}

//upload
function uploadDocumentos() {
  const data = new FormData()
  novosArquivos.value.forEach(f => data.append('documentos[]', f))

  router.post(route('imoveis.documentos.store', props.imovel.id), data, {
    onSuccess: () => { novosArquivos.value = [] }
  })
}

//excluir
function excluirDocumento(docId) {
  router.delete(route('imoveis.documentos.destroy', [props.imovel.id, docId ]))
}

// validacao em tempo real
watch(() => form.area_terreno, (newValue) => {
  if (newValue && newValue.length > 0) {
    setTimeout(() => form.validate('area_terreno'), 500);
  }
});

watch(() => form.area_edificacao, (newValue) => {
  if (newValue && newValue.length > 0) {
    setTimeout(() => form.validate('area_edificacao'), 500);
  }
});

const submit = () => {
  if (!confirm('Tem certeza que deseja salvar as alterações deste imóvel?')) return;

  form.submit({
    preserveScroll: true,
    onSuccess: () => {
      console.log('✅ Imóvel atualizado com sucesso!');
    },
    onError: (errors) => {
      console.error('❌ Erros de validação:', errors);
    },
  });
};
</script>
