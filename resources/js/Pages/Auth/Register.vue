<template>
  <GuestLayout>
    <Head title="Registrar" />

    <v-container class="d-flex justify-center align-center" style="min-height: 100vh;">
      <v-card elevation="6" width="400" class="pa-6">
        <v-card-title class="text-h5 text-center mb-4">
          Criar Conta
        </v-card-title>

        <v-form @submit.prevent="submit" class="space-y-4">
          <v-text-field
            label="Nome"
            v-model="form.name"
            :error-messages="form.errors.name"
            outlined
            dense
            required
          ></v-text-field>

          <v-text-field
            label="E-mail"
            type="email"
            v-model="form.email"
            :error-messages="form.errors.email"
            outlined
            dense
            required
          ></v-text-field>

          <v-text-field
            label="Senha"
            type="password"
            v-model="form.password"
            :error-messages="form.errors.password"
            outlined
            dense
            required
          ></v-text-field>

          <v-text-field
            label="Confirmar Senha"
            type="password"
            v-model="form.password_confirmation"
            :error-messages="form.errors.password_confirmation"
            outlined
            dense
            required
          ></v-text-field>

          <div class="d-flex justify-space-between align-center mt-6">
            <Link :href="route('login')" class="text-sm text-indigo-600">
              Já possui conta?
            </Link>

            <v-btn
              color="primary"
              type="submit"
              :loading="form.processing"
              :disabled="form.processing"
              class="px-6"
            >
              Registrar
            </v-btn>
          </div>
        </v-form>
      </v-card>
    </v-container>
  </GuestLayout>
</template> 

<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>