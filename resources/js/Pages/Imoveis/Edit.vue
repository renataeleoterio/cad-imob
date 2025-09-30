<template>
  <AppLayout>
    <v-container>
      <v-card class="pa-4">
        <v-card-title>Editar Imóvel</v-card-title>

        <v-form @submit.prevent="submit">
          <v-row>
            <!-- Tipo -->
            <v-col cols="12" md="4">
              <v-select 
                v-model="form.tipo"
                :items="['Terreno','Casa','Apartamento']"
                label="Tipo"
                :error-messages="form.errors.tipo"
                required
              />
            </v-col>

            <!-- Área do Terreno -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.area_terreno"
                label="Área do Terreno (m²)"
                inputmode="decimal"
                :error-messages="form.errors.area_terreno"
              />
            </v-col>

            <!-- Área da Edificação -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.area_edificacao"
                label="Área da Edificação (m²)"
                inputmode="decimal"
                :error-messages="form.errors.area_edificacao"
              />
            </v-col>

            <!-- Logradouro -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.logradouro"
                label="Logradouro"
                :error-messages="form.errors.logradouro"
                required
              />
            </v-col>

            <!-- Número -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.numero"
                type="number"
                label="Número"
                :error-messages="form.errors.numero"
                required
              />
            </v-col>

            <!-- Bairro -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.bairro"
                label="Bairro"
                :error-messages="form.errors.bairro"
                required
              />
            </v-col>

            <!-- Complemento -->
            <v-col cols="12" md="4">
              <v-text-field
                v-model="form.complemento"
                label="Complemento"
                :error-messages="form.errors.complemento"
              />
            </v-col>

            <!-- Contribuinte -->
            <v-col cols="12" md="4">
              <v-select
                v-model="form.contribuinte_id"
                :items="pessoasSelect"
                item-value="id"
                item-title="nome"
                label="Contribuinte"
                :error-messages="form.errors.contribuinte_id"
                required
              />
            </v-col>

            <!-- Situação (apenas leitura/desabilitado) -->
            <v-col cols="12" md="4">
              <v-select
                v-model="form.situacao"
                :items="situacoes"
                label="Situação"
                :error-messages="form.errors.situacao"
                disabled
              />
            </v-col>

            <!-- Botões -->
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
      </v-card>
    </v-container>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from 'laravel-precognition-vue-inertia';

const props = defineProps({
  imovel: Object,
  pessoas: Array,
  situacoes: Array,
})


const form = useForm('put', route('imoveis.update', { imovei: props.imovel.id }), {
   tipo: props.imovel.tipo || '',
  area_terreno: props.imovel.area_terreno || '',
  area_edificacao: props.imovel.area_edificacao || '',
  logradouro: props.imovel.logradouro || '',
  numero: props.imovel.numero || '',
  bairro: props.imovel.bairro || '',
  complemento: props.imovel.complemento || '',
  contribuinte_id: props.imovel.contribuinte_id || '',
  situacao: props.imovel.situacao || 'Ativo',
})


function submit() {
  form.submit({
    onSuccess: () => router.get(route('imoveis.index')),
  })
}
</script>
