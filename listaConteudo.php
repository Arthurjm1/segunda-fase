<?php

include 'phpconectamysql.php';

function criaArvore($lista, $pai_id)
{
    $galho = [];
    foreach ($lista as $conteudo) {
        if ($conteudo['ConteudoID'] == $pai_id) {
            array_push($galho, $conteudo);
            criaArvore($lista, $conteudo);
        }
    }

    return ($galho);
}

function criarLista($galho)
{
    $li = '';
    if (isset($galho['Titulo'])) {
        $li .= '<li><a>' . $galho['Titulo'] . '</a>';
    }
    if (isset($galho['filhos']) && isset($galho['Titulo'])) {
        $li .= '<ul>';
        foreach ($galho['filhos'] as $filho) {
            $li .= criarLista($filho);
        }
        $li .= '</ul>';
    }
    $li .= '</li>';

    return $li;   
}

function listaConteudo(int $id, PDO $con)
{
    $query = 'SELECT ID, Titulo, imobiliariaID, ConteudoID FROM conteudo WHERE imobiliariaID = ' . $id;
    $stmt = $con->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $arvore = [];   
    foreach ($result as $conteudo) {
        if ($conteudo['ConteudoID'] == 0) {
            $arvore[$conteudo['ID']] = $conteudo;
            $arvore[$conteudo['ID']]['filhos'] = [];
        }
    }

    foreach ($result as $conteudo) {
        $arvore[$conteudo['ID']]['filhos'] = criaArvore($result, $conteudo['ID']);
    }
    echo '<ul>';
    foreach ($arvore as $galho) {
        echo criarLista($galho);
    }
    echo '</ul>';
}

listaConteudo(99901, $con);