<template>
  <AppLayout>
    <Head title="Cadastro de Usuário" />

    <v-container class="d-flex justify-center align-center py-10">
      <v-card elevation="6" width="500" class="pa-6">
        <v-card-title class="text-h5 text-center mb-4">
          Cadastrar Usuário
        </v-card-title>

        <v-form @submit.prevent="submit" class="space-y-4">
          <v-text-field
            label="Nome completo"
            v-model="form.nome"
            :error-messages="form.errors.nome"
            outlined
            dense
            required
          />

          <v-text-field
            label="E-mail funcional"
            v-model="form.email"
            type="email"
            :error-messages="form.errors.email"
            outlined
            dense
            required
          />

          <v-text-field
            label="Senha"
            v-model="form.senha"
            type="password"
            :error-messages="form.errors.senha"
            outlined
            dense
            required
          />

          <v-select
            label="Perfil"
            v-model="form.perfil"
            :items="perfis"
            item-title="label"
            item-value="value"
            :error-messages="form.errors.perfil"
            outlined
            dense
            required
          />

          <v-text-field
            label="CPF"
            v-model="form.cpf"
            :error-messages="form.errors.cpf"
            outlined
            dense
            required
            maxlength="14"
            placeholder="000.000.000-00"
          />

          <v-radio-group
            v-model="form.ativo"
            row
            label="Ativo?"
            :error-messages="form.errors.ativo"
          >
            <v-radio label="Sim" value="S" />
            <v-radio label="Não" value="N" />
          </v-radio-group>

          <div class="d-flex justify-space-between mt-6">
            <Link
              :href="route('users.index')"
              class="text-sm text-indigo-600"
            >
              Voltar
            </Link>

            <v-btn
              color="primary"
              type="submit"
              :loading="form.processing"
              :disabled="form.processing"
            >
              Salvar
            </v-btn>
          </div>
        </v-form>
      </v-card>
    </v-container>
  </AppLayout>
</template>

<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

// formulario
const form = useForm({
  nome: '',
  email: '',
  senha: '',
  perfil: '',
  cpf: '',
  ativo: 'S',
})

const submit = () => {
  form.post(route('users.store'), {
    onSuccess: () => form.reset('senha', 'senha_confirmation'),
  })
}

const perfis = [
  { label: 'TI', value: 'T' },
  { label: 'Sistema', value: 'S' },
  { label: 'Usuário', value: 'U' },
]
</script>