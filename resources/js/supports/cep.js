import axios from "axios";
export const getCep = async (cep) => {
  // viacep.com.br/ws/01001000/json/
  /*
    {
      "cep": "01001-000",
      "logradouro": "Praça da Sé",
      "complemento": "lado ímpar",
      "bairro": "Sé",
      "localidade": "São Paulo",
      "uf": "SP",
      "ibge": "3550308",
      "gia": "1004",
      "ddd": "11",
      "siafi": "7107"
    }
  */
  let result = {
    error: true,
    message: "O cep não foi encontrado.",
    data: {},
  };
  if (!cep) {
    return result;
  }
  if (cep.trim().length < 1) {
    return result;
  }

  cep = cep.replaceAll("-", "").replaceAll(" ", "");
  await axios({
    baseURL: "https://viacep.com.br/ws",
    url: `${cep}/json`,
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then(({ data }) => {
      if (!data.hasOwnProperty("erro")) {
        result.error = false;
        result.data = data;
        result.message = "Cep encontrado!";
      }
    })
    .catch(() => {});
  return result;
};

export default {
  getCep,
};
