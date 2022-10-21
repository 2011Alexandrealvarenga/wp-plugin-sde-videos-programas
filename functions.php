<?php 
function sde_videos_table_creator()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'videos_programa';
    $sql = "DROP TABLE IF EXISTS $table_name;
            CREATE TABLE $table_name(

            id mediumint(11) NOT NULL AUTO_INCREMENT,
            ordem varchar(10) NOT NULL,
            url varchar(100) NOT NULL,
            descricao varchar(100) NOT NULL,

            PRIMARY KEY id(id)
            )$charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function sde_videos_da_display_esm_menu()
{
    add_menu_page('Videos Programas', 'Videos Programas', 'manage_options', 'sde-lista-videos', 'SDE_lista_videos','', 8);
    add_submenu_page('sde-lista-videos', 'SDE - Lista Videos', 'SDE - Lista Videos', 'manage_options', 'sde-lista-videos', 'SDE_lista_videos');
    add_submenu_page(null, 'Videos Atualiza', 'Videos Atualiza', 'manage_options', 'update-videos', 'videos_da_emp_update_call');
    add_submenu_page(null, 'Delete Employee', 'Delete Employee', 'manage_options', 'delete-videos', 'sde_videos_da_emp_delete_call');
}

function SDE_lista_videos()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'videos_programa';
    $msg = '';
    if (isset($_REQUEST['submit'])) {
        $wpdb->insert("$table_name", [
            'url' => $_REQUEST['url'],
            'ordem' => $_REQUEST['ordem'],
            'descricao' => $_REQUEST['descricao']
        ]);

        if ($wpdb->insert_id > 0) {
            $msg = "Gravado com sucesso!";
        } else {
            $msg = "Falha ao gravar!";
        }
    }

    ?>
    <div class="content-pat">
        <h1 class="title">Programas - Videos</h1>
        <h2 class="subtitle">Cadastro de Videos</h2>
        <form method="post">
            <div class="cont">
                <div class="esq">
                    <span>Ordem de Exibição</span>
                </div>
                <input type="text" name="ordem" required><br>
            </div>
            <div class="cont">
                <div class="esq">
                    <span>Descrição</span>
                </div>
                <input type="text" name="descricao" required><br>
            </div>
            <div class="cont">
                <div class="esq">
                    <span>URL Video</span>
                </div>
                <input type="text" name="url" required><br>
            </div>
            
            <div class="cont">
                <div class="esq">
                    <h4 id="msg" class="alert"><?php echo $msg; ?></h4>
                    <button class="btn-pat" type="submit" name="submit">CADASTRAR</button>

                </div>
            </div>           
        </form>
    </div>
    <?php 

    $table_name = $wpdb->prefix . 'videos_programa';
    $employee_list = $wpdb->get_results($wpdb->prepare("select * FROM $table_name ORDER BY ordem asc "), ARRAY_A);
    if (count($employee_list) > 0): ?>  

        <!-- <div class="busca">
            <h3 class="subtitle">Realize a busca da unidade</h3>
            <input type="text" class="form-control" id="live_search" autocomplete="off" placeholder="Ex.: URL">
        </div>   
        <div id="searchresult" style="margin: 24px 10px 0 0; display: block;"></div>
        <script  src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

        <script type="text/javascript">
            $(document).ready(function(){
                $("#live_search").keyup(function(){
                    var input = $(this).val();
                    // alert(input);
                    var url_search =  "<?php echo site_url(); ?>/wp-content/plugins/sde-videos-programas/busca-resultado.php";
                    
                    if(input != ""){
                        $.ajax({                      
                            url:url_search,
                            method: "POST",
                            data:{input:input},

                            success:function(data){
                                $("#searchresult").html(data);
                                $("#searchresult").css('display','block');
                                $("#registros-todos-dados-tabela").css('display','none');
                            }
                        });
                    }else{
                        $("#searchresult").css("display","none");
                        $("#registros-todos-dados-tabela").css('display','block');
                    }
                });
            });
        </script>   
        <div id="registros-todos-dados-tabela" style="margin: 24px 10px 0 0;">
            <?php sde_videos_resultado_busca($employee_list);?>
        </div>
    <?php else:echo "<h2>Não há Informação</h2>";endif;
}


