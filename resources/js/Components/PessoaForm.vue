<template>
  <v-sheet class="mx-auto pa-md-12 pa-4" max-width="900" rounded="lg">
    <v-card-title class="d-flex justify-space-between align-center mb-4">
      <span class="text-h5">{{ isEditing ? 'Editar Pessoa' : 'Cadastrar Pessoa' }}</span>
      <v-btn
        color="primary"
        prepend-icon="mdi-arrow-left"
        @click="goBack"
      >
        Voltar
      </v-btn>
    </v-card-title>

    
    <v-form @submit.prevent="submitForm">
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="form.nome"
            label="Nome Completo"
            placeholder="Nome Completo"
            variant="outlined"
            :error-messages="form.errors.nome"
          />
        </v-col>

        <v-col cols="12" md="6">
          <v-text-field
            v-model="form.cpf"
            v-maska="'###.###.###-##'"
            label="CPF"
            placeholder="000.000.000-00"
            variant="outlined"
            :readonly="isEditing"
            :error-messages="form.errors.cpf"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="form.data_nascimento"
            label="Data de Nascimento"
            type="date"
            variant="outlined"
            :error-messages="form.errors.data_nascimento"
            :max="dataMaxima"
            hint="A pessoa deve ter pelo menos 18 anos"
            persistent-hint
          />
        </v-col>

        <v-col cols="12" md="6">
          <v-radio-group v-model="form.sexo" :error-messages="form.errors.sexo">
            <template #label><div>Sexo</div></template>
            <v-radio label="Masculino" value="Masculino" />
            <v-radio label="Feminino" value="Feminino" />
            <v-radio label="Outro" value="Outro" />
          </v-radio-group>
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="form.email"
            label="E-mail"
            placeholder="exemplo@email.com"
            variant="outlined"
            :error-messages="form.errors.email"
          />
        </v-col>

        <v-col cols="12" md="6">
          <v-text-field
            v-model="form.telefone"
            v-maska="'(##) #####-####'"
            label="Telefone"
            placeholder="(99) 99999-9999"
            variant="outlined"
            :error-messages="form.errors.telefone"
          />
        </v-col>
      </v-row>

      <div class="d-flex justify-end mt-4">
        <v-btn
          color="grey-lighten-2"
          class="text-none mr-2"
          rounded
          @click="goBack"
          :disabled="form.processing"
        >
          Voltar
        </v-btn>
        <v-btn
          color="primary"
          class="text-none"
          rounded
          type="submit"
          :loading="form.processing"
        >
          {{ isEditing ? 'Salvar Alterações' : 'Cadastrar' }}
        </v-btn>
      </div>
    </v-form>
  </v-sheet>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { vMaska } from 'maska/vue'

const props = defineProps({
  pessoa: {
    type: Object,
    default: null,
  },
})



const isEditing = ref(!!props.pessoa?.id)

// useForm para criar ou editar
const form = useForm({
  nome: props.pessoa?.nome ?? '',
  cpf: props.pessoa?.cpf ?? '',
  data_nascimento: props.pessoa?.data_nascimento ?? '',
  sexo: props.pessoa?.sexo ?? '',
  email: props.pessoa?.email ?? '',
  telefone: props.pessoa?.telefone ?? '',
})

const goBack = () => window.history.back()

watch(
  ()=>form.cpf,
  (value) => {
    form.post(route('pessoas.store'), {
      preserveState: true,
      only: ['errors'],
    })
  }
)

const dataMaxima = computed(() => {
  const data = new Date()
  data.setFullYear(data.getFullYear() - 18)
  return data.toISOString().split('T')[0]
})

const submitForm = () => {
  if (isEditing.value) {
    if (!confirm('Tem certeza que deseja atualizar este cadastro?')) return
    form.put(route('pessoas.update', props.pessoa.id), {
      onSuccess: () => router.get(route('pessoas.index'))
    })
  } else {
    form.post(route('pessoas.store'), {
      preserveState: true,
      onSuccess: () => router.get(route('pessoas.index'))
    })
  }
}
</script>
