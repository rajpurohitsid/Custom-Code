<?php

/**
* @param int $list
* List of Nodes
*/
function hook_mycustom_hook(&$list): void {
	foreach ($list as $key => $nid) {
		# code...
	}
}