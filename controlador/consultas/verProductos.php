<?php

    include_once("../../controlador/consultas/conexion.php");
    $respuesta = array();

    if(!empty($_POST['clave'])) {
        $clave = $_POST['clave'];
    }

    $querySelect = "SELECT pr.TokenProd, c.NomCategoria, m.NomMarca, pr.NomProducto, pr.Porcion, pr.PrecioOriginal, pr.PrecioOferta 
        FROM productos pr, marca m, relacionprodcat rel, categoria c 
        WHERE m.IdMarca = pr.IdMarca AND rel.IdProducto = pr.IdProducto AND rel.IdCategoria = c.IdCategoria";

    if(isset($_GET['clave']) && !empty($_GET['clave'])) {
        $clave = $_GET['clave'];
        $querySelectSearch = "SELECT pr.TokenProd, c.NomCategoria, m.NomMarca, pr.NomProducto, pr.Porcion, pr.PrecioOriginal, pr.PrecioOferta, pr.Imagen, pr.Descripcion 
        FROM productos pr, marca m, relacionprodcat rel, categoria c 
        WHERE m.IdMarca = pr.IdMarca AND rel.IdProducto = pr.IdProducto AND rel.IdCategoria = c.IdCategoria AND TokenProd='$clave'";
        
        if($result2 = $cn->query($querySelectSearch)) {

            for($i = 0; $i<$result2->num_rows;$i++) {
                $respuesta[$i] = $result2->fetch_assoc();
            }
        } else {
            $respuesta['estado'] = "2";
        }
        
    } else if(empty($_POST['clave'])) {

        if (!empty($_POST['buscarProd'])) {
            $txtChar = $cn->real_escape_string($_POST['buscarProd']);   // Solo permite caracteres string
            $where = " AND pr.NomProducto LIKE '%".$txtChar."%'";       // Concateno
            $querySelect = $querySelect.$where;
        }

        if($result = $cn->query($querySelect)) {

            for($i = 0; $i<$result->num_rows;$i++) {
                $respuesta[$i] = $result->fetch_assoc();
            }

        } else {
            $respuesta['estado'] = "2";
        }

    } else {

        $querySelectSearch = "SELECT pr.TokenProd, c.IdCategoria, m.IdMarca, pr.NomProducto, pr.Porcion, pr.PrecioOriginal, pr.PrecioOferta, pr.Imagen, pr.Descripcion 
        FROM productos pr, marca m, relacionprodcat rel, categoria c 
        WHERE m.IdMarca = pr.IdMarca AND rel.IdProducto = pr.IdProducto AND rel.IdCategoria = c.IdCategoria AND TokenProd='$clave'";
        
        if($result2 = $cn->query($querySelectSearch)) {

            for($i = 0; $i<$result2->num_rows;$i++) {
                $respuesta[$i] = $result2->fetch_assoc();
            }
        } else {
            $respuesta['estado'] = "2";
        }
        
    }
    

    // CONVIERTE E IMPRIME EL ARRAY EN UN OBJETO JSON
    header('Content-Type: application/json');
    echo json_encode($respuesta);