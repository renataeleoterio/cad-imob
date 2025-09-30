
<template>
  <AppLayout>

  <v-container class="pa-md-12">
    <section>
      <h3 class="d-flex justify-space-between align-center text-subtitle-1 font-weight-bold">
        Pessoas

        <v-btn class="text-none"  color="primary" prepend-icon="mdi-plus" rounded="lg" slim 
          text="Cadastrar"
          variant="flat" 
          @click="goToCreate"
          />
      </h3>

      <v-text-field
        v-model="pesquisa"
        prepend-inner-icon="mdi-magnify"
        density="compact"
        label="Pesquisar"
        single-line
        flat
        hide-details
        variant="solo-filled"
        class="w-full sm:w-3/4 xs:w-1/2"
        ></v-text-field>

      <!-- tabela de paginação de pessoas -->
        <v-data-table
        class="bg-transparent"
        :headers="headers"
        :items="pessoas"
        :items-length="totalPessoas"
        :loading="loading"
        :options="options"
        @update:options="updateOptions"
        v-model:search="pesquisa"
      >
      <!-- coluna de ações -->
        <template #item.actions="{ item }">
          <v-btn
            class="text-none"
            color="primary"
            min-width="0"
            slim
            text="VISUALIZAR"
            variant="text"
            @click="viewPerson(item)"
          />
          <v-btn
            class="text-none"
            color="error"
            min-width="0"
            slim
            text="EXCLUIR"
            variant="text"
            @click="deletePerson(item)"
            />
          </template>
        </v-data-table>
      </section>
    </v-container>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const headers = ref([
  { text: 'ID', value: 'id', sortable: true },
  { text: 'Nome', value: 'nome', sortable: true },
  { text: 'CPF', value: 'cpf', sortable: false },
  { text: 'Data de Nascimento', value: 'data_nascimento', sortable: true },
  { text: 'Sexo', value: 'sexo', sortable: true },
  { text: 'Ações', value: 'actions', sortable: false },
]);

const props = defineProps({
  pessoas: Array,
  totalPessoas: Number,
  options: Object,
});

const pesquisa = ref('');

// a tabela se atualiza quando os dados mudam
const pessoas = ref(props.pessoas);


// controla o carregamento da tabela
const loading = ref(false);

// controla a navegação para a rota de cadastro de pessoas
const goToCreate = () => {
  router.get(route('pessoas.create'));
};


// função para as opções de paginação e ordenação
const updateOptions = (options) => {
  loading.value = true;
  router.get(route('pessoas.index'), {
    page: options.page,
    itemsPerPage: options.itemsPerPage,
  }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      loading.value = false;
    },
  });
}

// visualizar os dados de uma pessoa
const viewPerson = (pessoa) => {
  router.get(route('pessoas.edit', pessoa.id));
};

// função para excluir uma pessoa
const deletePerson = (pessoa) => {
  if (confirm(`Tem certeza que deseja deletar ${pessoa.nome}?`)) {
    router.delete(route('pessoas.destroy', pessoa.id), {
      onSuccess: () => {
        router.get(route('pessoas.index'));
      },
      onError: (errors) => {
        console.log(errors);
      },
    });
  }
};
</script>
