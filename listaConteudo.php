<?php

include 'phpconectamysql.php';

function criaArvore($resultados, $pai_id)
{    
    $arvore = [];    
    foreach($resultados as $conteudo){               
        if($conteudo['ConteudoID'] == $pai_id){                        
            $filhos = criaArvore($resultados, $conteudo['ID']);
            if($filhos){
                $conteudo['filhos'] = $filhos;
            }
            $arvore[] = $conteudo;            
        }
    }       
    return $arvore;
}

function criaLista($arvore)
{
    $li = '<ul>';
    foreach($arvore as $conteudo){
        $li .= "<li><a href='#'>".$conteudo['Titulo'].'</a>';
        if(isset($conteudo['filhos'])){
            $li .=  criaLista($conteudo['filhos']);
        }
        $li .= '</li>';
    }
    $li .= '</ul>';
    return $li;
}

function listaConteudo(int $id, PDO $con)
{
    $query = 'SELECT ID, Titulo, imobiliariaID, ConteudoID FROM conteudo WHERE imobiliariaID = ' . $id;
    $stmt = $con->prepare($query);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $arvore = [];

    $arvore = criaArvore($resultados, 0);

    echo criaLista($arvore);
}

listaConteudo(99901, $con);