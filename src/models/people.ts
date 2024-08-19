export interface PersonGender {
  M: 'Masculino';
  F: 'Feminino';
}

export interface Person {
  id: number;
  name: string;
  birthDate?: Date;
  gender?: Gender;
  rg?: string;
  orgaoEmissor?: string;
  rgDataEmissao?: string;
  cpf?: string;
  carteiraDeTrabalho?: string;
  address?: string;
  zipCode?: string;
  neighborhood?: string;
  city?: string;
  state?: string;
  email?: string;
  father?: string;
  mother?: string;
  phone?: string;
  phoneContact?: string;
  phone2?: string;
  phoneContact2?: string;
  cellphone?: string;
  cellphone2?: string;
  cellphone3?: string;
}

export interface PersonQueryResult {
  nome: string;
  dtnascimento?: Date;
  sexo?: string;
  rg?: string;
  orgaoemissor?: string;
  rgdataemissao?: string;
  cpf?: string;
  carteiradetrabalho?: string;
  endereco?: string;
  cep?: string;
  bairro?: string;
  cidade?: string;
  estado?: string;
  telefone?: string;
  celular?: string;
  email?: string;
  pai?: string;
  mae?: string;
  telcontato?: string;
  telefone2?: string;
  tel2contato?: string;
  celular2?: string;
  celular3?: string;
}
