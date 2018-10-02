
<?php

$valido=false;        
try
        {
           $connection = new PDO("mysql:host=localhost;dbname=gabrielaugusto","root","123456"); 
           $connection->exec("set names utf8");
        }
        catch(PDOException $e)
        {
            echo "Falha: ". $e->getMessage();
            exit();
            
        }
        session_start();
        if (!isset($_SESSION["usuario"]) )
        {
            
            echo "erro, operação inválida";
            exit();
        }
        else{
        $rs =$connection->prepare("SELECT usuario FROM admin");
        
                if($rs->execute()) // RETORNA 1 CASO A OPERAÇÃO SEJA REALIZADA COM SUCESSO
                {
                    while($registro = $rs->fetch(PDO::FETCH_OBJ))//Retorna o usuario seguinte em cada iteração
                    {
                        if ($_SESSION["usuario"] == $registro->usuario && isset($_SESSION["usuario"]))
                        {
                            $valido = true;
                        }
                    }
                }
            }
            ?>
        

           <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <TITLE>Página Inicial</TITLE>
        <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <STYLE>
        table.tela_inicial{
            border-collapse: separate;
            border-spacing:10px;
        }
            th{
            background-color: rgb(200,200,200);
            
        }
    </STYLE>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/metisMenu.min.css" rel="stylesheet">

    <!-- Social Buttons CSS -->
    <link href="css/bootstrap-social.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/morris.css" rel="stylesheet">
    
    <link href="style2.css" rel="stylesheet">
    
    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="4.7.0/css/font-awesome.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                
                <a class="navbar-brand" >
                  <?php
                    if ($valido ==true)
                    {
                     echo "Seja Bem Vindo ".$_SESSION["usuario"]."<BR><BR>";
                     ?>
                  </a>
                            
            <topright>
            <img src="cruz_vermelha.png" alt="cruz_vermelha" style="width:100%">
            </topright>
            
            </div>
    
            <div class="navbar-default sidebar" role="navigation" >
                
                <ul class="nav" id="side-menu">
                        <li>   
                             <a href="#"><i class="fa fa-calendar fa-fw"></i> Agendamento de exames</a>
                        </li>
                        <!--
                        <li>
                            <a ><i class="fa fa-files-o fa-fw"></i> Carteiras De Vacinação<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Carteira de Vacinação de Crianças</a>
                                </li>
                                <li>
                                    <a href="#">Carteira de Vacinação de Adolescentes</a>
                                </li>
                                <li>
                                    <a href="#">Carteira de Vacinação de Adultos</a>
                                </li>
                            </ul>
                            -->
                            <!-- /.nav-second-level -->
                        <!-- </li> -->
                        
                        <li>
                            <a href="#"><i class="fa fa-info-circle fa-fw"></i> Informações do Hospital </a>

                        </li>
                        
                        <li>
                            <a href="#"><i class="fa fa-phone fa-fw"></i> Fale Conosco</a>
                        </li>                        
                </ul>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                     
                    <!-- <div class="container">-->
                        <div class="borderright">
                              
                              <p>Admin</p>
                                <h5>
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#home">Inserir Médicos</a></li>
                                        <li><a data-toggle="tab" href="#menu0">Listar Médicos</a></li>
                                        <li><a data-toggle="tab" href="#menu1">Buscar Médicos</a></li>
                                        <li><a href="index.php">Logout</a></li>
                                </ul>
                                </h5>
                        
                                <?php
                                }
                                ?>
                        </div>
                    </h1>
                    
                </div>
                
             </div>

            <BR>
      
        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <h3>Inserir Médicos</h3>
                    <p>   
                    <?php include "proj_inserir.php";?>
                    <BR>  
                    </p>
            </div>
            <div id="menu0" class="tab-pane fade">
                <h3>Listar médicos</h3>
                    <p>   
                    <?php
                    //opção de buscar médico pelo crm ou nome
                     include "proj_listar.php";?>
                    <BR>
                    </p>
            </div>

            <div id="menu1" class="tab-pane fade">
                <h3>Buscar médicos</h3>
                    <p>   
                    <?php
                     include "proj_buscar.php";?>
                    <BR>
                    </p>
            </div>
    
        </div>
    
        </div>
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    </BODY>
</HTML>