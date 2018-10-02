    <?php
    $erro = null;
    $valido = false;
    /* function telephone($number){
        $number="(".substr($number,0,2).") ".substr($number,2,-4)." - ".substr($number,-4);
        // primeiro substr pega apenas o DDD e coloca dentro do (), segundo subtr pega os números do 3º até faltar 4, insere o hifem, e o ultimo pega apenas o 4 ultimos digitos
        return $number;
    } */
    if (isset($_SESSION["usuario"]))
    { 
        include "WebService_Consumidor.php";
        $str = file_get_contents('arrays.json');
                  $json = json_decode($str, true); // decode the JSON into an associative array

        if(isset($_REQUEST["validaInsere"]) && $_REQUEST["validaInsere"] == true) //Para quando os dados forem enviados, parte das validações dos dados
            {
                $especialidades = "";
                for ($i=0; $i< sizeof($json["especialidades"]); $i++){
                    $aux =(isset($_POST["especialidade".$i])) ? $json["especialidades"][$i] : ""; 
                    if ($aux !=""){
                        $especialidades = $especialidades.','.$aux;
                    }
                    
                }
                #print ($especialidades);
                //VALIDACOES
                $caracters = array("(", ")", " ", "-", ".");
                
                $_POST["cpf"] = str_replace($caracters, "", $_POST["cpf"]);
                $_POST["telefone"] = str_replace($caracters, "", $_POST["telefone"]);

                //
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
                    $valido = true;
                        
                 
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

                              
                            $sql = "INSERT INTO medicos (nome, cpf, crm, telefone, email, ocupacao, especialidades )
                                            VALUES   (?,?,?,?,?,?,?)";
                                            
                                            
                                            $stmt = $connection->prepare($sql); //Criando a expressão SQL
                        
                    
                        $stmt->bindParam(1,$_POST["nome"]); //Troca das ??? pelo parâmetros desejados
                        $stmt->bindParam(2,$_POST["cpf"]);
                        $stmt->bindParam(3,$_POST["crm"]);

                        $stmt->bindParam(4,$_POST["telefone"]);
                        $stmt->bindParam(5,$_POST["email"]);
                        $stmt->bindParam(6,$_POST["ocupacao"]);
                        $stmt->bindParam(7,$especialidades);
                        
                                                
                        $stmt->execute(); //execução no BD
                        
                            if ($stmt->errorCode()!= "0000")
                                {
                                    $valido = false;
                                    $erro = "Erro código".$stmt->errorCode() . ":";
                                    $erro .= implode(",", $stmt->errorInfo());
                                }
                        
                        }
            }
                
                ?>
                    <HTML>
                        <HEAD>
                            <TITLE>Ficha médica básica</TITLE>
                        </HEAD>
                        <BODY>
                        
                            <?php
                            
                                if($valido == true)
                                { 
                                    ?>
                                    <form class="search-form" action='proj_admin.php'>
                                        <input type="submit" value="Refresh"/>
                                    </form>                     
                                   <?php
                                  
                                        
                                }
                                else
                                {
                            
                                    if(isset($erro))
                                    {
                                        echo $erro . "<BR><BR>";
                                    }
                            
                            ?>
                            <FORM method=POST id="myForm" action="?validaInsere=true">

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

                                    
                                    ?>
                                <INPUT type=CHECKBOX name=<?php echo "especialidade".$i; ?>
                                <?php if(isset($_POST["especialidade".$i])) { echo "checked"; } ?>
                                > 
                                <?php
                                echo $json["especialidades"][$i];
                                    }
                                ?>
                                
                                <BR>
                                 
                                <BR>
                                <INPUT type=SUBMIT value="Enviar">
                        
                            </FORM>
                            <?php
                                }
                                
    }
    else
       { //Para voltar a tela inicial do usuário de acordo com sua função na empresa
        header("location: proj_admin.php");                                       
       }     
        ?>
        
        
        
                        </BODY>
                    </HTML>
                