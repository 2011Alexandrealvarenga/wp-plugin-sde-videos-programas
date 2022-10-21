<?php
/*
 * Plugin Name: SDE - Videos Programas
 * Description: Plugin para Inserir, atualizar, ler e deletar Videos da area de programs na home
 * Version: 1.0
 * Author: Alexandre Alvarenga
 * Plugin URI: 
 * Author URI: 
 */

if(!function_exists('add_action')){
    echo 'Opa! Eu sou só um plugin, não posso ser chamado diretamente!';
    exit;
}

// setup
define('SDE_VIDEOS_PLUGIN_URL', __FILE__);

register_activation_hook(SDE_VIDEOS_PLUGIN_URL, 'sde_videos_table_creator');
register_uninstall_hook(SDE_VIDEOS_PLUGIN_URL, 'sde_videos_plugin');

// includes
include('functions.php');
include('enqueue.php');


add_action('admin_menu', 'sde_videos_da_display_esm_menu');
add_action('admin_enqueue_scripts', 'sde_videos_admin_enqueue_css');

