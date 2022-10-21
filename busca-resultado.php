<?php
 
global $wpdb;
require_once('../../../wp-config.php');
if(isset($_POST['input'])){
    $input = $_POST['input'];
    $query = $wpdb->get_results("SELECT * FROM videos_programas WHERE
        url LIKE '%{$url}%'
    ");

    if($query > 0){
        
        ?>
        <div class="content-pat">
            <table class="table table-bordered table-striped mt-4" border="1" cellpadding="10" width="90%">
                <thead>
                    <tr>
                        <th>ID</th>  
                        <th>url</th>  
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($query as $row){?>
                    <tr>
                        <td><?php echo $row->id;?></td>
                        <td><?php echo $row->url;?></td>
                        <td><a href="admin.php?page=update-videos&id=<?php echo $row->id;?>" class="btn-editar">EDITAR</a></td>
                        <td><a href="admin.php?page=delete-videos&id=<?php echo $row->id;?>" class="btn-deletar">DELETAR</a></td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    <?php
    }else{
        echo "<h6 class='text-danger text-center mt-3'>Não foi encontrado informações</h6>";
    }
}
