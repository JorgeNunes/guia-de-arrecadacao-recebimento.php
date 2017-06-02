<?php

include_once("GuiaArrecadacaoRecebimento.php");

/*
    dev: Jorge Nunes
    
    Gerador de linha digitavel para guia de arrecadação e recebimento
    Instância do objeto GuiaArrecadacaoRecebimento
    
    Encapsulamento GuiaArrecadacaoRecebimento(Identificacao do Produto, Identificacao do Segmento, Identificacao do Valor Referente, 
                                              Valor Referente, Identificacao da Empresa_orgao, Data de Vencimento);
                        
    function setCampoLivreUtilizacaoEmpresa(, Numero do Documento)
    
*/

$exemplo = new GuiaArrecadacaoRecebimento(8,1,7, "8934.89", 3266, "20/06/2017");
echo $exemplo->setNumeroDocumento("314240011780")->get();