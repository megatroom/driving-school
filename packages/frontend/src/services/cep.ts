import axios from 'axios'

export interface ResponseCEP {
  cep: string
  logradouro: string
  complemento: string
  bairro: string
  localidade: string
  uf: string
  ibge: string
  gia: string
  ddd: string
  siafi: string
}

export const getAddressFromCEP = async (
  cep: string | number
): Promise<ResponseCEP> => {
  const res = await axios.get(`https://viacep.com.br/ws/${cep}/json/`)

  if (!res.data || res.data.erro) {
    throw new Error('CEP not found')
  }

  return res.data
}
