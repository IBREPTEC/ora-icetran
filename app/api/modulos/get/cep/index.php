<?php 
header('Content-type: application/json');

// Criação do array em branco $address, a ser retornado no final da execução.
$address = [];

// Array auxiliar com as siglas dos estados para busca do idestado.
$estados = [
    '-',
    'AC', 
    'AL', 
    'AP', 
    'AM', 
    'BA', 
    'CE', 
    'DF', 
    'ES', 
    'GO', 
    'MA', 
    'MT', 
    'MS', 
    'MG', 
    'PA', 
    'PB', 
    'PR', 
    'PE', 
    'PI', 
    'RJ', 
    'RN', 
    'RS', 
    'RO', 
    'RR', 
    'SC', 
    'SP', 
    'SE', 
    'TO',
];

// Verificação se o valor recebido existe e é diferente de vazio.
if (isset($_POST['cep']) && !empty($_POST['cep'])) {
    // Recebe o parametro 'cep' de $_POST e remove todos os caracteres não númericos encontrados;
    $cep = $_POST['cep'];
    $cep = preg_replace('/[^0-9]/', '', $cep);

    // Validação para conferir se o após a limpeza de carcteres indesejados o CEP informado está no padrão correto de 8 digitos.
    if (!preg_match('/[0-9]{8}/', $cep)) {
        $address = [
            'erro' => 'Formato de CEP inválido.',
        ];
    } else {
        // Insere o valor tratado dentro do link da requisição como parametro;
        $url = 'https://viacep.com.br/ws/'.$cep.'/json/';

        // Preenche o array com o retorno da API
        $address = json_decode(file_get_contents($url), true);

        $idestado = array_search($address['uf'], $estados);
        $address += [
            'idestado' => $idestado,
        ];

        // Insere uma mensagem de erro caso a busca na API retorne erro = true
        if (isset($address['erro'])) {
            $address = [
                'erro' => 'CEP não encontrado.',
            ];
        }
    }    
} else {
    $address = [
        'erro' => 'O campo CEP não pode ser deixado em branco.',
    ];
}

echo json_encode($address);

?>