<?php ///ALTERAR SENHA DO BD
$correto=false;
$valido= false;
$nome =NULL;
session_start();
if (isset($_SESSION["usuario"])) //garantir que é uma nova sessão
{
session_destroy();
}

if (isset ($_COOKIE["visitas"]))
        {
        $visitas = $_COOKIE["visitas"] +1;    
        }
        else{
        $visitas=1;
        
        }
        setcookie("visitas",$visitas, time()+(30*24*60*60));
        
        echo "Número de visitas no site ".$visitas."<BR><BR>";
       
if(isset($_REQUEST["validar"]) && $_REQUEST["validar"] == true)
{
    $valido= true;
    try
        {
            //new PDO(mysql:host=localhost;dbname=curso,"root","senhadobanco") 
           $connection = new PDO("mysql:host=localhost;dbname=gabrielaugusto","root","123456"); 
           $connection->exec("set names utf8");
        }
        catch(PDOException $e)
        {
            echo "Falha: ". $e->getMessage();
            exit();
            
        }
        
            $rs =$connection->prepare("SELECT id, usuario, senha FROM admin");
        
        if($rs->execute()) // RETORNA 1 CASO A OPERAÇÃO SEJA REALIZADA COM SUCESSO
        {
            
           while($registro = $rs->fetch(PDO::FETCH_OBJ))//Retorna o usuario seguinte em cada iteração
           {
            if ($registro->usuario==$_POST["usuario"] && $registro->senha == md5($_POST["senha"]))
                {
                    $correto=true;
                    session_start();
                    $_SESSION["id"] = $registro->id;
                    $_SESSION["usuario"] = $registro->usuario;
                    
                   header("location: proj_admin.php");
                    
                    
                }
        
            }
           if ($correto ==false)
           {
            echo "Dados inválidos.";
           }
        }
        else
            {
                echo "Falha no acesso ao usuário.";
            }
}       
?>

<HTML>
    <HEAD>
        <meta charset="UTF-8">
        <TITLE> Login</TITLE>
        <LINK rel="stylesheet" href="style.css">

 
    </HEAD>
    <BODY>
<HTML lang="en-US">
<HEAD>
  <META charset="utf-8">
    <TITLE>Login Funcionário</title>
    
    <LINK rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,700">

</HEAD>
    <?php
        
    if($valido == false || $correto== false)
                {       
        ?>
        <DIV id="login">

        <FORM  name='form-login'  method=POST action="?validar=true">
       
        <span class="fontawesome-user"></span>        
        <INPUT type=TEXT class= "form-control" name=usuario placeholder="Enter username"
            <?php if(isset($_POST["usuario"])) { echo "value='" . $_POST["usuario"] . "'"; } ?>
            ><BR>
         
         <span class="fontawesome-lock"></span>
            <INPUT type=PASSWORD class= "form-control" name="senha" placeholder="Enter password"><BR>
        
       
       <INPUT type="submit" value="Enviar">
        <?php
        }
        ?>
        

        
    </BODY>
    </HTML>

