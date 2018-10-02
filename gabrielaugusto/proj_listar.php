<?php

   if (isset($_SESSION["usuario"]))
   { //Para que não se consiga inserir/alterar/listar dados importantes se não for um admin ou se não for um secretário ou médico atendendo um cliente
     
      $valido=false;        
         
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
                       
                  ?>
               
                     <HTML>
                  <HEAD>
                     <TITLE>Lista de médicos</TITLE>
                  </HEAD>
                  <BODY>
                     <TABLE border=2 style="width:100%" >
                                       
                              
                              
                                    <TR>
                                          <TH>Nome</TH>
                                          <TH>CPF</TH>
                                          <TH>CRM</TH>
                                          <TH>Telefone</TH>
                                          <TH>Email</TH>
                                          <TH>Ocupação</TH>
                                          <TH>Especialidades</TH>
                                          <TH>Ações</TH>

                                    </TR>                                          
                     <?php
                        
                              if (isset($_REQUEST["excluir"]) && $_REQUEST["excluir"]==true )
                              {
                                    
                                 
                                    $stmt = $connection->prepare("DELETE FROM medicos WHERE id =?");
                                 
                                    $stmt->bindParam(1,$_REQUEST["id"]); //PARA TROCAR QUEM TEM O ID 'X' POR '?' 
                                    $stmt->execute();
                              
                                    if($stmt->errorCode()!="0000")
                                    {
                                          echo "Erro código".$stmt->errorCode() . ":";
                                          echo  implode(",", $stmt->errorInfo());
                                    }
                                                                     
                              }
                              
                              $rs =$connection->prepare("SELECT * FROM medicos");
                                 
                              
                              if($rs->execute()) // RETORNA 1 CASO A OPERAÇÃO SEJA REALIZADA COM SUCESSO
                              {
                                    while($registro = $rs->fetch(PDO::FETCH_OBJ))//Retorna o usuario seguinte em cada iteração
                                    {
                                    echo "<TR>";
                                 
                                    echo "<TD>  ".$registro->nome."     </TD>";
                                    echo "<TD class= cpf>  ".$registro->cpf."      </TD>";
                                    echo "<TD>  ".$registro->crm."  </TD>";
                                    echo "<TD class=telefone>  ".$registro->telefone."   </TD>";
                                    echo "<TD>  ".$registro->email."      </TD>";
                                    echo "<TD>  ".$registro->ocupacao." </TD>";                                 
                                    echo "<TD>  ".$registro->especialidades." </TD>";
                                    ?>
        
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
                    $('.cpf').mask("000.000.000-00");
                  
        
                        </script>
                        
                              
                                    <?php
                                    echo "<TD>";
                                          echo"<A href='?excluir=true&id=".$registro->id."'> (excluir) </A><BR>";
                                 
                                          echo"<A href='proj_alterar.php?id=".$registro->id."'> (alterar) </A><BR>";
                                          
                                    echo"</TD>"; 
                                 
                                    echo "</TR>";
                                    
                                    }
                              }
                              else
                              {
                                 echo "Falha na seleção do médico<BR>";
                              }      
   }
   else
       { 
                                   
      header("location: proj_admin.php");
                                        
       }     
         ?>
            
               </TABLE>
               <BR>
            </BODY>
      </HTML>