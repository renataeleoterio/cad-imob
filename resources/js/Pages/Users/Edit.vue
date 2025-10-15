<template>
  <AppLayout>
    <v-sheet class="mx-auto pa-md-12 pa-4" max-width="900" rounded="lg">

      <v-alert
      v-if="flash?.success"
      type="success"
      outlined
      class="mb-4"
      dense
      elevation="2"
    >
      {{ flash.success }}
    </v-alert>

      <v-alert
        v-if="flash?.error"
        type="error"
        outlined
        class="mb-4"
        dense
        elevation="2"
      >
        {{ flash.error }}
      </v-alert>

    <v-card-title class="d-flex justify-space-between align-center mb-4">
      <span class="text-h5">Editar Usuário</span>
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
            label="CPF"
            placeholder="000.000.000-00"
            variant="outlined"
            readonly
            :error-messages="form.errors.cpf"
          />
        </v-col>
      </v-row>

      <v-row>
        <v-col cols="12" md="6">
          <v-text-field
            v-model="form.email"
            label="E-mail"
            placeholder="exemplo@email.com"
            variant="outlined"
            readonly
            :error-messages="form.errors.email"
          />
        </v-col>

        <v-col cols="12" md="6">
          <v-select
            v-model="form.perfil"
            :items="[
              { title: 'Administrador da TI', value: 'T' },
              { title: 'Administrador do Sistema', value: 'S' },
              { title: 'Atendente', value: 'A' }
            ]"
            label="Perfil"
            variant="outlined"
            :error-messages="form.errors.perfil"
          />
        </v-col>
      </v-row>

     
      <v-row>
        <v-col cols="12" md="6">
          <v-switch
            v-model="form.ativo"
            label="Usuário Ativo"
            inset
            color="primary"
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
          Salvar Alterações
        </v-btn>
      </div>
    </v-form>
  </v-sheet>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  user: {
    type: Object,
    required: true,
  },
})

const flash = computed(() => usePage().props.flash)

const form = useForm({
  nome: props.user.nome,
  cpf: props.user.cpf,
  email: props.user.email,
  perfil: props.user.perfil,
  ativo: props.user.ativo === 'S',
})

const goBack = () => window.history.back()

const submitForm = () => {
  if (!confirm('Tem certeza que deseja salvar as alterações deste usuário?')) return
  
  console.log('📤 Dados ANTES do envio:')
  console.log('Nome:', form.nome)
  console.log('Perfil:', form.perfil)
  console.log('Ativo:', form.ativo, '(tipo:', typeof form.ativo, ')')
  console.log('Dados completos:', form.data())
  
  form.put(route('users.update', props.user.id), {
    preserveState: false,
    preserveScroll: true,
    onSuccess: (page) => {
      console.log('✅ SUCESSO - Dados enviados')
      console.log('Flash message', flash.value)
      console.log('Flash:', page.props.flash)
    },
    onError: (errors) => {
      console.error('❌ ERROS:', errors)
    }
  })
}
</script>