function sde_videos_resultado_busca($employee_list){?>
    <table border="1" cellpadding="5" width="100%">
        <tr>
            <th>ID</th>
            <th>Ordem de Exibição</th> 
            <th>Descrição</th>            
            <th>URL</th>

            <th>Editar</th>
            <th>Deletar</th>
        </tr>
        <?php $i = 1;
        foreach ($employee_list as $index => $employee): ?>
            <tr>
                <td><?php echo $i++; ?></td>
                <td><?php echo $employee['ordem']; ?></td>
                <td><?php echo $employee['descricao']; ?></td>
                <td><?php echo $employee['url']; ?></td>

                <td><a href="admin.php?page=update-videos&id=<?php echo $employee['id']; ?>" class="btn-editar">EDITAR</a></td>
                <td><a href="admin.php?page=delete-videos&id=<?php echo $employee['id']; ?>" class="btn-deletar">DELETAR</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php }

function videos_da_emp_update_call()
{
    global $wpdb;
    
    $url = site_url();
    $url2 = '/wp-admin/admin.php?page=sde-lista-videos';
    $urlvoltar = $url.$url2;

    $table_name = $wpdb->prefix . 'videos_programa';
    $msg = '';
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    
    $employee_details = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name where id = %d", $id), ARRAY_A); ?>
   <div class="content-pat">
        <h1 class="title">PAT - Unidades</h1>
        <h2 class="subtitle">Atualização de Cadastro de Unidade</h2>
        <form method="post"> 
            <div class="cont">
                <div class="esq">
                    <span>Ordem de exibição</span>
                </div>
                <input type="text" name="ordem" value="<?php echo $employee_details['ordem']; ?>" required><br>
            </div>     
            <div class="cont">
                <div class="esq">
                    <span>Descrição</span>
                </div>
                <input type="text" name="descricao" value="<?php echo $employee_details['descricao']; ?>" required><br>
            </div>  
            <div class="cont">
                <div class="esq">
                    <span>URL</span>
                </div>
                <input type="text" name="url" value="<?php echo $employee_details['url']; ?>" required><br>
            </div> 
            
            <div class="cont">
                <div class="esq">
                    <button class="btn-pat" type="submit" name="update">ATUALIZAR</button>
                </div>
            </div>
            <div class="cont">
                <div class="esq">
                    <?php                     
                        if (isset($_REQUEST['update'])) {
                            if (!empty($id)) {
                                $wpdb->update("$table_name", [
                                    "url" => $_REQUEST['url'],
                                    "ordem" => $_REQUEST['ordem'],
                                    "descricao" => $_REQUEST['descricao']           
                            ], ["id" => $id]);
                                $msg = 'Atualização realizada!';
                                echo '<h4 class="alert">    '. $msg .'</h4>';
                                echo '<a href="'. $urlvoltar.'" class="link-back">Voltar para a lista</a>';
                            }
                        }
                    ?>
                    
                </div>
            </div> 
        </form>
<?php }

function sde_videos_da_emp_delete_call()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'videos_programa';
    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
    if (isset($_REQUEST['delete'])) {
        if ($_REQUEST['conf'] == 'yes') {
            $row_exits = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
            if (count($row_exits) > 0) {
                $wpdb->delete("$table_name", array('id' => $id,));
            }
        } ?>
        <script>location.href = "<?php echo site_url(); ?>/wp-admin/admin.php?page=sde-lista-videos";</script>
    <?php } ?>
    <form method="post">
        <div class="content-pat">
            <h1 class="title">VIDEOS  - Programas</h1>
            <h2 class="subtitle">Exclusão de cadastro</h2>

            <h3 class="description">Deseja realmente apagar?</h3 >
            <input type="radio" name="conf" value="yes" checked>Sim
            <input type="radio" name="conf" value="no" >Não  <br><br>      
        
            <button class="btn-pat" type="submit" name="delete">OK</button>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </div>        
    </form>
<?php }