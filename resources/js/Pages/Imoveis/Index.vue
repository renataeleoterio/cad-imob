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
            />
          </v-col>
        </v-row>

      <v-data-table
      :headers="headers"
      :items="imoveis"
      class="elevation-1"
      item-key="id"
      >
        <template #item.contribuinte="{ item }">
          {{  item.contribuinte?.nome }}
        </template>

        <template #item.area_terreno="{ item }">
          {{  item.area_terreno }} m²
        </template>

        <template #item.area_edificacao="{ item }">
          {{ item.area_edificacao ?? '-' }} m²
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
          @click="router.get(route('imoveis.edit', item))"
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
        </template>
      </v-data-table>

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
import { ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';


const page = usePage();

const imoveis = ref(page.props.imoveis?.data ?? [])

const filters = ref({
  inscricao_municipal: page.props.filters?.inscricao_municipal ?? '',
  tipo: page.props.filters?.tipo ?? '',
  logradouro: page.props.filters?.logradouro ?? '',
  numero: page.props.filters?.numero ?? '',
  bairro: page.props.filters?.bairro ?? '',
  contribuinte_id: page.props.filters?.contribuinte_id ?? '',
  situacao: page.props.filters?.situacao ?? '',
})

const currentPage = ref(page.props.imoveis.current_page ?? 1)

const headers = ref([
  { title: 'Inscrição', key: 'id'},
  { title: 'Tipo', key: 'tipo'},
  { title: 'Area do Terreno (m²)', key: 'area_terreno'},
  { title: 'Area de Edificação', key: 'area_edificacao'},
  { title: 'Logradouro', key: 'logradouro'},
  { title: 'Numero', key: 'numero'},
  { title: 'Bairro', key: 'bairro'},
  { title: 'Contribuinte', key: 'contribuinte'},
  { title: 'Situação', key: 'situacao'},
  { title: 'Ações', key: 'acoes', sortable: false},
])


watch(filters, (newFilters) => {
  router.get(route('imoveis.index'),
   { ...newFilters, page: currentPage.value }, 
   { preserveState: true, replace: true })
}, { deep: true })


const changePage= (newPage) =>{
  currentPage.value = newPage
  router.get(
    route('imoveis.index'),
    { ...filters.value, page: newPage},
    { preserveState: true, replace: true }
  )
}


// inativar imóvel
const inativarImovel = (id) => {
  if (!confirm('Tem certeza que deseja inativar este imóvel?')) {
    router.delete(route('imoveis.destroy', id), {
      onSuccess: () => {
        alert('Imovel inativado com sucesso')
      }
    })
  }
}
  
</script>