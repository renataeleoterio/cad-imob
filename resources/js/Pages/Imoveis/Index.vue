<template>
  <AppLayout>
    <v-container>
      <h3 class="d-flex justify-space-between align-center text-subtitle-1 font-weight-bold">
        Imóveis

        <v-btn class="text-none"  color="primary" prepend-icon="mdi-plus" rounded="lg" slim 
          text="Novo Imóvel"
          variant="flat" 
          @click="router.get(route('imoveis.create'))"
          />
      </h3>

        <v-alert
        v-if="$page.props.flash?.success"
        type="success"
        class="mb-4"
        dismissible
      >
        {{ $page.props.flash.success }}
      </v-alert>

      
      <v-alert
        v-if="$page.props.flash?.error"
        type="error"
        class="mb-4"
        dismissible
      >
        {{ $page.props.flash.error }}
      </v-alert>

      <div class="d-flex gap-2 mb-4">
        <v-btn color="primary" @click="gerarRelatorioGeral">
          <v-icon left>mdi-file-pdf</v-icon>
          Relatório Geral (PDF)
        </v-btn>      
      </div>

     

      <v-card-text>
        <v-row class="mb-4">
          <v-col cols="12" md="4">
            <v-select
            v-model="filters.tipo"
            :items="['Terreno','Casa','Apartamento']"
            label="Tipo"
            clearable
            />
          </v-col>

           <v-col cols="12" md="4">
            <v-select
            v-model="filters.situacao"
            :items="['Ativo', 'Inativo']"
            label="Situação"
            clearable
            />
          </v-col>

           <v-col cols="12" md="4">
            <v-text-field
            v-model="filters.search"
            label="Buscar por logradouro/bairro"
            clearable
            @keyup.enter="aplicarFiltros"
            />
          </v-col>

          <v-col cols="12" md="6" class="d-flex align-end gap-2">
              <v-btn
                color="secondary"
                @click="limparFiltros"
                :disabled="!temFiltrosAtivos"
              >
                Limpar Filtros
              </v-btn>
              <v-btn
                color="primary"
                @click="aplicarFiltros"
                
              >
                Aplicar Filtros
              </v-btn>
            </v-col>
        </v-row>

      <v-data-table-server
      :headers="headers"
      :items="imoveis"
      :items-length="page.props.imoveis.total"
      class="elevation-1"
      item-key="id"
      @update:options="updateOptions"
      >
        <template #item.contribuinte="{ item }">
          {{  item.contribuinte?.nome || '-'}}
        </template>

        <template #item.area_terreno="{ item }">
          {{  item.area_terreno ? `${item.area_terreno} m²` : '-' }} m²
        </template>

        <template #item.area_edificacao="{ item }">
          {{ item.area_edificacao ? `${item.area_edificacao} m²` : '-' }} m²
        </template>

        <template #item.situacao="{ item }">
            <v-chip :color="item.situacao === 'Ativo' ? 'green' : 'red'" dark>
              {{ item.situacao }}
            </v-chip>
        </template>

        <template #item.acoes="{ item }">
          <v-btn
          icon
          size="small"
          @click="editarImovel(item)"
          >
            <v-icon>mdi-pencil</v-icon>
          </v-btn>

          <v-btn
          icon
          size="small"
          @click="inativarImovel(item)"
          >
          <v-icon>mdi-delete</v-icon>
        </v-btn>

        <v-btn icon 
        size="small"
        title="Gerar PDF Individual"
        @click="gerarRelatorioIndividual(item.id)">
         <v-icon>mdi-file-pdf-box</v-icon>
        </v-btn>
        </template>

        <template #no-data>
            <div class="text-center py-4">
              <v-icon size="64" class="mb-2">mdi-home-search</v-icon>
              <div>Nenhum imóvel encontrado</div>
              <v-btn 
                color="primary" 
                class="mt-2"
                @click="limparFiltros"
              >
                Limpar Filtros
              </v-btn>
            </div>
          </template>
      </v-data-table-server>

      <div class="mt-4 flex justify-center">
        <v-pagination
          v-model="currentPage"
          :length="page.props.imoveis.last_page"
          @update:model-value="changePage"
        />
      </div>

      </v-card-text>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';


const page = usePage();
const imovel = ref(page.props.imovel);

const imoveis = ref(page.props.imoveis?.data ?? [])

const filters = ref({
  tipo: page.props.filters?.tipo || '',
  logradouro: page.props.filters?.logradouro || '',
  numero: page.props.filters?.numero || '',
  bairro: page.props.filters?.bairro || '',
  contribuinte_id: page.props.filters?.contribuinte_id || '',
  situacao: page.props.filters?.situacao || '',
  search: page.props.filters?.search || '',
});


const currentPage = ref(page.props.imoveis.current_page ?? 1)

const headers = ref([
  { title: 'Inscrição', key: 'id'},
  { title: 'Tipo', key: 'tipo'},
  { title: 'Logradouro', key: 'logradouro'},
  { title: 'Numero', key: 'numero'},
  { title: 'Bairro', key: 'bairro'},
  { title: 'Contribuinte', key: 'contribuinte'},
  { title: 'Situação', key: 'situacao'},
  { title: 'Ações', key: 'acoes', sortable: false},
])

const gerarRelatorioGeral = () => {
  window.open('/pdf/imoveis/geral', '_blank')
}

const gerarRelatorioIndividual = (id) => {
  window.open(`/pdf/imoveis/${id}/individual`, '_blank')
}

//ordenar e paginar
const updateOptions = (options) => {
  currentPage.value = options.page
}

const loading = ref(false);

const temFiltrosAtivos = computed(() => {
  return Object.values(filters.value).some(value => 
    value !== '' && value !== null && value !== undefined
  );
});

watch(filters, (newFilters) => {
  setTimeout(() => {
    aplicarFiltros();
  }, 500);
}, { deep: true });

const aplicarFiltros = () => {
  loading.value = true;
  currentPage.value = 1;
  
  router.get(route('imoveis.index'), 
    { ...filters.value, page: currentPage.value }, 
    { 
      preserveState: false, 
      replace: true,
      onSuccess: () => {
        console.log('Filtros aplicados com sucesso');
        console.log('Novos dados:', page.props.imoveis);
      },
      onFinish: () => {
        loading.value = false;
      }
    }
  );
};

const limparFiltros = () => {
  filters.value = {
    tipo: '',
    logradouro: '',
    numero: '',
    bairro: '',
    contribuinte_id: '',
    situacao: '',
  };
  currentPage.value = 1;
  
  router.get(route('imoveis.index'), 
    { page: currentPage.value }, 
    { preserveState: true, replace: true }
  );
};


const changePage= (newPage) =>{
  currentPage.value = newPage
  router.get(
    route('imoveis.index'),
    { ...filters.value, page: newPage},
    { preserveState: false, replace: true }
  )
}

const editarImovel = (imovel) => {
  router.get(route('imoveis.edit', imovel.id))
}

// inativar imóvel
const inativarImovel = (imovel) => {
  if (!confirm('Tem certeza que deseja inativar este imóvel?')) {
    return;
  }
    
  router.patch(route('imoveis.inativar', imovel.id), {
      onSuccess: () => {
        alert('Imovel inativado com sucesso')
        router.reload({ preserveScroll: true });
      },
      onError: () => {
        alert('Erro ao inativar imovel.')
      }
    })
  }

  
</script>