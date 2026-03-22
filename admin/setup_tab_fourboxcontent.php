<?php
// TAB: Four Box Content (original Revolution Pro)
print '<div role="tabpanel" id="revoprofourboxcontent" class="tab-pane ">';
print '<div class="site-skintools2" >';
	print '<div class="site-skintools2-inner">';
	print '<div class="site-skintools2-content" style="min-height: initial !important;">';
	print '<div class="nav-tabs-horizontal">';
	print '<div class="tab-content">';
	print '<br>';
	print '<table class="tableconfigurationrevopro tagtable liste">';

	$boxes = array(
		array('val' => $val8, 'valc' => $val8c, 'name' => 'value8', 'namec' => 'value8c', 'label' => 'RevolutionProfirstbox', 'class' => 'firstbox'),
		array('val' => $val9, 'valc' => $val9c, 'name' => 'value9', 'namec' => 'value9c', 'label' => 'RevolutionProsecondtbox', 'class' => 'secondbox'),
		array('val' => $val10, 'valc' => $val10c, 'name' => 'value10', 'namec' => 'value10c', 'label' => 'RevolutionProthirdbox', 'class' => 'thirdbox'),
		array('val' => $val11, 'valc' => $val11c, 'name' => 'value11', 'namec' => 'value11c', 'label' => 'RevolutionProfourthbox', 'class' => 'fourthbox'),
	);
	foreach ($boxes as $box) {
		print '<tr class="oddeven '.$box['class'].'">';
		print '<td>'.$langs->trans($box['label']).'</td>';
		print '<td>';
		print $object->selectFourBoxContent($box['val'], $box['name']);
		print '<div class="radiocolorfourboxcontent">';
		foreach(array('indigo-400','green-300','purple-300','amber-600') as $c) {
			$chd = ($box['valc'] == $c) ? 'checked' : '';
			print '<div class="radio-custom radio-'.$c.'"><input id="box-'.$box['namec'].'-'.$c.'" type="radio" name="'.$box['namec'].'" '.$chd.' value="'.$c.'"><label for="box-'.$box['namec'].'-'.$c.'"> </label></div>';
		}
		print '</div>';
		print '</td>';
		print '</tr>';
	}
	print '</table>';
	print '</div>';
	print '</div>';
	print '</div>';
	print '</div>';
print '</div>';
print '</div>';
