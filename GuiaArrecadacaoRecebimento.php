<?php

/*
    Dev: Jorge Nunes
    
    GuiaArrecadacaoRecebimento
*/


class GuiaArrecadacaoRecebimento {
    
    /* Variaveis de apoio */
    private $identificacao_do_produto; /* Identificação do Produto posição 01-01 tamanho 1*/
    private $identificacao_do_segmento; /* Identificação do Segmento posição 02-02 tamanho 1*/
    private $identificacao_do_valor_referente; /* Identificação do valor_referente real ou referência posição 03-03 tamanho 1*/
    private $valor_referente; /* valor_referente posição 05-15 tamanho 11 */
    private $identificacao_da_empresa_orgao; /* Identificação da Empresa/Orgão posição 16-19 tamanho 4*/
    private $campo_livre_utilizacao_empresa; /* Campo livre de utilização da Empresa/Orgão posição 20-44 tamanho 25 */
    private $codigo_linha_digital; 
    
    /* Método construtor */
    function __construct($identificacao_do_produto, $identificacao_do_segmento, $identificacao_do_valor_referente,
                $valor_referente, $identificacao_da_empresa_orgao, $data_vencimento){
                            
        $this->identificacao_do_produto = $identificacao_do_produto;
        $this->identificacao_do_segmento = $identificacao_do_segmento;
        $this->identificacao_do_valor_referente = $identificacao_do_valor_referente;
        
        $this->valor_referente = $valor_referente;
        $this->valor_referente = str_replace("." , "" , $this->valor_referente); // tira os pontos
        $this->valor_referente = str_replace("," , "" , $this->valor_referente); // tira a vírgula
        
        $this->valor_referente = str_pad($this->valor_referente, 11, "0", STR_PAD_LEFT); // adiciona zero restantes
        
        $this->identificacao_da_empresa_orgao = $identificacao_da_empresa_orgao;
        
        $data_vencimento = str_replace("/" , "-" , $data_vencimento); //substitui barra por traço
        
        $this->campo_livre_utilizacao_empresa = date("Ymd", strtotime($data_vencimento)); // padrão AAAAMMDD
    }
    
    /* 
        setNumeroDocumento
        return GuiaArrecadacaoRecebimento
    */ 
    public function setNumeroDocumento($numero_documento){
        
        $count = 25 - strlen ($this->campo_livre_utilizacao_empresa);
        
        $numero_documento = str_pad($numero_documento, $count, "0", STR_PAD_LEFT); // adiciona zero restantes
        
        $this->campo_livre_utilizacao_empresa = $this->campo_livre_utilizacao_empresa.$numero_documento;
        
        return $this;
    }
    
    /* 
        calculoDac
        return Código Linha Digitavel 
    */ 
    private function calculoDac($codigo){
        $this->codigo_linha_digital = $codigo;
        
        for($i = 0; $i < strlen ($codigo); $i++){
            if($i == 3){
                $calculo_codigo = $calculo_codigo.$this->digitoVerificadorGeral($codigo); // digito verificador geral
            }
            $calculo_codigo = $calculo_codigo.$codigo[$i]; // concatenação do caracter
        }
        
        $codigo = "";
        
        for($i = 0; $i < strlen ($calculo_codigo); $i++){ // divisão do código em 4 partes
            if($i == 11 || $i == 22 || $i == 33){
                $codigo = $codigo."-".$this->digitoVerificadorGeral(substr($calculo_codigo, $i - 11, 11))." "; // digito verificador
            }
            $codigo = $codigo.$calculo_codigo[$i]; // concatenação do caracter
        }
        
        $codigo = $codigo."-".$this->digitoVerificadorGeral(substr($calculo_codigo, 33, 11)); // ultimo digito verificador
        
        return $codigo;
    }
    
    /* 
        digitoVerificadorGeral
        return Digito verificador
    */
    private function digitoVerificadorGeral($codigo){
        $verificador = true;
        
        for($i = 0; $i < strlen ($codigo); $i++){
            if($verificador == true){
                $calculo_codigo = $calculo_codigo.$codigo[$i] * 2; // Regra de negocio Febraban Versão 5 (Modulo 10)
                $verificador = false;
            }else{
                $calculo_codigo = $calculo_codigo.$codigo[$i] * 1; // Regra de negocio Febraban Versão 5 (Modulo 10)
                $verificador = true;
            }
        }
        
        for($i = 0; $i < strlen ($calculo_codigo); $i++){
            $soma = $soma+$calculo_codigo[$i]; // Regra de negocio Febraban Versão 5 soma dos caracter (modulo 10)
        }
        
        if($soma%10 == 0){
            return 0;
        }
        
        return 10-($soma%10);
    }
    
    /* 
        get
        return Código Linha Digitavel
    */
    public function get(){
        $this->codigo_linha_digital = $this->identificacao_do_produto.$this->identificacao_do_segmento.
                 $this->identificacao_do_valor_referente.$this->valor_referente.
                 $this->identificacao_da_empresa_orgao.$this->campo_livre_utilizacao_empresa; // Concatenção dos dados passados no construtor     
                 
        return  $this->calculoDac($this->codigo_linha_digital); // get da Linha Digitavel na regra do Febraban Versão 5 (Modulo 10)
    }
    
}