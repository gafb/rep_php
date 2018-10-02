
<?php
// No banco eh melhor telefone, cpf e crm serem varchar, pois se comecarem com zero, o 0 nao sera armazenado
$erro = null;
$teste = false;

session_start();
if (isset($_SESSION["usuario"]))
    {
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
        include "WebService_Consumidor.php";

        $str = file_get_contents('arrays.json');
        $json = json_decode($str, true); // decode the JSON into an associative array

      if(isset($_REQUEST["alterar"]) && $_REQUEST["alterar"] == true) //Validações de dados INCOMPLETO
      {
        

        $especialidades = "";
                for ($i=0; $i< sizeof($json["especialidades"]); $i++){
                    $aux =(isset($_POST["especialidade".$i])) ? $json["especialidades"][$i] : ""; 
                    if ($aux !=""){
                        $especialidades = $especialidades.','.$aux;
                    }
                    
                }
        //para validacoes
        $caracters = array("(", ")", " ", "-", ".");
                
                $_POST["cpf"] = str_replace($caracters, "", $_POST["cpf"]);
                $_POST["telefone"] = str_replace($caracters, "", $_POST["telefone"]);
        
        if(strlen(utf8_decode($_POST["nome"])) < 5)
        {
            $erro = "Preencha o campo nome corretamente (5 ou mais caracteres)";
        }
        
        else if(is_numeric($_POST["cpf"]) == false || strlen($_POST["cpf"])!=11)
        {
            $erro = "Campo CPF deve ser numérico ou você digitou um número inválido";  
        }
        
        else if(is_numeric($_POST["crm"]) == false || strlen($_POST["crm"])>7)
        {
            $erro = "Campo CRM deve ser numérico ou você digitou um número inválido";  
        }

        else if(is_numeric($_POST["telefone"]) == false || (strlen($_POST["telefone"])!=10 && strlen($_POST["telefone"])!=11))
        {
            $erro = "Campo telefone deve ser numérico ou você digitou um número inválido";  
        }

        else if(strlen(utf8_decode($_POST["email"])) < 6 || ((strpos(utf8_decode($_POST["email"]),"@"))=="") || ((strpos(utf8_decode($_POST["email"]),".com"))==""))
        { ////strpos ve se encontra a substring na string, caso não encontre retorna caractere ""
            $erro = "E-mail inválido, preencha corretamente";
        }
        else if($_POST["ocupacao"] == "" )
        {
                    $erro = "Selecione o campo de ocupação corretamente";
        }
        else if(substr_count($especialidades, ',') < 2 )
        { 
                    $erro = "Coloque pelo menos duas especialidades";
        }
            else
            {      
               $teste = true;
               
                     $sql = "UPDATE medicos SET
                                          nome=?,
                                          cpf=?,
                                          crm=?,
                                          telefone=?,
                                          email=?,
                                          ocupacao=?,
                                          especialidades=?
                                          WHERE id =?";
                                    
                        $stmt = $connection->prepare($sql);
                        
                        $stmt->bindParam(1,$_POST["nome"]);
                        $stmt->bindParam(2,$_POST["cpf"]);
                        $stmt->bindParam(3,$_POST["crm"]);
                        $stmt->bindParam(4,$_POST["telefone"]);
                        $stmt->bindParam(5,$_POST["email"]);
                        $stmt->bindParam(6,$_POST["ocupacao"]);
                        $stmt->bindParam(7,$especialidades);
                        $stmt->bindParam(8,$_POST["id"]);
                        
                        $stmt->execute();
            
                        if ($stmt->errorCode()!= "0000")
                        {
                           $teste= false;
                           $erro = "Erro código".$stmt->errorCode() . ":";
                           $erro .= implode(",", $stmt->errorInfo());
                        }
                        else{
                        #echo "Alterado com sucesso";
                        //header("location: proj_admin.php");
                        }
        }
    }
      else
      {            
                  $rs = $connection->prepare("SELECT * FROM medicos WHERE ID = ?");
                
                  $rs->bindParam(1,$_REQUEST["id"]);
                  
                  if($rs->execute())
                  {
                     if ($registro = $rs->fetch(PDO::FETCH_OBJ)) //Busca a pessoa nos registro do banco de dados
                     {
                        $_POST["nome"] = $registro->nome;
                        $_POST["cpf"] = $registro->cpf;
                        $_POST["crm"] = $registro->crm;
                        $_POST["telefone"] = $registro->telefone;
                        $_POST["email"] = $registro->email;
                        $_POST["ocupacao"] = $registro->ocupacao;
                            
                       
                        $aux = array_fill(0, sizeof($json["especialidades"]), NULL); //Creating empty array
                        for ($i=0; $i< sizeof($json["especialidades"]); $i++){

                            $_POST["especialidade".$i] = $aux[$i];
                        }
                        
                        $_POST["id"] = $registro->id;
                        
                     }
                     else
                        {
                        $erro= "Registro não encontrado<BR>";
                        header("location: proj_admin.php");
                        }
                  }
                  else
                  {
                     $erro = "Falha na captura do registro<BR>";
                  }
                  
                }
            
      ?>
      <HTML>
         <HEAD>
               <TITLE>BD_MED_ALTERAR</TITLE>
         </HEAD>
         <BODY>
            <?php
        
            if($teste == true) //Primeira verificação
            {
               //Se dados alterados com sucesso voltar a página inicial
               #echo "Alo";
               header("location: proj_admin.php");
                
            }
            else
            {
                if(isset($erro))
                {
                    echo $erro . "<BR><BR>";
               }
            }           
               ?>
        <FORM method=POST action="?alterar=true">
        
        <script type='text/javascript' src='//code.jquery.com/jquery-compat-git.js'></script>
        <script type='text/javascript' src='//igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js'></script>

        Nome (completo):
                                <INPUT type=TEXT name=nome 
                                <?php if(isset($_POST["nome"])) { echo "value='" . $_POST["nome"] . "'"; } ?>
                                ><BR>
                                CPF
                                    <INPUT type=TEXT name=cpf class ="cpf"
                                <?php if(isset($_POST["cpf"])) { echo "value='" . $_POST["cpf"] . "'"; } ?>
                                ><BR>

                               CRM
                                    <INPUT type=TEXT name=crm class ="crm"
                                <?php if(isset($_POST["crm"])) { echo "value='" . $_POST["crm"] . "'"; } ?>
                                ><BR>
                                
                                <script>
                                
                                
                                $('.cpf').mask("000.000.000-00");
                                $('.crm').mask("0000000");
                  
        
                        </script>

                                Telefone (números juntos):
                                 
        
                                
                                
                                <INPUT type=TEXT name=telefone class="telefone"
                                <?php if(isset($_POST["telefone"])) { echo "value='" . $_POST["telefone"] . "'"; } ?>
                                ><BR>
                                
                        <script>
                                
                                var behavior = function (val) {
                                    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                                },
                                
                                options = {
                                    onKeyPress: function (val, e, field, options) {
                                    field.mask(behavior.apply({}, arguments), options);
                                    }
                                };
                    $('.telefone').mask(behavior, options);
                  
        
                        </script>               
                                
                                E-mail:
                                <INPUT type=TEXT name=email
                                <?php if(isset($_POST["email"])) { echo "value='" . $_POST["email"] . "'"; } ?>
                                ><BR>
                                        
                                Ocupação:
                                <SELECT name="ocupacao">
                                    <OPTION value="">Selecione... </OPTION>
                                    <?php
                                    for ($i=0; $i< sizeof($service); $i++){

                                    
                                    ?>
                                        <OPTION
                                        <?php
                                        if(isset($_POST["ocupacao"]) && $_POST["ocupacao"] == $service[$i])
                                        { echo "selected"; }
                                        ?>
                                        ><?php echo $service[$i]; ?></OPTION>
                                    
                                    <?php }?>
                                    
                                </SELECT>
                                <BR>

                                    Especialidades:
                                    <?php
                                
                                    for ($i=0; $i< sizeof($json["especialidades"]); $i++){

                                    //Nao mostra as especialidades que estavam setadas antes
                                    ?>
                                <INPUT type=CHECKBOX name=<?php echo "especialidade".$i; ?>
                                <?php if(isset($_POST["especialidade".$i])) { echo "checked"; } ?>
                                > 
                                <?php
                                echo $json["especialidades"][$i];
                                    }
                                ?>


                                <BR>
                                <INPUT type=HIDDEN name=id value="<?php echo $_REQUEST["id"]; ?>">
                                <BR>
                                <INPUT type=SUBMIT value="Enviar">
        </FORM>
    <?php
    }
    else{
        header("location: proj_admin.php");
    }
    ?>
    </BODY>
</HTML>