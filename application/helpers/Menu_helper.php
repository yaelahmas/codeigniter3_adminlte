<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('build_menu')) {
	function build_menu($menus)
	{
		$html = '';
		foreach ($menus as $item) {
			$treeview = '';
			if (!empty($item['child'])) {
				$treeview = 'class="treeview"';
			}
			$html .= '<li ' . $treeview . '>';
			$html .= '<a href="' . base_url($item['url']) . '">';
			$html .= '<i class="' . $item['icon'] . '"></i>';
			$html .= '<span>' . $item['nama_menu'] . '</span>';
			if (!empty($item['child'])) {
				$html .= '<span class="pull-right-container">';
				$html .= '<i class="fa fa-angle-left pull-right"></i>';
				$html .= '</span>';
			}
			$html .= '</a>';
			if (!empty($item['child'])) {
				$html .= '<ul class="treeview-menu">';
				$html .= build_menu($item['child']);
				$html .= '</ul>';
			}
			$html .= '</li>';
		}
		return $html;
	}
}
